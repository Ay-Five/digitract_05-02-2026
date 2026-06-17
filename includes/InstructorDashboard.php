<?php
require_once __DIR__ . '/BaseDashboard.php';
require_once __DIR__ . '/Notifiable.php';

/**
 * InstructorDashboard.php
 *
 * INHERITANCE   → extends BaseDashboard (gets db, userId, redirect, notify, etc.)
 * ABSTRACTION   → fulfills handlePost() and loadData() contracts
 * POLYMORPHISM  → implements Notifiable (same interface as Admin and Student)
 * ENCAPSULATION → private action methods; instructor-specific logic hidden inside
 */
class InstructorDashboard extends BaseDashboard implements Notifiable
{
    private const SELF_FILE = 'instructor_dashboard.php';
    private const USER_TYPE = 'instructor';

    public function __construct(Database $db, int $instructorId)
    {
        parent::__construct($db, $instructorId);
    }

    // ── Notifiable interface (POLYMORPHISM) ───────────────────────────────

    public function getUnreadCount(): int
    {
        return $this->db->count(
            "SELECT COUNT(*) AS c FROM notifications
             WHERE user_id={$this->userId} AND user_type='instructor' AND is_read=0"
        );
    }

    public function markNotificationsRead(): void
    {
        $this->db->query(
            "UPDATE notifications SET is_read=1
             WHERE user_id={$this->userId} AND user_type='instructor'"
        );
    }

    public function getNotifications(int $limit = 30): array
    {
        return $this->db->rows(
            "SELECT * FROM notifications
             WHERE user_id={$this->userId} AND user_type='instructor'
             ORDER BY created_at DESC LIMIT $limit"
        );
    }

    // ── Abstract method: handlePost (ABSTRACTION fulfilled) ───────────────

    public function handlePost(string $action): void
    {
        match ($action) {
            'approve_appointment' => $this->approveAppointment(),
            'reject_appointment'  => $this->rejectAppointment(),
            'add_session'         => $this->addSession(),
            'rate_student'        => $this->rateStudent(),
            'issue_certificate'   => $this->issueCertificate(),
            'add_course'          => $this->addCourse(),
            'edit_course'         => $this->editCourse(),
            'update_course'       => $this->updateCourse(),
            'toggle_course'       => $this->toggleCourse(),
            'delete_course'       => $this->deleteCourse(),
            'update_profile'      => $this->updateProfile(),
            'mark_read'           => $this->markReadAndRedirect(),
            'send_message'        => $this->sendMessage(),
            'mark_conv_read'      => $this->markConvRead(),
            default               => null,
        };
    }

    // ── Abstract method: loadData (ABSTRACTION fulfilled) ─────────────────

    public function loadData(): void
    {
        $db  = $this->db;
        $uid = $this->userId;
        $t   = $this->today;

        // ── Core stats ────────────────────────────────────────────────────
        $this->data['statPending'] = $db->count("SELECT COUNT(*) AS c FROM appointments WHERE instructor_id=$uid AND status='pending'");
        $this->data['statActive']  = $db->count("SELECT COUNT(*) AS c FROM appointments WHERE instructor_id=$uid AND status IN('approved','in_progress')");
        $this->data['statDone']    = $db->count("SELECT COUNT(*) AS c FROM appointments WHERE instructor_id=$uid AND status='completed'");
        $this->data['avgRating']   = $db->scalar("SELECT ROUND(AVG(rating),1) AS r FROM instructor_ratings WHERE instructor_id=$uid", 'r');

        // ── Instructor profile ────────────────────────────────────────────
        $this->data['instructor'] = $db->row("SELECT * FROM instructors WHERE instructor_id=$uid");

        // ── Notifications ─────────────────────────────────────────────────
        $this->data['unreadCnt']   = $this->getUnreadCount();
        $this->data['notifs']      = $this->getNotifications();

        // ── Unread messages per student ───────────────────────────────────
        $unreadMsgMap = [];
        foreach ($db->rows("SELECT sender_id,COUNT(*) AS cnt FROM messages WHERE receiver_id=$uid AND receiver_type='instructor' AND is_read=0 AND sender_type='student' GROUP BY sender_id") as $row) {
            $unreadMsgMap[(int)$row['sender_id']] = (int)$row['cnt'];
        }
        $this->data['unreadMsgMap']   = $unreadMsgMap;
        $this->data['totalUnreadMsg'] = array_sum($unreadMsgMap);
        $this->data['chatStudentId']  = (int)($_GET['chat_student'] ?? 0);

        // ── Appointments ──────────────────────────────────────────────────
        $this->data['pendingAppts'] = $db->rows(
            "SELECT a.*,ic.course_type,s.fullname AS sname,s.email AS semail
             FROM appointments a
             JOIN instructor_courses ic ON ic.course_id=a.course_id
             JOIN students s ON s.student_id=a.student_id
             WHERE a.instructor_id=$uid AND a.status='pending'
             ORDER BY a.start_date ASC, a.requested_at ASC"
        );
        $this->data['allAppts'] = $db->rows(
            "SELECT a.*,ic.course_type,s.fullname AS sname,
                    (SELECT SUM(hours_covered) FROM sessions WHERE appointment_id=a.appointment_id AND status='completed') AS hours_done,
                    tc.cert_id
             FROM appointments a
             JOIN instructor_courses ic ON ic.course_id=a.course_id
             JOIN students s ON s.student_id=a.student_id
             LEFT JOIN tdc_certificates tc ON tc.appointment_id=a.appointment_id
             WHERE a.instructor_id=$uid
             ORDER BY a.start_date DESC, a.requested_at DESC"
        );

        // ── My students ───────────────────────────────────────────────────
        $this->data['myStudents'] = $db->rows(
            "SELECT DISTINCT s.student_id,s.fullname,s.email,
               (SELECT ic.course_type FROM appointments a2 JOIN instructor_courses ic ON ic.course_id=a2.course_id
                WHERE a2.instructor_id=$uid AND a2.student_id=s.student_id ORDER BY a2.requested_at DESC LIMIT 1) AS latest_course,
               (SELECT a3.status FROM appointments a3 WHERE a3.instructor_id=$uid AND a3.student_id=s.student_id ORDER BY a3.requested_at DESC LIMIT 1) AS latest_status
             FROM students s
             WHERE EXISTS(SELECT 1 FROM appointments a WHERE a.instructor_id=$uid AND a.student_id=s.student_id AND a.status IN('approved','in_progress','completed','pending'))
             ORDER BY s.fullname ASC"
        );

        // ── Unrated sessions ──────────────────────────────────────────────
        $this->data['unratedSessions'] = $db->rows(
            "SELECT sess.*,a.course_id,ic.course_type,s.fullname AS sname
             FROM sessions sess
             JOIN appointments a ON a.appointment_id=sess.appointment_id
             JOIN instructor_courses ic ON ic.course_id=a.course_id
             JOIN students s ON s.student_id=sess.student_id
             WHERE sess.instructor_id=$uid AND sess.status='completed'
               AND (sess.performance_rating IS NULL OR sess.grade IS NULL)
               AND ic.course_type LIKE 'TDC%'
             ORDER BY sess.session_date DESC"
        );

        // ── Courses ───────────────────────────────────────────────────────
        $this->data['myCourses'] = $db->rows(
            "SELECT * FROM instructor_courses WHERE instructor_id=$uid ORDER BY course_type"
        );

        // ── All sessions ──────────────────────────────────────────────────
        $this->data['allSessions'] = $db->rows(
            "SELECT s.*,st.fullname AS sname,ic.course_type
             FROM sessions s
             JOIN students st ON st.student_id=s.student_id
             JOIN appointments a ON a.appointment_id=s.appointment_id
             JOIN instructor_courses ic ON ic.course_id=a.course_id
             WHERE s.instructor_id=$uid
             ORDER BY s.session_date DESC LIMIT 50"
        );

        // ── Certs ready to issue ──────────────────────────────────────────
        $this->data['certReady'] = $db->rows(
            "SELECT a.*,s.fullname AS sname,ic.course_type
             FROM appointments a
             JOIN students s ON s.student_id=a.student_id
             JOIN instructor_courses ic ON ic.course_id=a.course_id
             WHERE a.instructor_id=$uid AND a.status='completed' AND ic.course_type LIKE 'TDC%'
               AND NOT EXISTS(SELECT 1 FROM tdc_certificates WHERE appointment_id=a.appointment_id)
             ORDER BY a.completed_at DESC"
        );

        // ── Top students this month ───────────────────────────────────────
        $this->data['topStudents'] = $db->rows(
            "SELECT s.student_id,s.fullname,ROUND(AVG(sess.grade),1) AS avg_grade,COUNT(sess.session_id) AS sess_cnt
             FROM sessions sess JOIN students s ON s.student_id=sess.student_id
             WHERE sess.instructor_id=$uid AND sess.grade IS NOT NULL
               AND DATE_FORMAT(sess.session_date,'%Y-%m')=DATE_FORMAT(NOW(),'%Y-%m')
             GROUP BY s.student_id ORDER BY avg_grade DESC LIMIT 5"
        );

        // ── Chart data ────────────────────────────────────────────────────
        $months = $this->buildMonthLabels();
        $sessPerMonth = [];
        foreach ($months as $label) {
            $ym = date('Y-m', strtotime($label));
            $sessPerMonth[] = (float)($db->scalar(
                "SELECT COALESCE(SUM(hours_covered),0) AS h FROM sessions
                 WHERE instructor_id=$uid AND status='completed' AND DATE_FORMAT(session_date,'%Y-%m')='$ym'", 'h') ?? 0);
        }
        $this->data['instrMonths']      = $months;
        $this->data['instrSessPerMonth']= $sessPerMonth;

        $buckets = ['90-100' => 0, '80-89' => 0, '70-79' => 0, '60-69' => 0, '<60' => 0];
        foreach ($db->rows("SELECT grade FROM sessions WHERE instructor_id=$uid AND grade IS NOT NULL AND status='completed'") as $row) {
            $g = (int)$row['grade'];
            if ($g >= 90)     $buckets['90-100']++;
            elseif ($g >= 80) $buckets['80-89']++;
            elseif ($g >= 70) $buckets['70-79']++;
            elseif ($g >= 60) $buckets['60-69']++;
            else              $buckets['<60']++;
        }
        $this->data['instrGradeBuckets'] = $buckets;

        // ── Sidebar stats ─────────────────────────────────────────────────
        $this->data['sbTotalStudents']  = $db->count("SELECT COUNT(DISTINCT student_id) AS c FROM appointments WHERE instructor_id=$uid AND status IN('approved','in_progress','completed')");
        $this->data['sbTotalHrsTaught'] = (float)($db->scalar("SELECT COALESCE(SUM(hours_covered),0) AS h FROM sessions WHERE instructor_id=$uid AND status='completed'", 'h') ?? 0);
        $this->data['sbMonthHrs']       = (float)($db->scalar("SELECT COALESCE(SUM(hours_covered),0) AS h FROM sessions WHERE instructor_id=$uid AND status='completed' AND DATE_FORMAT(session_date,'%Y-%m')=DATE_FORMAT(NOW(),'%Y-%m')", 'h') ?? 0);
        $this->data['sbCertsIssued']    = $db->count("SELECT COUNT(*) AS c FROM tdc_certificates WHERE issued_by=$uid");
        $this->data['sbTotalSess']      = $db->count("SELECT COUNT(*) AS c FROM sessions WHERE instructor_id=$uid AND status='completed'");

        // ── Flash ─────────────────────────────────────────────────────────
        $this->data['flash'] = $_SESSION['flash'] ?? null; unset($_SESSION['flash']);
    }

    // ── Private action methods (ENCAPSULATION) ────────────────────────────

    private function approveAppointment(): void
    {
        $id   = (int)($_POST['appointment_id'] ?? 0);
        $note = $this->db->escape($_POST['instructor_note'] ?? '');
        $uid  = $this->userId;
        $this->db->query("UPDATE appointments SET status='approved',instructor_note='$note',reviewed_at=NOW() WHERE appointment_id=$id AND instructor_id=$uid AND status='pending'");
        $aRow = $this->db->row("SELECT a.*,s.student_id,ic.course_type FROM appointments a JOIN students s ON s.student_id=a.student_id JOIN instructor_courses ic ON ic.course_id=a.course_id WHERE a.appointment_id=$id");
        if ($aRow) $this->notify($aRow['student_id'], 'student', "Your appointment for {$aRow['course_type']} has been APPROVED!", 'appointment_approved');
        $this->redirect(self::SELF_FILE . '?section=appointments', 'Appointment approved.');
    }

    private function rejectAppointment(): void
    {
        $id   = (int)($_POST['appointment_id'] ?? 0);
        $note = $this->db->escape($_POST['instructor_note'] ?? '');
        $uid  = $this->userId;
        $this->db->query("UPDATE appointments SET status='rejected',instructor_note='$note',reviewed_at=NOW() WHERE appointment_id=$id AND instructor_id=$uid AND status='pending'");
        $aRow = $this->db->row("SELECT a.*,s.student_id,ic.course_type FROM appointments a JOIN students s ON s.student_id=a.student_id JOIN instructor_courses ic ON ic.course_id=a.course_id WHERE a.appointment_id=$id");
        if ($aRow) $this->notify($aRow['student_id'], 'student', "Your appointment for {$aRow['course_type']} was declined.", 'appointment_rejected');
        $this->redirect(self::SELF_FILE . '?section=appointments', 'Appointment rejected.');
    }

    private function addSession(): void
    {
        $uid       = $this->userId;
        $appt_id   = (int)($_POST['appointment_id'] ?? 0);
        $sess_date = $this->db->escape($_POST['session_date'] ?? $this->today);
        $hrs       = max(0.5, min(8, (float)($_POST['hours_covered'] ?? 1)));
        $grade     = (int)($_POST['grade'] ?? 0);
        $grade     = ($grade >= 1 && $grade <= 100) ? $grade : null;
        $improve   = $this->db->escape($_POST['improvements'] ?? '');
        $notes     = $this->db->escape($_POST['performance_notes'] ?? '');

        $this->db->query("UPDATE appointments SET status='in_progress' WHERE appointment_id=$appt_id AND instructor_id=$uid AND status='approved'");
        $aRow = $this->db->row("SELECT * FROM appointments WHERE appointment_id=$appt_id AND instructor_id=$uid");
        if ($aRow) {
            $typeRow  = $this->db->row("SELECT course_type FROM instructor_courses WHERE course_id={$aRow['course_id']}");
            $sessType = stripos($typeRow['course_type'], 'TDC') === 0 ? 'TDC' : 'PDC';
            $sessNum  = $this->db->count("SELECT COUNT(*)+1 AS c FROM sessions WHERE appointment_id=$appt_id") ;
            $gradeSQL = $grade === null ? 'NULL' : $grade;
            $this->db->query("INSERT INTO sessions (appointment_id,session_type,session_number,student_id,instructor_id,session_date,hours_covered,status,grade,improvements,performance_notes) VALUES ($appt_id,'$sessType',$sessNum,{$aRow['student_id']},$uid,'$sess_date',$hrs,'completed',$gradeSQL,'$improve','$notes')");

            $doneHrs = (float)($this->db->scalar("SELECT COALESCE(SUM(hours_covered),0) AS h FROM sessions WHERE appointment_id=$appt_id AND status='completed'", 'h') ?? 0);
            if ($doneHrs >= $aRow['total_hours']) {
                $this->db->query("UPDATE appointments SET status='completed',completed_at=NOW() WHERE appointment_id=$appt_id");
                if ($sessType === 'TDC') {
                    $og = (float)($this->db->scalar("SELECT ROUND(AVG(grade),1) AS ag FROM sessions WHERE appointment_id=$appt_id AND grade IS NOT NULL", 'ag') ?? 0);
                    if ($og > 0) $this->db->query("UPDATE appointments SET tdc_overall_grade=ROUND($og) WHERE appointment_id=$appt_id");
                }
                $this->notify($aRow['student_id'], 'student', 'Your course is complete! Please rate your instructor.', 'course_completed');
            }
        }
        $this->redirect(self::SELF_FILE . '?section=appointments', 'Session logged.');
    }

    private function rateStudent(): void
    {
        $uid     = $this->userId;
        $sessId  = (int)($_POST['session_id'] ?? 0);
        $rating  = max(1, min(5, (int)($_POST['performance_rating'] ?? 5)));
        $notes   = $this->db->escape($_POST['performance_notes'] ?? '');
        $grade   = (int)($_POST['grade'] ?? 0);
        $grade   = ($grade >= 1 && $grade <= 100) ? $grade : null;
        $improve = $this->db->escape($_POST['improvements'] ?? '');
        $gradeSQL= $grade === null ? 'NULL' : $grade;
        $this->db->query("UPDATE sessions SET performance_rating=$rating,performance_notes='$notes',grade=$gradeSQL,improvements='$improve' WHERE session_id=$sessId AND instructor_id=$uid");
        $sessRow = $this->db->row("SELECT s.*,ic.course_type FROM sessions s JOIN appointments a ON a.appointment_id=s.appointment_id JOIN instructor_courses ic ON ic.course_id=a.course_id WHERE s.session_id=$sessId");
        if ($sessRow && stripos($sessRow['course_type'], 'TDC') === 0) {
            $og = (float)($this->db->scalar("SELECT ROUND(AVG(grade),1) AS ag FROM sessions WHERE appointment_id={$sessRow['appointment_id']} AND grade IS NOT NULL", 'ag') ?? 0);
            if ($og > 0) $this->db->query("UPDATE appointments SET tdc_overall_grade=ROUND($og) WHERE appointment_id={$sessRow['appointment_id']}");
        }
        $this->redirect(self::SELF_FILE . '?section=performance', 'Session updated.');
    }

    private function issueCertificate(): void
    {
        $uid      = $this->userId;
        $appt_id  = (int)($_POST['appointment_id'] ?? 0);
        $certNum  = $this->db->escape($_POST['cert_number'] ?? '');
        $delivery = in_array($_POST['delivery_method'] ?? '', ['online','face_to_face']) ? $_POST['delivery_method'] : 'face_to_face';
        $aRow     = $this->db->row("SELECT * FROM appointments WHERE appointment_id=$appt_id AND instructor_id=$uid AND status='completed'");
        if ($aRow) {
            $certImage = 'NULL';
            if ($delivery === 'online' && isset($_FILES['cert_image']) && $_FILES['cert_image']['error'] === 0) {
                $ext = strtolower(pathinfo($_FILES['cert_image']['name'], PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg','jpeg','png','gif','webp'])) {
                    $uploadPath = '../assets/uploads/certs/';
                    if (!is_dir($uploadPath)) mkdir($uploadPath, 0755, true);
                    $fname = 'tcert_' . $aRow['student_id'] . '_' . time() . '.' . $ext;
                    if (move_uploaded_file($_FILES['cert_image']['tmp_name'], $uploadPath . $fname)) $certImage = "'$fname'";
                }
            }
            $this->db->query("INSERT IGNORE INTO tdc_certificates (student_id,appointment_id,issued_by,cert_number,delivery_method,cert_image) VALUES ({$aRow['student_id']},$appt_id,$uid,'$certNum','$delivery',$certImage)");
            $msg = $delivery === 'online' ? 'Your TDC Certificate has been issued online! PDC is now unlocked.' : 'Your TDC Certificate is ready! Please collect it in person. PDC is now unlocked.';
            $this->notify($aRow['student_id'], 'student', $msg, 'certificate_issued');
        }
        $this->redirect(self::SELF_FILE . '?section=performance', 'Certificate issued.');
    }

    private function addCourse(): void
    {
        $uid   = $this->userId;
        $ct    = $this->db->escape($_POST['course_type'] ?? '');
        $desc  = $this->db->escape($_POST['description'] ?? '');
        $phr   = (float)($_POST['price_per_hr'] ?? 0);
        $limitRaw = $_POST['student_limit'] ?? '';
        $limit = ($limitRaw !== '' && (int)$limitRaw > 0) ? (int)$limitRaw : 'NULL';
        $this->db->query("INSERT INTO instructor_courses (instructor_id,course_type,description,price_per_hr,student_limit) VALUES ($uid,'$ct','$desc',$phr,$limit)");
        $this->redirect(self::SELF_FILE . '?section=courses', 'Course added.');
    }

    private function editCourse(): void
    {
        // Redirects to courses section with edit_course param — modal opens in view
        $cid = (int)($_POST['course_id'] ?? 0);
        $this->redirect(self::SELF_FILE . '?section=courses&edit_course=' . $cid);
    }

    private function updateCourse(): void
    {
        $uid      = $this->userId;
        $cid      = (int)($_POST['course_id'] ?? 0);
        $desc     = $this->db->escape($_POST['description'] ?? '');
        $phr      = (float)($_POST['price_per_hr'] ?? 0);
        $limitRaw = $_POST['student_limit'] ?? '';
        $limit    = ($limitRaw !== '' && (int)$limitRaw > 0) ? (int)$limitRaw : 'NULL';

        // Update the course itself
        $this->db->query("UPDATE instructor_courses SET description='$desc',price_per_hr=$phr,student_limit=$limit WHERE course_id=$cid AND instructor_id=$uid");

        // Sync student_limit into all future course_schedules rows for this course
        // so the admin workload view always reflects the current limit
        $this->db->query("UPDATE course_schedules SET student_limit=$limit WHERE course_id=$cid AND schedule_date >= CURDATE()");

        $this->redirect(self::SELF_FILE . '?section=courses', 'Course updated.');
    }

    private function toggleCourse(): void
    {
        $cid = (int)($_POST['course_id'] ?? 0);
        $uid = $this->userId;
        $this->db->query("UPDATE instructor_courses SET is_active=1-is_active WHERE course_id=$cid AND instructor_id=$uid");
        $this->redirect(self::SELF_FILE . '?section=courses');
    }

    private function deleteCourse(): void
    {
        $cid = (int)($_POST['course_id'] ?? 0);
        $uid = $this->userId;
        $this->db->query("DELETE FROM instructor_courses WHERE course_id=$cid AND instructor_id=$uid");
        $this->redirect(self::SELF_FILE . '?section=courses');
    }

    private function updateProfile(): void
    {
        $uid    = $this->userId;
        $name   = $this->db->escape($_POST['fullname'] ?? '');
        $email  = $this->db->escape($_POST['email'] ?? '');
        $mobile = $this->db->escape($_POST['mobile'] ?? '');
        $bio    = $this->db->escape($_POST['bio'] ?? '');
        $certSQL = '';
        if (isset($_FILES['certificate_image']) && $_FILES['certificate_image']['error'] === 0) {
            $ext = strtolower(pathinfo($_FILES['certificate_image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','gif','webp','pdf'])) {
                $path = '../assets/uploads/certificates/';
                if (!is_dir($path)) mkdir($path, 0755, true);
                $fname = 'cert_' . $uid . '_' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['certificate_image']['tmp_name'], $path . $fname)) $certSQL = ",certificate_image='$fname'";
            }
        }
        $this->db->query("UPDATE instructors SET fullname='$name',email='$email',mobile='$mobile',bio='$bio',modified_date=NOW()$certSQL WHERE instructor_id=$uid");
        $this->redirect(self::SELF_FILE . '?section=profile', 'Profile updated.');
    }

    private function markReadAndRedirect(): void
    {
        $this->markNotificationsRead();
        $this->redirect(self::SELF_FILE . '?section=messages');
    }

    private function sendMessage(): void
    {
        $uid    = $this->userId;
        $toId   = (int)($_POST['to_student_id'] ?? 0);
        $body   = $this->db->escape(trim($_POST['body'] ?? ''));
        $apptId = (int)($_POST['appointment_id'] ?? 0);
        $ok     = $this->db->count("SELECT COUNT(*) AS c FROM appointments WHERE student_id=$toId AND instructor_id=$uid AND status IN('pending','approved','in_progress','completed')");
        if ($ok > 0 && $body !== '') {
            $apptCol = $apptId > 0 ? $apptId : 'NULL';
            $this->db->query("INSERT INTO messages (sender_id,sender_type,receiver_id,receiver_type,appointment_id,body) VALUES ($uid,'instructor',$toId,'student',$apptCol,'$body')");
            $this->notify($toId, 'student', '💬 Message from your instructor.', 'message');
        }
        $this->redirect(self::SELF_FILE . "?section=chat&chat_student=$toId");
    }

    private function markConvRead(): void
    {
        $uid = $this->userId;
        $sid = (int)($_POST['student_id'] ?? 0);
        $this->db->query("UPDATE messages SET is_read=1 WHERE receiver_id=$uid AND receiver_type='instructor' AND sender_id=$sid AND sender_type='student'");
        $this->redirect(self::SELF_FILE . "?section=chat&chat_student=$sid");
    }
}
