<?php
require_once __DIR__ . '/BaseDashboard.php';
require_once __DIR__ . '/Notifiable.php';

/**
 * StudentDashboard.php
 *
 * INHERITANCE   → extends BaseDashboard
 * ABSTRACTION   → fulfills handlePost() and loadData()
 * POLYMORPHISM  → implements Notifiable interface
 * ENCAPSULATION → all student-specific logic is private
 */
class StudentDashboard extends BaseDashboard implements Notifiable
{
    private const SELF_FILE = 'student_dashboard.php';
    private const USER_TYPE = 'student';

    public function __construct(Database $db, int $studentId)
    {
        parent::__construct($db, $studentId);
    }

    // ── Notifiable interface (POLYMORPHISM) ───────────────────────────────

    public function getUnreadCount(): int
    {
        return $this->db->count(
            "SELECT COUNT(*) AS c FROM notifications
             WHERE user_id={$this->userId} AND user_type='student' AND is_read=0"
        );
    }

    public function markNotificationsRead(): void
    {
        $this->db->query(
            "UPDATE notifications SET is_read=1
             WHERE user_id={$this->userId} AND user_type='student'"
        );
    }

    public function getNotifications(int $limit = 30): array
    {
        return $this->db->rows(
            "SELECT * FROM notifications
             WHERE user_id={$this->userId} AND user_type='student'
             ORDER BY created_at DESC LIMIT $limit"
        );
    }

    // ── Abstract method: handlePost (ABSTRACTION fulfilled) ───────────────

    public function handlePost(string $action): void
    {
        match ($action) {
            'book_appointment'     => $this->bookAppointment(),
            'cancel_appointment'   => $this->cancelAppointment(),
            'rate_instructor'      => $this->rateInstructor(),
            'mark_read_student'    => $this->markReadAndRedirect(),
            'send_message_student' => $this->sendMessage(),
            'update_profile'       => $this->updateProfile(),
            default                => null,
        };
    }

    // ── Abstract method: loadData (ABSTRACTION fulfilled) ─────────────────

    public function loadData(): void
    {
        $db  = $this->db;
        $uid = $this->userId;
        $ym  = date('Y-m');

        // ── Profile ───────────────────────────────────────────────────────
        $this->data['student']  = $db->row("SELECT * FROM students WHERE student_id=$uid");

        // ── TDC completion check ──────────────────────────────────────────
        $this->data['tdcDone'] = $db->count(
            "SELECT COUNT(*) AS c FROM appointments a
             JOIN instructor_courses ic ON ic.course_id=a.course_id
             WHERE a.student_id=$uid AND ic.course_type IN('TDC Face-to-Face','TDC Online') AND a.status='completed'"
        );

        // ── Active instructor check ───────────────────────────────────────
        $this->data['activeInstructor'] = $db->row(
            "SELECT a.instructor_id,i.fullname,ic.course_type,a.status
             FROM appointments a
             JOIN instructors i ON i.instructor_id=a.instructor_id
             JOIN instructor_courses ic ON ic.course_id=a.course_id
             WHERE a.student_id=$uid AND a.status IN('pending','approved','in_progress')
             ORDER BY a.requested_at DESC LIMIT 1"
        );

        // ── Notifications ─────────────────────────────────────────────────
        $this->data['unreadCnt'] = $this->getUnreadCount();
        $this->data['notifs']    = $this->getNotifications();

        // ── Unread messages per instructor ────────────────────────────────
        $unreadMsgMapS = [];
        foreach ($db->rows("SELECT sender_id,COUNT(*) AS cnt FROM messages WHERE receiver_id=$uid AND receiver_type='student' AND is_read=0 AND sender_type='instructor' GROUP BY sender_id") as $row) {
            $unreadMsgMapS[(int)$row['sender_id']] = (int)$row['cnt'];
        }
        $this->data['unreadMsgMapS']   = $unreadMsgMapS;
        $this->data['totalUnreadMsgS'] = array_sum($unreadMsgMapS);
        $this->data['chatInstrId']     = (int)($_GET['chat_instr'] ?? 0);

        // ── My appointments ───────────────────────────────────────────────
        $this->data['myAppts'] = $db->rows(
            "SELECT a.*,ic.course_type,ic.price_per_hr,i.fullname AS instr_name,
                    tc.cert_id,tc.delivery_method AS cert_delivery,tc.cert_image AS cert_img,
                    ir.rating AS my_rating,
                    (SELECT SUM(hours_covered) FROM sessions WHERE appointment_id=a.appointment_id AND status='completed') AS hours_done
             FROM appointments a
             JOIN instructor_courses ic ON ic.course_id=a.course_id
             JOIN instructors i ON i.instructor_id=a.instructor_id
             LEFT JOIN tdc_certificates tc ON tc.appointment_id=a.appointment_id
             LEFT JOIN instructor_ratings ir ON ir.appointment_id=a.appointment_id AND ir.student_id=$uid
             WHERE a.student_id=$uid ORDER BY a.requested_at DESC"
        );

        // ── Dashboard summary stats ───────────────────────────────────────
        $this->data['totalAppts']     = $db->count("SELECT COUNT(*) AS c FROM appointments WHERE student_id=$uid");
        $this->data['activeAppts']    = $db->count("SELECT COUNT(*) AS c FROM appointments WHERE student_id=$uid AND status IN('approved','in_progress')");
        $this->data['completedAppts'] = $db->count("SELECT COUNT(*) AS c FROM appointments WHERE student_id=$uid AND status='completed'");
        $this->data['hasCert']        = $db->count("SELECT COUNT(*) AS c FROM tdc_certificates WHERE student_id=$uid");
        $this->data['avgGrade']       = $db->scalar("SELECT ROUND(AVG(grade),1) AS ag FROM sessions WHERE student_id=$uid AND grade IS NOT NULL", 'ag');

        // ── Browse: all instructors ───────────────────────────────────────
        $this->data['allInstructors'] = $db->rows(
            "SELECT i.instructor_id,i.fullname,i.email,
               COALESCE(i.mobile_number,i.mobile) AS phone,
               i.certificate_image,
               (SELECT COUNT(*) FROM instructor_courses WHERE instructor_id=i.instructor_id AND is_active=1) AS course_count,
               (SELECT GROUP_CONCAT(DISTINCT course_type ORDER BY course_type SEPARATOR '|') FROM instructor_courses WHERE instructor_id=i.instructor_id AND is_active=1) AS courses,
               (SELECT ROUND(AVG(rating),1) FROM instructor_ratings WHERE instructor_id=i.instructor_id) AS avg_rating,
               (SELECT COUNT(*) FROM instructor_ratings WHERE instructor_id=i.instructor_id) AS rating_count
             FROM instructors i
             WHERE EXISTS(SELECT 1 FROM instructor_courses ic WHERE ic.instructor_id=i.instructor_id AND ic.is_active=1)
             ORDER BY avg_rating DESC, i.fullname ASC"
        );

        // ── View single instructor ────────────────────────────────────────
        $viewInstrId = (int)($_GET['view_instr'] ?? 0);
        $this->data['viewInstrId']    = $viewInstrId;
        $this->data['viewInstr']      = null;
        $this->data['instrAllCourses']= [];
        if ($viewInstrId > 0) {
            $this->data['viewInstr'] = $db->row(
                "SELECT i.instructor_id,i.fullname,i.email,
                        COALESCE(i.mobile_number,i.mobile) AS phone,
                        i.certificate_image,i.bio,
                        (SELECT ROUND(AVG(rating),1) FROM instructor_ratings WHERE instructor_id=i.instructor_id) AS avg_rating,
                        (SELECT COUNT(*) FROM instructor_ratings WHERE instructor_id=i.instructor_id) AS rating_count
                 FROM instructors i WHERE i.instructor_id=$viewInstrId"
            );
            $this->data['instrAllCourses'] = $db->rows(
                "SELECT ic.*,
                    (SELECT GROUP_CONCAT(
                        CONCAT(cs.schedule_date,':',cs.booked_count,':',COALESCE(ic.student_limit,0))
                        ORDER BY cs.schedule_date ASC SEPARATOR '|'
                     ) FROM course_schedules cs
                     WHERE cs.course_id=ic.course_id AND cs.schedule_date >= CURDATE()
                    ) AS upcoming_slots
                 FROM instructor_courses ic
                 WHERE ic.instructor_id=$viewInstrId AND ic.is_active=1 ORDER BY ic.course_type"
            );
            $this->data['instrReviews'] = $db->rows(
                "SELECT ir.*,s.fullname AS sname FROM instructor_ratings ir
                 JOIN students s ON s.student_id=ir.student_id
                 WHERE ir.instructor_id=$viewInstrId ORDER BY ir.rated_at DESC LIMIT 5"
            );
        }

        // ── My instructors (for chat) ─────────────────────────────────────
        $this->data['myInstructors'] = $db->rows(
            "SELECT DISTINCT i.instructor_id,i.fullname,i.email,a.appointment_id,ic.course_type,a.status
             FROM appointments a JOIN instructors i ON i.instructor_id=a.instructor_id
             JOIN instructor_courses ic ON ic.course_id=a.course_id
             WHERE a.student_id=$uid ORDER BY i.fullname"
        );

        // ── My sessions ───────────────────────────────────────────────────
        $this->data['mySessions'] = $db->rows(
            "SELECT s.*,a.course_id,ic.course_type,i.fullname AS instr_name
             FROM sessions s
             JOIN appointments a ON a.appointment_id=s.appointment_id
             JOIN instructor_courses ic ON ic.course_id=a.course_id
             JOIN instructors i ON i.instructor_id=s.instructor_id
             WHERE s.student_id=$uid ORDER BY s.session_date DESC"
        );

        // ── Progress page ─────────────────────────────────────────────────
        $this->data['tdcHours'] = (float)($db->scalar(
            "SELECT COALESCE(SUM(s.hours_covered),0) AS h FROM sessions s
             JOIN appointments a ON a.appointment_id=s.appointment_id
             JOIN instructor_courses ic ON ic.course_id=a.course_id
             WHERE s.student_id=$uid AND ic.course_type IN('TDC Face-to-Face','TDC Online') AND s.status='completed'", 'h') ?? 0);
        $this->data['pdcHours'] = (float)($db->scalar(
            "SELECT COALESCE(SUM(s.hours_covered),0) AS h FROM sessions s
             JOIN appointments a ON a.appointment_id=s.appointment_id
             JOIN instructor_courses ic ON ic.course_id=a.course_id
             WHERE s.student_id=$uid AND ic.course_type LIKE 'PDC%' AND s.status='completed'", 'h') ?? 0);
        $this->data['overallGradeRow'] = $db->row(
            "SELECT tdc_overall_grade FROM appointments a
             JOIN instructor_courses ic ON ic.course_id=a.course_id
             WHERE a.student_id=$uid AND ic.course_type LIKE 'TDC%' AND a.status='completed'
               AND a.tdc_overall_grade IS NOT NULL ORDER BY a.completed_at DESC LIMIT 1"
        );

        // ── Sidebar analytics ─────────────────────────────────────────────
        $tdcHrs  = $this->data['tdcHours'];
        $totalHrs= (float)($db->scalar("SELECT COALESCE(SUM(hours_covered),0) AS h FROM sessions WHERE student_id=$uid AND status='completed'", 'h') ?? 0);
        $pdcHrs  = max(0, $totalHrs - $tdcHrs);
        $avgGrade= $this->data['avgGrade'] ?? 0;
        $this->data['sbTotalHrs']     = $totalHrs;
        $this->data['sbTDCHrs']       = $tdcHrs;
        $this->data['sbPDCHrs']       = $pdcHrs;
        $this->data['sbTDCProgress']  = min(100, round($tdcHrs / 15 * 100));
        $this->data['sbSessions']     = $db->count("SELECT COUNT(*) AS c FROM sessions WHERE student_id=$uid AND status='completed'");
        $this->data['sbCoursesDone']  = $db->count("SELECT COUNT(DISTINCT course_id) AS c FROM appointments WHERE student_id=$uid AND status='completed'");
        $this->data['sbThisMonthHrs'] = (float)($db->scalar("SELECT COALESCE(SUM(hours_covered),0) AS h FROM sessions WHERE student_id=$uid AND status='completed' AND DATE_FORMAT(session_date,'%Y-%m')=DATE_FORMAT(NOW(),'%Y-%m')", 'h') ?? 0);
        $this->data['sbAvgGrade']     = $avgGrade;
        $this->data['sbGradeColor']   = $avgGrade >= 80 ? '#3cb97a' : ($avgGrade >= 60 ? '#f59e0b' : '#e55');

        // ── Chart data ────────────────────────────────────────────────────
        $months = $this->buildMonthLabels();
        $tdcPerMonth = $pdcPerMonth = [];
        foreach ($months as $label) {
            $ym2 = date('Y-m', strtotime($label));
            $tdcPerMonth[] = (float)($db->scalar("SELECT COALESCE(SUM(s.hours_covered),0) AS h FROM sessions s JOIN appointments a ON a.appointment_id=s.appointment_id JOIN instructor_courses ic ON ic.course_id=a.course_id WHERE s.student_id=$uid AND s.status='completed' AND ic.course_type LIKE 'TDC%' AND DATE_FORMAT(s.session_date,'%Y-%m')='$ym2'", 'h') ?? 0);
            $pdcPerMonth[] = (float)($db->scalar("SELECT COALESCE(SUM(s.hours_covered),0) AS h FROM sessions s JOIN appointments a ON a.appointment_id=s.appointment_id JOIN instructor_courses ic ON ic.course_id=a.course_id WHERE s.student_id=$uid AND s.status='completed' AND ic.course_type LIKE 'PDC%' AND DATE_FORMAT(s.session_date,'%Y-%m')='$ym2'", 'h') ?? 0);
        }
        $this->data['stuMonths']      = $months;
        $this->data['stuTDCHrsMonth'] = $tdcPerMonth;
        $this->data['stuPDCHrsMonth'] = $pdcPerMonth;

        $gradeTrend = $gradeLabels = [];
        foreach ($db->rows("SELECT grade,session_date,session_number FROM sessions WHERE student_id=$uid AND grade IS NOT NULL AND status='completed' ORDER BY session_date ASC,session_id ASC LIMIT 10") as $row) {
            $gradeLabels[] = 'Day ' . $row['session_number'] . ' ' . date('M d', strtotime($row['session_date']));
            $gradeTrend[]  = (int)$row['grade'];
        }
        $this->data['stuGradeTrend']  = $gradeTrend;
        $this->data['stuGradeLabels'] = $gradeLabels;

        // ── Flash ─────────────────────────────────────────────────────────
        $this->data['flash']    = $_SESSION['flash'] ?? null;    unset($_SESSION['flash']);
        $this->data['flashErr'] = $_SESSION['flash_err'] ?? null; unset($_SESSION['flash_err']);
    }

    // ── Private action methods (ENCAPSULATION) ────────────────────────────

    private function bookAppointment(): void
    {
        $uid = $this->userId;
        // Load active instructor here because loadData() may not have run yet
        $activeInstructor = $this->db->row(
            "SELECT a.instructor_id,i.fullname FROM appointments a JOIN instructors i ON i.instructor_id=a.instructor_id
             WHERE a.student_id=$uid AND a.status IN('pending','approved','in_progress') ORDER BY a.requested_at DESC LIMIT 1"
        );
        if ($activeInstructor) {
            $this->redirect(self::SELF_FILE . '?section=my_appointments', 'You already have an active appointment with ' . $activeInstructor['fullname'] . '.', true);
        }

        $instrId   = (int)($_POST['instructor_id'] ?? 0);
        $courseId  = (int)($_POST['course_id'] ?? 0);
        $startDate = $this->db->escape($_POST['start_date'] ?? '');
        $note      = $this->db->escape($_POST['student_note'] ?? '');
        $cRow      = $this->db->row("SELECT * FROM instructor_courses WHERE course_id=$courseId AND instructor_id=$instrId AND is_active=1");

        if (!$cRow) { $this->redirect(self::SELF_FILE . "?section=view_instr&view_instr=$instrId", 'Invalid course selected.', true); }

        $isPDC = stripos($cRow['course_type'], 'PDC') === 0;
        $tdcDone = $this->db->count("SELECT COUNT(*) AS c FROM appointments a JOIN instructor_courses ic ON ic.course_id=a.course_id WHERE a.student_id=$uid AND ic.course_type IN('TDC Face-to-Face','TDC Online') AND a.status='completed'");
        if ($isPDC && !$tdcDone) { $this->redirect(self::SELF_FILE . "?section=view_instr&view_instr=$instrId", 'You must complete TDC before booking PDC.', true); }

        $isTDC = stripos($cRow['course_type'], 'TDC') === 0;
        if ($isTDC) {
            $day = date('N', strtotime($startDate));
            if ($day < 6) { $this->redirect(self::SELF_FILE . "?section=view_instr&view_instr=$instrId", 'TDC start date must be a Saturday or Sunday.', true); }
        }

        // ── Schedule conflict check ────────────────────────────────────────
        // Prevent the same student from booking the same instructor on the same date
        $conflict = $this->db->count(
            "SELECT COUNT(*) AS c FROM appointments
             WHERE instructor_id=$instrId AND student_id=$uid
               AND start_date='$startDate'
               AND status IN('pending','approved','in_progress')"
        );
        if ($conflict > 0) {
            $this->redirect(self::SELF_FILE . "?section=view_instr&view_instr=$instrId",
                'You already have a booking with this instructor on that date.', true);
        }

        // ── Student slot limit check ──────────────────────────────────────
        // Check how many students are already booked for this course on this date
        if (!empty($cRow['student_limit'])) {
            $slotLimit = (int)$cRow['student_limit'];
            $booked = $this->db->count(
                "SELECT COUNT(*) AS c FROM appointments
                 WHERE course_id=$courseId AND start_date='$startDate'
                   AND status IN('pending','approved','in_progress')"
            );
            if ($booked >= $slotLimit) {
                $this->redirect(self::SELF_FILE . "?section=view_instr&view_instr=$instrId",
                    "Sorry, this course is fully booked on $startDate ($slotLimit/$slotLimit slots taken). Please choose a different date.", true);
            }
        }

        $hrs   = $isTDC ? 15 : (int)($_POST['total_hours'] ?? 8);
        $price = round($cRow['price_per_hr'] * $hrs, 2);
        $this->db->query("INSERT INTO appointments (student_id,instructor_id,course_id,start_date,total_hours,total_price,student_note) VALUES ($uid,$instrId,$courseId,'$startDate',$hrs,$price,'$note')");

        // ── Update course_schedules slot counter ──────────────────────────
        $slotLimit = !empty($cRow['student_limit']) ? (int)$cRow['student_limit'] : 'NULL';
        $this->db->query(
            "INSERT INTO course_schedules (course_id,instructor_id,schedule_date,booked_count,student_limit)
             VALUES ($courseId,$instrId,'$startDate',1,$slotLimit)
             ON DUPLICATE KEY UPDATE booked_count=booked_count+1"
        );

        $sname = $this->db->escape($_SESSION['student_name'] ?? 'A student');
        $this->notify($instrId, 'instructor', "New appointment request for {$cRow['course_type']} on $startDate from $sname.", 'booking_request');
        $this->redirect(self::SELF_FILE . '?section=my_appointments', 'Appointment request sent!');
    }

    private function cancelAppointment(): void
    {
        $uid    = $this->userId;
        $apptId = (int)($_POST['appointment_id'] ?? 0);

        // Fetch the appointment first — needed for slot release + notification
        $appt = $this->db->row(
            "SELECT a.*, ic.course_type
             FROM appointments a
             JOIN instructor_courses ic ON ic.course_id = a.course_id
             WHERE a.appointment_id=$apptId AND a.student_id=$uid
               AND a.status IN('pending','approved','in_progress')"
        );

        if (!$appt) {
            $this->redirect(self::SELF_FILE . '?section=my_appointments', 'Appointment cannot be cancelled.', true);
        }

        $wasActive = in_array($appt['status'], ['approved', 'in_progress']);

        $this->db->query(
            "UPDATE appointments SET status='cancelled' WHERE appointment_id=$apptId AND student_id=$uid"
        );

        // If it was an approved/in_progress booking, free up the schedule slot
        // and let the instructor know.
        if ($wasActive) {
            $courseId = (int)$appt['course_id'];
            $instrId  = (int)$appt['instructor_id'];
            $date     = $appt['start_date'];

            $this->db->query(
                "UPDATE course_schedules SET booked_count=GREATEST(0,booked_count-1)
                 WHERE course_id=$courseId AND schedule_date='$date'"
            );

            $sname = $this->db->escape($_SESSION['student_name'] ?? 'A student');
            $this->notify(
                $instrId, 'instructor',
                "$sname cancelled their {$appt['course_type']} appointment that was scheduled for $date.",
                'student_cancelled'
            );

            $this->redirect(self::SELF_FILE . '?section=my_appointments', 'Appointment cancelled. Your instructor has been notified.');
        }

        $this->redirect(self::SELF_FILE . '?section=my_appointments', 'Appointment cancelled.');
    }

    private function rateInstructor(): void
    {
        $uid    = $this->userId;
        $apptId = (int)($_POST['appointment_id'] ?? 0);
        $rating = max(1, min(5, (int)($_POST['rating'] ?? 5)));
        $comment= $this->db->escape($_POST['comment'] ?? '');
        $aRow   = $this->db->row("SELECT * FROM appointments WHERE appointment_id=$apptId AND student_id=$uid AND status='completed'");
        if ($aRow) {
            $this->db->query("INSERT IGNORE INTO instructor_ratings (appointment_id,student_id,instructor_id,rating,comment) VALUES ($apptId,$uid,{$aRow['instructor_id']},$rating,'$comment')");
        }
        $this->redirect(self::SELF_FILE . '?section=my_appointments');
    }

    private function markReadAndRedirect(): void
    {
        $this->markNotificationsRead();
        $this->redirect(self::SELF_FILE . '?section=messages');
    }

    private function sendMessage(): void
    {
        $uid    = $this->userId;
        $toId   = (int)($_POST['to_instructor_id'] ?? 0);
        $body   = $this->db->escape(trim($_POST['body'] ?? ''));
        $apptId = (int)($_POST['appointment_id'] ?? 0);
        $ok     = $this->db->count("SELECT COUNT(*) AS c FROM appointments WHERE student_id=$uid AND instructor_id=$toId AND status IN('pending','approved','in_progress','completed')");
        if ($ok > 0 && $body !== '') {
            $apptCol = $apptId > 0 ? $apptId : 'NULL';
            $this->db->query("INSERT INTO messages (sender_id,sender_type,receiver_id,receiver_type,appointment_id,body) VALUES ($uid,'student',$toId,'instructor',$apptCol,'$body')");
            $this->notify($toId, 'instructor', '💬 Message from your student.', 'message');
        }
        $this->redirect(self::SELF_FILE . "?section=chat&chat_instr=$toId");
    }

    private function updateProfile(): void
    {
        $uid     = $this->userId;
        $name    = $this->db->escape($_POST['fullname'] ?? '');
        $email   = $this->db->escape($_POST['email'] ?? '');
        $mobile  = $this->db->escape($_POST['mobile'] ?? '');
        $address = $this->db->escape($_POST['address'] ?? '');
        $gender  = in_array($_POST['gender'] ?? '', ['Male','Female','Other']) ? $_POST['gender'] : '';
        $fbLink  = $this->db->escape($_POST['fb_link'] ?? '');
        $idPhotoSQL = '';
        if (isset($_FILES['id_photo']) && $_FILES['id_photo']['error'] === 0) {
            $ext = strtolower(pathinfo($_FILES['id_photo']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','gif','webp'])) {
                $path = '../assets/uploads/student_ids/';
                if (!is_dir($path)) mkdir($path, 0755, true);
                $fname = 'sid_' . $uid . '_' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['id_photo']['tmp_name'], $path . $fname)) $idPhotoSQL = ",id_photo='$fname'";
            }
        }
        $this->db->query("UPDATE students SET fullname='$name',email='$email',mobile='$mobile',address='$address',gender='$gender',fb_link='$fbLink'$idPhotoSQL WHERE student_id=$uid");
        $_SESSION['student_name'] = $_POST['fullname'] ?? '';
        $this->redirect(self::SELF_FILE . '?section=profile', 'Profile updated.');
    }
}
