<?php
require_once __DIR__ . '/BaseDashboard.php';
require_once __DIR__ . '/Notifiable.php';

/**
 * AdminDashboard.php
 *
 * OOP PILLARS DEMONSTRATED:
 *
 * 1. INHERITANCE   — extends BaseDashboard
 *    Gets $this->db, $this->userId, redirect(), notify(),
 *    buildMonthLabels(), etc. for free.
 *
 * 2. ABSTRACTION   — implements the two abstract methods required by BaseDashboard:
 *    handlePost()  and  loadData()
 *    The parent class guarantees these exist without knowing the details.
 *
 * 3. POLYMORPHISM  — implements Notifiable interface
 *    Any code that receives a Notifiable object can call
 *    getUnreadCount() / markNotificationsRead() / getNotifications()
 *    without knowing it's talking to an Admin specifically.
 *
 * 4. ENCAPSULATION — all admin-specific logic and state is private/protected.
 *    Raw SQL, redirect targets, and action names are hidden inside this class.
 */
class AdminDashboard extends BaseDashboard implements Notifiable
{
    // ── Private constants: encapsulate magic strings ──────────────────────
    private const SELF_FILE   = 'admin_dashboard.php';
    private const USER_TYPE   = 'admin';

    // ── Constructor ───────────────────────────────────────────────────────

    public function __construct(Database $db, int $adminId)
    {
        parent::__construct($db, $adminId); // call BaseDashboard constructor
    }

    // ── Notifiable interface (POLYMORPHISM) ───────────────────────────────

    public function getUnreadCount(): int
    {
        return $this->db->count(
            "SELECT COUNT(*) AS c FROM notifications
             WHERE user_id={$this->userId} AND user_type='admin' AND is_read=0"
        );
    }

    public function markNotificationsRead(): void
    {
        $this->db->query(
            "UPDATE notifications SET is_read=1
             WHERE user_id={$this->userId} AND user_type='admin'"
        );
    }

    public function getNotifications(int $limit = 30): array
    {
        return $this->db->rows(
            "SELECT * FROM notifications
             WHERE user_id={$this->userId} AND user_type='admin'
             ORDER BY created_at DESC LIMIT $limit"
        );
    }

    // ── Abstract method: handlePost (ABSTRACTION fulfilled) ───────────────

    public function handlePost(string $action): void
    {
        match ($action) {
            'add_instructor'      => $this->addInstructor(),
            'delete_instructor'   => $this->deleteInstructor(),
            'delete_student'      => $this->deleteStudent(),
            'delete_appointment'  => $this->deleteAppointment(),
            'force_complete'      => $this->forceComplete(),
            'announce'            => $this->sendAnnouncement(),
            'mark_admin_read'     => $this->markAdminRead(),
            'assign_student'      => $this->assignStudent(),
            'reassign_appointment'=> $this->reassignAppointment(),
            default               => null,
        };
    }

    // ── Abstract method: loadData (ABSTRACTION fulfilled) ─────────────────

    public function loadData(): void
    {
        $db = $this->db;
        $t  = $this->today;
        $ym = date('Y-m');

        // ── Core stats ────────────────────────────────────────────────────
        $this->data['totalStudents']    = $db->count("SELECT COUNT(*) AS c FROM students");
        $this->data['totalInstructors'] = $db->count("SELECT COUNT(*) AS c FROM instructors");
        $this->data['pendingAppts']     = $db->count("SELECT COUNT(*) AS c FROM appointments WHERE status='pending'");
        $this->data['completedAppts']   = $db->count("SELECT COUNT(*) AS c FROM appointments WHERE status='completed'");
        $this->data['certsIssued']      = $db->count("SELECT COUNT(*) AS c FROM tdc_certificates");
        $this->data['pdcCertsIssued']   = $db->count("SELECT COUNT(*) AS c FROM pdc_certificates");
        $this->data['totalSessions']    = $db->count("SELECT COUNT(*) AS c FROM sessions WHERE status='completed'");
        $this->data['avgRating']        = $db->scalar("SELECT ROUND(AVG(rating),1) AS r FROM instructor_ratings", 'r');
        $this->data['totalHoursTaught'] = (float)($db->scalar("SELECT COALESCE(SUM(hours_covered),0) AS h FROM sessions WHERE status='completed'", 'h') ?? 0);

        // ── Analytics ─────────────────────────────────────────────────────
        $this->data['todayLogins'] = $db->count(
            "SELECT (SELECT COUNT(*) FROM student_login_history WHERE DATE(login_datetime)='$t' AND status='success')
                  + (SELECT COUNT(*) FROM instructor_login_history WHERE DATE(login_time)='$t' AND login_status='success')
                  + (SELECT COUNT(*) FROM admin_login_history WHERE DATE(login_time)='$t' AND login_status='success') AS c"
        );
        $this->data['monthLogins'] = $db->count(
            "SELECT (SELECT COUNT(*) FROM student_login_history WHERE YEAR(login_datetime)=YEAR(NOW()) AND MONTH(login_datetime)=MONTH(NOW()) AND status='success')
                  + (SELECT COUNT(*) FROM instructor_login_history WHERE YEAR(login_time)=YEAR(NOW()) AND MONTH(login_time)=MONTH(NOW()) AND login_status='success') AS c"
        );
        $this->data['newStudentsMonth'] = $db->count(
            "SELECT COUNT(*) AS c FROM students WHERE YEAR(created_at)=YEAR(NOW()) AND MONTH(created_at)=MONTH(NOW())"
        );

        // ── Rankings ──────────────────────────────────────────────────────
        $this->data['topInstructors'] = $db->rows(
            "SELECT i.instructor_id,i.fullname,ROUND(AVG(ir.rating),1) AS avg_r,COUNT(ir.rating_id) AS cnt,
                    (SELECT COUNT(*) FROM appointments WHERE instructor_id=i.instructor_id AND status='completed') AS done
             FROM instructor_ratings ir
             JOIN instructors i ON i.instructor_id=ir.instructor_id
             GROUP BY i.instructor_id ORDER BY avg_r DESC,cnt DESC LIMIT 5"
        );
        $this->data['lowInstructors'] = $db->rows(
            "SELECT i.instructor_id,i.fullname,ROUND(AVG(ir.rating),1) AS avg_r,COUNT(ir.rating_id) AS cnt
             FROM instructor_ratings ir
             JOIN instructors i ON i.instructor_id=ir.instructor_id
             GROUP BY i.instructor_id HAVING cnt>=1 ORDER BY avg_r ASC,cnt DESC LIMIT 5"
        );
        $this->data['topStudents'] = $db->rows(
            "SELECT s.student_id,s.fullname,ROUND(AVG(sess.grade),1) AS avg_grade,COUNT(sess.session_id) AS sess_cnt
             FROM sessions sess JOIN students s ON s.student_id=sess.student_id
             WHERE sess.grade IS NOT NULL AND DATE_FORMAT(sess.session_date,'%Y-%m')='$ym'
             GROUP BY s.student_id ORDER BY avg_grade DESC LIMIT 5"
        );
        $this->data['lowStudents'] = $db->rows(
            "SELECT s.student_id,s.fullname,ROUND(AVG(sess.grade),1) AS avg_grade,COUNT(sess.session_id) AS sess_cnt
             FROM sessions sess JOIN students s ON s.student_id=sess.student_id
             WHERE sess.grade IS NOT NULL AND DATE_FORMAT(sess.session_date,'%Y-%m')='$ym'
             GROUP BY s.student_id ORDER BY avg_grade ASC LIMIT 5"
        );

        // ── Chart data ────────────────────────────────────────────────────
        $months = $this->buildMonthLabels();
        $newStudents = $apptCompleted = $apptPending = $apptCancelled = [];
        foreach ($months as $label) {
            $ym2 = date('Y-m', strtotime($label));
            $newStudents[]   = $db->count("SELECT COUNT(*) AS c FROM students WHERE DATE_FORMAT(created_at,'%Y-%m')='$ym2'");
            $apptCompleted[] = $db->count("SELECT COUNT(*) AS c FROM appointments WHERE status='completed' AND DATE_FORMAT(requested_at,'%Y-%m')='$ym2'");
            $apptPending[]   = $db->count("SELECT COUNT(*) AS c FROM appointments WHERE status IN('pending','approved','in_progress') AND DATE_FORMAT(requested_at,'%Y-%m')='$ym2'");
            $apptCancelled[] = $db->count("SELECT COUNT(*) AS c FROM appointments WHERE status IN('cancelled','rejected') AND DATE_FORMAT(requested_at,'%Y-%m')='$ym2'");
        }
        $this->data['chartMonths']        = $months;
        $this->data['chartNewStudents']   = $newStudents;
        $this->data['chartApptCompleted'] = $apptCompleted;
        $this->data['chartApptPending']   = $apptPending;
        $this->data['chartApptCancelled'] = $apptCancelled;

        // ── Course completion counts (donut chart) ────────────────────────
        $this->data['tdcDoneCount']   = $db->count("SELECT COUNT(*) AS c FROM appointments a JOIN instructor_courses ic ON ic.course_id=a.course_id WHERE a.status='completed' AND ic.course_type LIKE 'TDC%'");
        $this->data['pdcDoneCount']   = $db->count("SELECT COUNT(*) AS c FROM appointments a JOIN instructor_courses ic ON ic.course_id=a.course_id WHERE a.status='completed' AND ic.course_type LIKE 'PDC%'");
        $this->data['tdcActiveCount'] = $db->count("SELECT COUNT(*) AS c FROM appointments a JOIN instructor_courses ic ON ic.course_id=a.course_id WHERE a.status IN('approved','in_progress') AND ic.course_type LIKE 'TDC%'");
        $this->data['pdcActiveCount'] = $db->count("SELECT COUNT(*) AS c FROM appointments a JOIN instructor_courses ic ON ic.course_id=a.course_id WHERE a.status IN('approved','in_progress') AND ic.course_type LIKE 'PDC%'");

        // ── Grade distribution ────────────────────────────────────────────
        $buckets = ['90-100' => 0, '80-89' => 0, '70-79' => 0, '60-69' => 0, '<60' => 0];
        foreach ($db->rows("SELECT grade FROM sessions WHERE grade IS NOT NULL AND status='completed'") as $row) {
            $g = (int)$row['grade'];
            if ($g >= 90)      $buckets['90-100']++;
            elseif ($g >= 80)  $buckets['80-89']++;
            elseif ($g >= 70)  $buckets['70-79']++;
            elseif ($g >= 60)  $buckets['60-69']++;
            else               $buckets['<60']++;
        }
        $this->data['gradeBuckets'] = $buckets;

        // ── Gender distribution ───────────────────────────────────────────
        $total = $this->data['totalStudents'];
        $male  = $db->count("SELECT COUNT(*) AS c FROM students WHERE gender='Male'");
        $female= $db->count("SELECT COUNT(*) AS c FROM students WHERE gender='Female'");
        $this->data['maleCount']        = $male;
        $this->data['femaleCount']      = $female;
        $this->data['otherGenderCount'] = max(0, $total - $male - $female);

        // ── Sidebar live stats ────────────────────────────────────────────
        $this->data['sbActiveNow']     = $db->count("SELECT COUNT(*) AS c FROM appointments WHERE status IN('approved','in_progress')");
        $this->data['sbUnverified']    = $db->count("SELECT COUNT(*) AS c FROM students WHERE is_verified=0");
        $this->data['sbSessionsToday'] = $db->count("SELECT COUNT(*) AS c FROM sessions WHERE session_date='$t'");

        // ── Students list ─────────────────────────────────────────────────
        $this->data['students'] = $db->rows(
            "SELECT s.*,
               (SELECT COUNT(*) FROM appointments a JOIN instructor_courses ic ON ic.course_id=a.course_id
                WHERE a.student_id=s.student_id AND ic.course_type IN('TDC Face-to-Face','TDC Online') AND a.status='completed') AS tdc_done,
               (SELECT COUNT(*) FROM appointments a JOIN instructor_courses ic ON ic.course_id=a.course_id
                WHERE a.student_id=s.student_id AND ic.course_type LIKE 'PDC%' AND a.status='completed') AS pdc_done,
               (SELECT COUNT(*) FROM tdc_certificates WHERE student_id=s.student_id) AS has_cert
             FROM students s ORDER BY s.created_at DESC"
        );

        // ── Instructors list ──────────────────────────────────────────────
        $this->data['instructors'] = $db->rows(
            "SELECT i.*,
               (SELECT COUNT(*) FROM instructor_courses WHERE instructor_id=i.instructor_id AND is_active=1) AS course_cnt,
               (SELECT ROUND(AVG(rating),1) FROM instructor_ratings WHERE instructor_id=i.instructor_id) AS avg_r,
               (SELECT COUNT(*) FROM appointments WHERE instructor_id=i.instructor_id) AS appt_cnt
             FROM instructors i ORDER BY i.fullname ASC"
        );

        // ── Appointments list ─────────────────────────────────────────────
        $this->data['appointments'] = $db->rows(
            "SELECT a.*,s.fullname AS sname,i.fullname AS iname,ic.course_type,
                    (SELECT SUM(hours_covered) FROM sessions WHERE appointment_id=a.appointment_id AND status='completed') AS hdone
             FROM appointments a
             JOIN students s ON s.student_id=a.student_id
             JOIN instructors i ON i.instructor_id=a.instructor_id
             JOIN instructor_courses ic ON ic.course_id=a.course_id
             ORDER BY a.requested_at DESC"
        );

        // ── Certificates ──────────────────────────────────────────────────
        $this->data['certificates'] = $db->rows(
            "SELECT tc.*,s.fullname AS sname,i.fullname AS iname
             FROM tdc_certificates tc
             JOIN students s ON s.student_id=tc.student_id
             JOIN instructors i ON i.instructor_id=tc.issued_by
             ORDER BY tc.issued_at DESC"
        );

        // ── Recent student logins ─────────────────────────────────────────
        $this->data['recentLogins'] = $db->rows(
            "SELECT slh.*,s.fullname FROM student_login_history slh
             JOIN students s ON s.student_id=slh.student_id
             ORDER BY slh.login_datetime DESC LIMIT 10"
        );

        // ── Failed login counts per student ───────────────────────────────
        $sFailMap = [];
        foreach ($db->rows("SELECT student_id,COUNT(*) AS fc FROM student_login_history WHERE status='failed' GROUP BY student_id") as $row) {
            $sFailMap[(int)$row['student_id']] = (int)$row['fc'];
        }
        $this->data['sFailMap'] = $sFailMap;

        // ── Announcement recipients ───────────────────────────────────────
        $this->data['stuRecipCount']     = $db->count("SELECT COUNT(*) AS c FROM students WHERE is_verified=1");
        $this->data['insRecipCount']     = $db->count("SELECT COUNT(*) AS c FROM instructors");
        $this->data['allStudentsList']   = $db->rows("SELECT student_id,fullname FROM students WHERE is_verified=1 ORDER BY fullname");
        $this->data['allInstructorsList']= $db->rows("SELECT instructor_id,fullname FROM instructors ORDER BY fullname");

        // ── Recent announcements ──────────────────────────────────────────
        $this->data['recentAnnouncements'] = $db->rows(
            "SELECT n.*,
               CASE WHEN n.user_type='student'    THEN (SELECT fullname FROM students    WHERE student_id=n.user_id)
                    WHEN n.user_type='instructor'  THEN (SELECT fullname FROM instructors WHERE instructor_id=n.user_id)
               END AS recip
             FROM notifications n WHERE n.type='announcement'
             ORDER BY n.created_at DESC LIMIT 50"
        );

        // ── Course breakdown (reports) ────────────────────────────────────
        $this->data['courseReport'] = $db->rows(
            "SELECT ic.course_type,COUNT(*) AS total,
                    SUM(a.status='completed') AS done,
                    SUM(a.status IN('approved','in_progress')) AS active,
                    SUM(a.status='pending') AS pending
             FROM appointments a JOIN instructor_courses ic ON ic.course_id=a.course_id
             GROUP BY ic.course_type ORDER BY ic.course_type"
        );

        // ── Flash messages ────────────────────────────────────────────────
        $this->data['flash']    = $_SESSION['flash'] ?? null;    unset($_SESSION['flash']);
        $this->data['flashErr'] = $_SESSION['flash_err'] ?? null; unset($_SESSION['flash_err']);
    }

    // ── Private action methods (ENCAPSULATION) ────────────────────────────

    private function addInstructor(): void
    {
        $name  = $this->db->escape($_POST['fullname'] ?? '');
        $email = $this->db->escape($_POST['email'] ?? '');
        $pass  = password_hash($_POST['password'] ?? '', PASSWORD_BCRYPT);
        $this->db->query("INSERT INTO instructors (fullname,email,password) VALUES ('$name','$email','$pass')");
        $this->redirect(self::SELF_FILE . '?section=instructors', 'Instructor added.');
    }

    private function deleteInstructor(): void
    {
        $id = (int)($_POST['instructor_id'] ?? 0);
        $this->db->query("DELETE FROM instructors WHERE instructor_id=$id");
        $this->redirect(self::SELF_FILE . '?section=instructors', 'Instructor deleted.');
    }

    private function deleteStudent(): void
    {
        $id = (int)($_POST['student_id'] ?? 0);
        $this->db->query("DELETE FROM students WHERE student_id=$id");
        $this->redirect(self::SELF_FILE . '?section=students', 'Student deleted.');
    }

    private function deleteAppointment(): void
    {
        $id = (int)($_POST['appointment_id'] ?? 0);
        $this->db->query("DELETE FROM appointments WHERE appointment_id=$id");
        $this->redirect(self::SELF_FILE . '?section=appointments', 'Appointment deleted.');
    }

    private function forceComplete(): void
    {
        $id   = (int)($_POST['appointment_id'] ?? 0);
        $aRow = $this->db->row("SELECT * FROM appointments WHERE appointment_id=$id");
        if ($aRow) {
            $this->db->query("UPDATE appointments SET status='completed',completed_at=NOW() WHERE appointment_id=$id");
            $this->notify($aRow['student_id'], 'student', 'Your appointment has been marked complete by admin.', 'admin_action');
        }
        $this->redirect(self::SELF_FILE . '?section=appointments');
    }

    private function sendAnnouncement(): void
    {
        $msg      = $this->db->escape($_POST['message'] ?? '');
        $sendTo   = $_POST['send_to'] ?? 'all_students';
        $valid    = ['all_students','all_instructors','everyone','specific_student','specific_instructor'];
        if (!in_array($sendTo, $valid)) $sendTo = 'all_students';
        $specificId = (int)($_POST['specific_id'] ?? 0);
        $count      = 0;

        if (in_array($sendTo, ['all_students','everyone'])) {
            foreach ($this->db->rows("SELECT student_id FROM students WHERE is_verified=1") as $s) {
                $this->db->query("INSERT INTO notifications (user_id,user_type,message,type,recipient_target) VALUES ({$s['student_id']},'student','📢 Admin: $msg','announcement','$sendTo')");
                $count++;
            }
        } elseif ($sendTo === 'specific_student' && $specificId > 0) {
            $this->db->query("INSERT INTO notifications (user_id,user_type,message,type,recipient_target) VALUES ($specificId,'student','📢 Admin: $msg','announcement','specific_student')");
            $count = 1;
        }

        if (in_array($sendTo, ['all_instructors','everyone'])) {
            foreach ($this->db->rows("SELECT instructor_id FROM instructors") as $i) {
                $this->db->query("INSERT INTO notifications (user_id,user_type,message,type,recipient_target) VALUES ({$i['instructor_id']},'instructor','📢 Admin: $msg','announcement','$sendTo')");
                $count++;
            }
        } elseif ($sendTo === 'specific_instructor' && $specificId > 0) {
            $this->db->query("INSERT INTO notifications (user_id,user_type,message,type,recipient_target) VALUES ($specificId,'instructor','📢 Admin: $msg','announcement','specific_instructor')");
            $count = 1;
        }

        $this->redirect(self::SELF_FILE . '?section=communication', "Announcement sent to {$count} recipient(s).");
    }

    private function assignStudent(): void
    {
        $studentId   = (int)($_POST['student_id']   ?? 0);
        $instructorId= (int)($_POST['instructor_id'] ?? 0);
        $courseId    = (int)($_POST['course_id']     ?? 0);
        $startDate   = $this->db->escape($_POST['start_date'] ?? '');
        $note        = $this->db->escape($_POST['admin_note'] ?? '');

        if (!$studentId || !$instructorId || !$courseId || !$startDate) {
            $this->redirect(self::SELF_FILE . '?section=workload', 'Missing required fields.', true);
        }

        // Get course details
        $course = $this->db->row("SELECT * FROM instructor_courses WHERE course_id=$courseId AND instructor_id=$instructorId AND is_active=1");
        if (!$course) {
            $this->redirect(self::SELF_FILE . '?section=workload', 'Course not found or inactive.', true);
        }

        // Check student not already booked with this instructor on this date
        $conflict = $this->db->count("SELECT COUNT(*) AS c FROM appointments
            WHERE instructor_id=$instructorId AND student_id=$studentId
              AND start_date='$startDate' AND status IN('pending','approved','in_progress')");
        if ($conflict > 0) {
            $this->redirect(self::SELF_FILE . '?section=workload', 'This student already has a booking with this instructor on that date.', true);
        }

        // Check slot limit
        if (!empty($course['student_limit'])) {
            $slotLimit = (int)$course['student_limit'];
            $booked    = $this->db->count("SELECT COUNT(*) AS c FROM appointments
                WHERE course_id=$courseId AND start_date='$startDate'
                  AND status IN('pending','approved','in_progress')");
            if ($booked >= $slotLimit) {
                $this->redirect(self::SELF_FILE . '?section=workload',
                    "Date $startDate is fully booked ($slotLimit/$slotLimit). Choose a different date.", true);
            }
        }

        $isTDC = stripos($course['course_type'], 'TDC') === 0;
        $hrs   = $isTDC ? 15 : (int)($_POST['total_hours'] ?? 8);
        $price = round($course['price_per_hr'] * $hrs, 2);

        // Insert appointment as already approved (admin is assigning directly)
        $adminNote = $note ? "Admin assigned: $note" : 'Assigned by admin.';
        $this->db->query("INSERT INTO appointments (student_id,instructor_id,course_id,start_date,total_hours,total_price,status,student_note,reviewed_at)
            VALUES ($studentId,$instructorId,$courseId,'$startDate',$hrs,$price,'approved','$adminNote',NOW())");

        // Update schedule slot counter
        $slotLimit = !empty($course['student_limit']) ? (int)$course['student_limit'] : 'NULL';
        $this->db->query("INSERT INTO course_schedules (course_id,instructor_id,schedule_date,booked_count,student_limit)
            VALUES ($courseId,$instructorId,'$startDate',1,$slotLimit)
            ON DUPLICATE KEY UPDATE booked_count=booked_count+1");

        // Notify both parties
        $student    = $this->db->row("SELECT fullname FROM students WHERE student_id=$studentId");
        $sname      = $student ? $this->db->escape($student['fullname']) : 'A student';
        $instructor = $this->db->row("SELECT fullname FROM instructors WHERE instructor_id=$instructorId");
        $iname      = $instructor ? $this->db->escape($instructor['fullname']) : 'An instructor';

        $this->notify($studentId,    'student',     "Admin has assigned you to {$iname} for {$course['course_type']} starting $startDate. The appointment is already approved.", 'admin_assign');
        $this->notify($instructorId, 'instructor',  "Admin has assigned student {$sname} to your {$course['course_type']} course starting $startDate.", 'admin_assign');

        $this->redirect(self::SELF_FILE . '?section=workload', "Student assigned to {$iname} successfully. Appointment is pre-approved.");
    }

    private function reassignAppointment(): void
    {
        $apptId      = (int)($_POST['appointment_id'] ?? 0);
        $oldInstrId  = (int)($_POST['old_instructor_id'] ?? 0);
        $newInstrId  = (int)($_POST['ra_instr_sel'] ?? $_POST['new_instructor_id'] ?? 0);
        $newCourseId = (int)($_POST['course_id'] ?? 0);
        $startDate   = $this->db->escape($_POST['start_date'] ?? '');
        $hoursRaw    = $_POST['total_hours'] ?? '';

        // The new instructor select on the form doesn't carry a 'name' attr in this build,
        // so derive it from the new course's owning instructor instead.
        if (!$apptId || !$newCourseId || !$startDate) {
            $this->redirect(self::SELF_FILE . '?section=workload', 'Missing required fields for reassignment.', true);
        }

        // Current appointment + its course type
        $appt = $this->db->row(
            "SELECT a.*, ic.course_type AS old_course_type
             FROM appointments a
             JOIN instructor_courses ic ON ic.course_id = a.course_id
             WHERE a.appointment_id = $apptId"
        );
        if (!$appt || !in_array($appt['status'], ['pending', 'approved', 'in_progress'])) {
            $this->redirect(self::SELF_FILE . '?section=workload', 'Appointment not found or already finalized.', true);
        }

        // New course must be active and belong to the instructor derived from it
        $newCourse = $this->db->row(
            "SELECT * FROM instructor_courses WHERE course_id = $newCourseId AND is_active = 1"
        );
        if (!$newCourse) {
            $this->redirect(self::SELF_FILE . '?section=workload', 'Selected course not found or inactive.', true);
        }
        $newInstrId = (int)$newCourse['instructor_id'];

        // Course type must match (admin reassigns to the SAME course type on a new instructor)
        if ($newCourse['course_type'] !== $appt['old_course_type']) {
            $this->redirect(self::SELF_FILE . '?section=workload', 'New course type must match the original (' . $appt['old_course_type'] . ').', true);
        }

        $isTDC = stripos($newCourse['course_type'], 'TDC') === 0;
        $hours = $isTDC ? 15 : (int)($hoursRaw !== '' ? $hoursRaw : $appt['total_hours']);

        if ($isTDC) {
            $day = date('N', strtotime($startDate));
            if ($day < 6) {
                $this->redirect(self::SELF_FILE . '?section=workload', 'TDC start date must be a Saturday or Sunday.', true);
            }
        }

        $studentId = (int)$appt['student_id'];

        // No-op guard
        if ((int)$appt['instructor_id'] === $newInstrId && (int)$appt['course_id'] === $newCourseId && $appt['start_date'] === $startDate) {
            $this->redirect(self::SELF_FILE . '?section=workload', 'No changes made — same instructor, course, and date.', true);
        }

        // Conflict: student already booked with the new instructor on that date
        $conflict = $this->db->count(
            "SELECT COUNT(*) AS c FROM appointments
             WHERE instructor_id=$newInstrId AND student_id=$studentId
               AND start_date='$startDate'
               AND status IN('pending','approved','in_progress')
               AND appointment_id <> $apptId"
        );
        if ($conflict > 0) {
            $this->redirect(self::SELF_FILE . '?section=workload', 'This student already has a booking with that instructor on that date.', true);
        }

        // Slot limit check on new course/date
        if (!empty($newCourse['student_limit'])) {
            $slotLimit = (int)$newCourse['student_limit'];
            $booked = $this->db->count(
                "SELECT COUNT(*) AS c FROM appointments
                 WHERE course_id=$newCourseId AND start_date='$startDate'
                   AND status IN('pending','approved','in_progress')
                   AND appointment_id <> $apptId"
            );
            if ($booked >= $slotLimit) {
                $this->redirect(self::SELF_FILE . '?section=workload',
                    "New date is fully booked ($slotLimit/$slotLimit). Choose a different date.", true);
            }
        }

        $price = round($newCourse['price_per_hr'] * $hours, 2);

        // Decrement old schedule slot
        $oldCourseId = (int)$appt['course_id'];
        $oldDate     = $appt['start_date'];
        $this->db->query("UPDATE course_schedules SET booked_count=GREATEST(0,booked_count-1) WHERE course_id=$oldCourseId AND schedule_date='$oldDate'");

        // Move the appointment
        $this->db->query(
            "UPDATE appointments
             SET instructor_id=$newInstrId, course_id=$newCourseId, start_date='$startDate',
                 total_hours=$hours, total_price=$price
             WHERE appointment_id=$apptId"
        );

        // Increment new schedule slot
        $slotLimit = !empty($newCourse['student_limit']) ? (int)$newCourse['student_limit'] : 'NULL';
        $this->db->query(
            "INSERT INTO course_schedules (course_id,instructor_id,schedule_date,booked_count,student_limit)
             VALUES ($newCourseId,$newInstrId,'$startDate',1,$slotLimit)
             ON DUPLICATE KEY UPDATE booked_count=booked_count+1"
        );

        // Notify everyone involved
        $oldInstr = $this->db->row("SELECT fullname FROM instructors WHERE instructor_id=$oldInstrId");
        $newInstr = $this->db->row("SELECT fullname FROM instructors WHERE instructor_id=$newInstrId");
        $student  = $this->db->row("SELECT fullname FROM students WHERE student_id=$studentId");
        $oldName  = $oldInstr ? $this->db->escape($oldInstr['fullname']) : 'an instructor';
        $newName  = $newInstr ? $this->db->escape($newInstr['fullname']) : 'an instructor';
        $sName    = $student  ? $this->db->escape($student['fullname'])  : 'A student';
        $ctype    = $newCourse['course_type'];

        $this->notify($oldInstrId, 'instructor', "Admin reassigned student $sName's $ctype appointment to another instructor.", 'admin_reassign');
        $this->notify($newInstrId, 'instructor', "Admin assigned student $sName to your $ctype course starting $startDate.", 'admin_reassign');
        $this->notify($studentId, 'student', "Your $ctype appointment has been reassigned to $newName, starting $startDate.", 'admin_reassign');

        $this->redirect(self::SELF_FILE . '?section=workload', "Appointment reassigned from $oldName to $newName ($startDate).");
    }

    private function markAdminRead(): void
    {
        $this->markNotificationsRead();
        $this->redirect(self::SELF_FILE . '?section=messages');
    }
}
