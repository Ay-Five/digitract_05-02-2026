<?php
/**
 * student/student_backend.php
 *
 * OOP PILLARS DEMONSTRATED:
 *  - ENCAPSULATION  : Database class wraps mysqli
 *  - INHERITANCE    : StudentDashboard extends BaseDashboard
 *  - ABSTRACTION    : handlePost() / loadData() contracts
 *  - POLYMORPHISM   : same handlePost() call works for Admin, Instructor, Student
 *
 * $conn and q() are kept available so student_dashboard22.php works unchanged.
 */

session_start();
if (!isset($_SESSION['student_id'])) {
    header('Location: ../student_login.php');
    exit;
}
$student_id = (int)$_SESSION['student_id'];

// ── 1. Raw connection — keeps $conn + q() alive for the view ─────────────────
require_once '../includes/db.php'; // gives $conn, $host, $user, $password, $database

// ── 2. OOP layer ──────────────────────────────────────────────────────────────
require_once '../includes/Database.php';
require_once '../includes/BaseDashboard.php';
require_once '../includes/Notifiable.php';
require_once '../includes/StudentDashboard.php';

// OOP PILLAR: ENCAPSULATION
$db = new Database($host, $user, $password, $database);

// OOP PILLAR: INHERITANCE + ABSTRACTION
$dashboard = new StudentDashboard($db, $student_id);

// OOP PILLAR: POLYMORPHISM
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dashboard->handlePost($_POST['action'] ?? '');
}

$dashboard->loadData();
$data = $dashboard->getData();

// ── 3. Helper functions the view uses ────────────────────────────────────────
if (!function_exists('q'))   { function q($c,$s){return $c->query($s);} }
if (!function_exists('esc')) { function esc($c,$v){return $c->real_escape_string($v);} }
if (!function_exists('stars')){ function stars($n){$s='';for($i=1;$i<=5;$i++)$s.=$i<=$n?'★':'☆';return $s;} }

// ── 4. Variables the view expects ─────────────────────────────────────────────
$today    = date('Y-m-d');
$selfFile = basename($_SERVER['PHP_SELF']);

$cColors = [
    'TDC Face-to-Face'         => 'tag-amber',
    'TDC Online'               => 'tag-blue',
    'PDC Manual'               => 'tag-green',
    'PDC Automatic'            => 'tag-teal',
    // legacy names kept for safety
    'PDC Car Manual'           => 'tag-green',
    'PDC Car Automatic'        => 'tag-teal',
    'PDC Motorcycle Manual'    => 'tag-coral',
    'PDC Motorcycle Automatic' => 'tag-purple',
];

$scmS = ['pending'=>'bkp','approved'=>'bksu','in_progress'=>'bkb',
          'completed'=>'bkg','rejected'=>'bkr','cancelled'=>'bkd'];
$scmL = ['pending'=>'Pending','approved'=>'Approved','in_progress'=>'In Progress',
          'completed'=>'Completed','rejected'=>'Rejected','cancelled'=>'Cancelled'];

$uploadDir = '../assets/uploads/certificates/';

$activeSection = preg_replace('/[^a-z_]/', '', $_GET['section'] ?? 'dashboard');
if (!empty($_GET['chat_instr']) && $activeSection === 'dashboard') $activeSection = 'chat';
$viewInstrId = (int)($_GET['view_instr'] ?? 0);

// Flash
$flash    = $_SESSION['flash']     ?? null; unset($_SESSION['flash']);
$flashErr = $_SESSION['flash_err'] ?? null; unset($_SESSION['flash_err']);

// ── 5. Re-run queries the view uses as mysqli result objects ──────────────────
// (view calls ->fetch_assoc(), ->num_rows, ->data_seek() on these)

$tdcDone = (int)q($conn,"SELECT COUNT(*) AS c FROM appointments a
    JOIN instructor_courses ic ON ic.course_id=a.course_id
    WHERE a.student_id=$student_id
      AND ic.course_type IN('TDC Face-to-Face','TDC Online')
      AND a.status='completed'")->fetch_assoc()['c'];

$activeInstructor = q($conn,"SELECT a.instructor_id,i.fullname,ic.course_type,a.status
    FROM appointments a
    JOIN instructors i ON i.instructor_id=a.instructor_id
    JOIN instructor_courses ic ON ic.course_id=a.course_id
    WHERE a.student_id=$student_id AND a.status IN('pending','approved','in_progress')
    ORDER BY a.requested_at DESC LIMIT 1")->fetch_assoc();

$student = q($conn,"SELECT * FROM students WHERE student_id=$student_id")->fetch_assoc();

$unreadCnt = (int)q($conn,"SELECT COUNT(*) AS c FROM notifications
    WHERE user_id=$student_id AND user_type='student' AND is_read=0")->fetch_assoc()['c'];

$myInstructors = q($conn,"SELECT DISTINCT i.instructor_id,i.fullname,i.email,
    a.appointment_id,ic.course_type,a.status
    FROM appointments a
    JOIN instructors i ON i.instructor_id=a.instructor_id
    JOIN instructor_courses ic ON ic.course_id=a.course_id
    WHERE a.student_id=$student_id ORDER BY i.fullname");

$unreadMsgMapS = [];
$umResS = q($conn,"SELECT sender_id,COUNT(*) AS cnt FROM messages
    WHERE receiver_id=$student_id AND receiver_type='student'
      AND is_read=0 AND sender_type='instructor' GROUP BY sender_id");
while($ums=$umResS->fetch_assoc()) $unreadMsgMapS[(int)$ums['sender_id']]=(int)$ums['cnt'];
$totalUnreadMsgS = array_sum($unreadMsgMapS);
$chatInstrId = (int)($_GET['chat_instr'] ?? 0);

$myAppts = q($conn,"SELECT a.*,ic.course_type,ic.price_per_hr,i.fullname AS instr_name,
    tc.cert_id,tc.delivery_method AS cert_delivery,tc.cert_image AS cert_img,
    ir.rating AS my_rating,
    (SELECT SUM(hours_covered) FROM sessions WHERE appointment_id=a.appointment_id AND status='completed') AS hours_done
    FROM appointments a
    JOIN instructor_courses ic ON ic.course_id=a.course_id
    JOIN instructors i ON i.instructor_id=a.instructor_id
    LEFT JOIN tdc_certificates tc ON tc.appointment_id=a.appointment_id
    LEFT JOIN instructor_ratings ir ON ir.appointment_id=a.appointment_id AND ir.student_id=$student_id
    WHERE a.student_id=$student_id ORDER BY a.requested_at DESC");

$allInstructors = q($conn,"SELECT i.instructor_id,i.fullname,i.email,i.certificate_image,
    (SELECT COUNT(*) FROM instructor_courses WHERE instructor_id=i.instructor_id AND is_active=1) AS course_count,
    (SELECT GROUP_CONCAT(DISTINCT course_type ORDER BY course_type SEPARATOR '|')
     FROM instructor_courses WHERE instructor_id=i.instructor_id AND is_active=1) AS courses,
    (SELECT ROUND(AVG(rating),1) FROM instructor_ratings WHERE instructor_id=i.instructor_id) AS avg_rating,
    (SELECT COUNT(*) FROM instructor_ratings WHERE instructor_id=i.instructor_id) AS rating_count
    FROM instructors i
    WHERE EXISTS(SELECT 1 FROM instructor_courses ic WHERE ic.instructor_id=i.instructor_id AND ic.is_active=1)
    ORDER BY avg_rating DESC, i.fullname ASC");

$viewInstr = null;
$instrAllCourses = null;
$viewInstrTotalStudents = 0;
$viewInstrTotalHrs      = 0;
$viewInstrTotalSess     = 0;
$viewInstrTDCCerts      = 0;
$viewInstrPDCCerts      = 0;
if ($viewInstrId > 0) {
    $viewInstr = q($conn,"SELECT i.instructor_id,i.fullname,i.email,i.certificate_image,i.bio,i.created_at,
        COALESCE(i.mobile_number,i.mobile) AS mobile,
        (SELECT ROUND(AVG(rating),1) FROM instructor_ratings WHERE instructor_id=i.instructor_id) AS avg_rating,
        (SELECT COUNT(*) FROM instructor_ratings WHERE instructor_id=i.instructor_id) AS rating_count
        FROM instructors i WHERE i.instructor_id=$viewInstrId")->fetch_assoc();
    $instrAllCourses = q($conn,"SELECT * FROM instructor_courses WHERE instructor_id=$viewInstrId AND is_active=1 ORDER BY course_type");

    // Stats for the profile card (Students / Hours Taught / Sessions / Certificates Issued)
    $viewInstrTotalStudents = (int)q($conn,"SELECT COUNT(DISTINCT student_id) AS c FROM appointments
        WHERE instructor_id=$viewInstrId AND status IN('approved','in_progress','completed')")->fetch_assoc()['c'];
    $viewInstrTotalHrs = (float)q($conn,"SELECT COALESCE(SUM(hours_covered),0) AS h FROM sessions
        WHERE instructor_id=$viewInstrId AND status='completed'")->fetch_assoc()['h'];
    $viewInstrTotalSess = (int)q($conn,"SELECT COUNT(*) AS c FROM sessions
        WHERE instructor_id=$viewInstrId AND status='completed'")->fetch_assoc()['c'];
    $viewInstrTDCCerts = (int)q($conn,"SELECT COUNT(*) AS c FROM tdc_certificates WHERE issued_by=$viewInstrId")->fetch_assoc()['c'];
    $viewInstrPDCCerts = (int)q($conn,"SELECT COUNT(*) AS c FROM pdc_certificates WHERE issued_by=$viewInstrId")->fetch_assoc()['c'];
}

$notifs    = q($conn,"SELECT * FROM notifications WHERE user_id=$student_id AND user_type='student' ORDER BY created_at DESC LIMIT 30");
$mySessions= q($conn,"SELECT s.*,a.course_id,ic.course_type,i.fullname AS instr_name
    FROM sessions s
    JOIN appointments a ON a.appointment_id=s.appointment_id
    JOIN instructor_courses ic ON ic.course_id=a.course_id
    JOIN instructors i ON i.instructor_id=s.instructor_id
    WHERE s.student_id=$student_id ORDER BY s.session_date DESC");

// Progress page vars
$sbTDCHrsPre  = (float)q($conn,"SELECT COALESCE(SUM(s.hours_covered),0) AS h FROM sessions s
    JOIN appointments a ON a.appointment_id=s.appointment_id
    JOIN instructor_courses ic ON ic.course_id=a.course_id
    WHERE s.student_id=$student_id AND s.status='completed' AND ic.course_type LIKE 'TDC%'")->fetch_assoc()['h'];
$sbTotalHrsPre= (float)q($conn,"SELECT COALESCE(SUM(hours_covered),0) AS h FROM sessions
    WHERE student_id=$student_id AND status='completed'")->fetch_assoc()['h'];
$sbPDCHrsPre  = max(0, $sbTotalHrsPre - $sbTDCHrsPre);
$sbTDCProgressPre = min(100, round($sbTDCHrsPre / 15 * 100));
$sbSessionsPre= (int)q($conn,"SELECT COUNT(*) AS c FROM sessions WHERE student_id=$student_id AND status='completed'")->fetch_assoc()['c'];

// Chart data
$stuMonths=[]; $stuTDCHrsMonth=[]; $stuPDCHrsMonth=[];
for($m=5;$m>=0;$m--){
    $stuMonths[]=date('M Y',strtotime("-$m months"));
    $ym=date('Y-m',strtotime("-$m months"));
    $stuTDCHrsMonth[]=(float)q($conn,"SELECT COALESCE(SUM(s.hours_covered),0) AS h FROM sessions s
        JOIN appointments a ON a.appointment_id=s.appointment_id
        JOIN instructor_courses ic ON ic.course_id=a.course_id
        WHERE s.student_id=$student_id AND s.status='completed'
          AND ic.course_type LIKE 'TDC%' AND DATE_FORMAT(s.session_date,'%Y-%m')='$ym'")->fetch_assoc()['h'];
    $stuPDCHrsMonth[]=(float)q($conn,"SELECT COALESCE(SUM(s.hours_covered),0) AS h FROM sessions s
        JOIN appointments a ON a.appointment_id=s.appointment_id
        JOIN instructor_courses ic ON ic.course_id=a.course_id
        WHERE s.student_id=$student_id AND s.status='completed'
          AND ic.course_type LIKE 'PDC%' AND DATE_FORMAT(s.session_date,'%Y-%m')='$ym'")->fetch_assoc()['h'];
}
$stuGradeTrend=[]; $stuGradeLabels=[];
$grRes=q($conn,"SELECT grade,session_date,session_number FROM sessions
    WHERE student_id=$student_id AND grade IS NOT NULL AND status='completed'
    ORDER BY session_date ASC,session_id ASC LIMIT 10");
while($gr=$grRes->fetch_assoc()){
    $stuGradeLabels[]='Day '.$gr['session_number'].' '.date('M d',strtotime($gr['session_date']));
    $stuGradeTrend[]=(int)$gr['grade'];
}

// ── Sidebar analytics variables (used by student_dashboard.php sidebar) ───────
$sbTotalHrs     = $sbTotalHrsPre;
$sbTDCHrs       = $sbTDCHrsPre;
$sbPDCHrs       = $sbPDCHrsPre;
$sbTDCProgress  = $sbTDCProgressPre;
$sbSessions     = $sbSessionsPre;
$sbThisMonthHrs = (float)q($conn,"SELECT COALESCE(SUM(hours_covered),0) AS h FROM sessions
    WHERE student_id=$student_id AND status='completed'
    AND DATE_FORMAT(session_date,'%Y-%m')=DATE_FORMAT(NOW(),'%Y-%m')")->fetch_assoc()['h'];
$sbAvgGradeSb   = q($conn,"SELECT ROUND(AVG(grade),1) AS ag FROM sessions
    WHERE student_id=$student_id AND grade IS NOT NULL")->fetch_assoc()['ag'] ?? 0;
$sbGradeColor   = $sbAvgGradeSb >= 80 ? '#3cb97a' : ($sbAvgGradeSb >= 60 ? '#f59e0b' : '#e55');
$sbCoursesDone  = (int)q($conn,"SELECT COUNT(DISTINCT course_id) AS c FROM appointments
    WHERE student_id=$student_id AND status='completed'")->fetch_assoc()['c'];

// Avg grade stat card (used in dashboard section)
$avgGrade = q($conn,"SELECT ROUND(AVG(grade),1) AS ag FROM sessions
    WHERE student_id=$student_id AND grade IS NOT NULL")->fetch_assoc()['ag'] ?? null;
