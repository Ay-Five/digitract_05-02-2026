<?php
/**
 * instructor/instructor_backend.php
 *
 * OOP PILLARS DEMONSTRATED:
 *  - ENCAPSULATION  : Database class wraps mysqli
 *  - INHERITANCE    : InstructorDashboard extends BaseDashboard
 *  - ABSTRACTION    : handlePost() / loadData() contracts
 *  - POLYMORPHISM   : same handlePost() call works for Admin, Instructor, Student
 *
 * $conn and q() are kept available so instructor_dashboard1.php works unchanged.
 */

session_start();
if (!isset($_SESSION['instructor_id'])) {
    header('Location: ../instructor_login.php');
    exit;
}
$instructor_id = (int)$_SESSION['instructor_id'];

// ── 1. Raw connection — keeps $conn + q() alive for the view ─────────────────
require_once '../includes/db.php'; // gives $conn, $host, $user, $password, $database

// ── 2. OOP layer ──────────────────────────────────────────────────────────────
require_once '../includes/Database.php';
require_once '../includes/BaseDashboard.php';
require_once '../includes/Notifiable.php';
require_once '../includes/InstructorDashboard.php';

// OOP PILLAR: ENCAPSULATION
$db = new Database($host, $user, $password, $database);

// OOP PILLAR: INHERITANCE + ABSTRACTION
$dashboard = new InstructorDashboard($db, $instructor_id);

// OOP PILLAR: POLYMORPHISM
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dashboard->handlePost($_POST['action'] ?? '');
}

$dashboard->loadData();
$data = $dashboard->getData();

// ── 3. Helper functions the view uses ────────────────────────────────────────
if (!function_exists('q'))    { function q($c,$s){return $c->query($s);} }
if (!function_exists('esc'))  { function esc($c,$v){return $c->real_escape_string($v);} }
if (!function_exists('stars')){ function stars($n){$s='';for($i=1;$i<=5;$i++)$s.=$i<=$n?'★':'☆';return $s;} }

// ── 4. Variables the view expects ─────────────────────────────────────────────
$today    = date('Y-m-d');
$selfFile = basename($_SERVER['PHP_SELF']);

$cColors = [
    'TDC Face-to-Face'         => 'tag-amber',
    'TDC Online'               => 'tag-blue',
    'PDC Manual'               => 'tag-green',
    'PDC Automatic'            => 'tag-teal',
    'PDC Car Manual'           => 'tag-green',
    'PDC Car Automatic'        => 'tag-teal',
    'PDC Motorcycle Manual'    => 'tag-coral',
    'PDC Motorcycle Automatic' => 'tag-purple',
];

$scmS = ['pending'=>'bkp','approved'=>'bksu','in_progress'=>'bkb',
          'completed'=>'bkg','rejected'=>'bkr','cancelled'=>'bkd'];
$scmL = ['pending'=>'Pending','approved'=>'Approved','in_progress'=>'In Progress',
          'completed'=>'Completed','rejected'=>'Rejected','cancelled'=>'Cancelled'];

$uploadDir      = '../assets/uploads/certificates/';
$courseTypeOpts = ['TDC Face-to-Face','TDC Online','PDC Manual','PDC Automatic'];

$activeSection = preg_replace('/[^a-z_]/', '', $_GET['section'] ?? 'dashboard');
if (!empty($_GET['chat_student']) && $activeSection === 'dashboard') $activeSection = 'chat';
$viewStudentId = (int)($_GET['view_student'] ?? 0);
if ($viewStudentId > 0 && $activeSection === 'dashboard') $activeSection = 'student_profile';

// Flash
$flash    = $_SESSION['flash'] ?? null; unset($_SESSION['flash']);

// ── 5. Re-run all queries the view uses as real mysqli result objects ─────────

$instructor = q($conn,"SELECT * FROM instructors WHERE instructor_id=$instructor_id")->fetch_assoc();

$unreadCnt = (int)q($conn,"SELECT COUNT(*) AS c FROM notifications
    WHERE user_id=$instructor_id AND user_type='instructor' AND is_read=0")->fetch_assoc()['c'];

$myStudents = q($conn,"SELECT DISTINCT s.student_id,s.fullname,s.email,a.appointment_id,ic.course_type,a.status
    FROM appointments a
    JOIN students s ON s.student_id=a.student_id
    JOIN instructor_courses ic ON ic.course_id=a.course_id
    WHERE a.instructor_id=$instructor_id ORDER BY s.fullname");

$unreadMsgMap = [];
$umRes = q($conn,"SELECT sender_id,COUNT(*) AS cnt FROM messages
    WHERE receiver_id=$instructor_id AND receiver_type='instructor'
      AND is_read=0 AND sender_type='student' GROUP BY sender_id");
while($um=$umRes->fetch_assoc()) $unreadMsgMap[(int)$um['sender_id']]=(int)$um['cnt'];
$totalUnreadMsg = array_sum($unreadMsgMap);
$chatStudentId = (int)($_GET['chat_student'] ?? 0);

$pendingAppts = q($conn,"SELECT a.*,ic.course_type,s.fullname AS sname,s.email AS semail
    FROM appointments a
    JOIN instructor_courses ic ON ic.course_id=a.course_id
    JOIN students s ON s.student_id=a.student_id
    WHERE a.instructor_id=$instructor_id AND a.status='pending'
    ORDER BY a.start_date ASC, a.requested_at ASC");

$allAppts = q($conn,"SELECT a.*,ic.course_type,s.fullname AS sname,
    (SELECT SUM(hours_covered) FROM sessions WHERE appointment_id=a.appointment_id AND status='completed') AS hours_done,
    tc.cert_id
    FROM appointments a
    JOIN instructor_courses ic ON ic.course_id=a.course_id
    JOIN students s ON s.student_id=a.student_id
    LEFT JOIN tdc_certificates tc ON tc.appointment_id=a.appointment_id
    WHERE a.instructor_id=$instructor_id
    ORDER BY a.start_date DESC, a.requested_at DESC");

$myStudents = q($conn,"SELECT DISTINCT s.student_id,s.fullname,s.email,
    (SELECT ic.course_type FROM appointments a2 JOIN instructor_courses ic ON ic.course_id=a2.course_id
     WHERE a2.instructor_id=$instructor_id AND a2.student_id=s.student_id ORDER BY a2.requested_at DESC LIMIT 1) AS latest_course,
    (SELECT a3.status FROM appointments a3 WHERE a3.instructor_id=$instructor_id AND a3.student_id=s.student_id ORDER BY a3.requested_at DESC LIMIT 1) AS latest_status
    FROM students s
    WHERE EXISTS(SELECT 1 FROM appointments a WHERE a.instructor_id=$instructor_id AND a.student_id=s.student_id AND a.status IN('approved','in_progress','completed','pending'))
    ORDER BY s.fullname ASC");

$unratedSessions = q($conn,"SELECT sess.*,a.course_id,ic.course_type,s.fullname AS sname
    FROM sessions sess
    JOIN appointments a ON a.appointment_id=sess.appointment_id
    JOIN instructor_courses ic ON ic.course_id=a.course_id
    JOIN students s ON s.student_id=sess.student_id
    WHERE sess.instructor_id=$instructor_id AND sess.status='completed'
      AND (sess.performance_rating IS NULL OR sess.grade IS NULL)
      AND ic.course_type LIKE 'TDC%'
    ORDER BY sess.session_date DESC");

$myCourses = q($conn,"SELECT * FROM instructor_courses WHERE instructor_id=$instructor_id ORDER BY course_type");
$notifs    = q($conn,"SELECT * FROM notifications WHERE user_id=$instructor_id AND user_type='instructor' ORDER BY created_at DESC LIMIT 30");

$statPending = (int)q($conn,"SELECT COUNT(*) AS c FROM appointments WHERE instructor_id=$instructor_id AND status='pending'")->fetch_assoc()['c'];
$statActive  = (int)q($conn,"SELECT COUNT(*) AS c FROM appointments WHERE instructor_id=$instructor_id AND status IN('approved','in_progress')")->fetch_assoc()['c'];
$statDone    = (int)q($conn,"SELECT COUNT(*) AS c FROM appointments WHERE instructor_id=$instructor_id AND status='completed'")->fetch_assoc()['c'];
$avgRating   = q($conn,"SELECT ROUND(AVG(rating),1) AS r FROM instructor_ratings WHERE instructor_id=$instructor_id")->fetch_assoc()['r'];

$topStudents = q($conn,"SELECT s.student_id,s.fullname,ROUND(AVG(sess.grade),1) AS avg_grade,COUNT(sess.session_id) AS sess_cnt
    FROM sessions sess JOIN students s ON s.student_id=sess.student_id
    WHERE sess.instructor_id=$instructor_id AND sess.grade IS NOT NULL
      AND DATE_FORMAT(sess.session_date,'%Y-%m')=DATE_FORMAT(NOW(),'%Y-%m')
    GROUP BY s.student_id ORDER BY avg_grade DESC LIMIT 5");

// Chart data
$instrMonths=[]; $instrSessPerMonth=[];
for($m=5;$m>=0;$m--){
    $instrMonths[]=date('M Y',strtotime("-$m months"));
    $ym=date('Y-m',strtotime("-$m months"));
    $instrSessPerMonth[]=(float)q($conn,"SELECT COALESCE(SUM(hours_covered),0) AS h FROM sessions
        WHERE instructor_id=$instructor_id AND status='completed' AND DATE_FORMAT(session_date,'%Y-%m')='$ym'")->fetch_assoc()['h'];
}
$instrGradeBuckets=['90-100'=>0,'80-89'=>0,'70-79'=>0,'60-69'=>0,'<60'=>0];
$igRes=q($conn,"SELECT grade FROM sessions WHERE instructor_id=$instructor_id AND grade IS NOT NULL AND status='completed'");
while($ig=$igRes->fetch_assoc()){
    $g=(int)$ig['grade'];
    if($g>=90)$instrGradeBuckets['90-100']++;
    elseif($g>=80)$instrGradeBuckets['80-89']++;
    elseif($g>=70)$instrGradeBuckets['70-79']++;
    elseif($g>=60)$instrGradeBuckets['60-69']++;
    else $instrGradeBuckets['<60']++;
}
$instrTDCSess=(int)q($conn,"SELECT COUNT(*) AS c FROM sessions WHERE instructor_id=$instructor_id AND session_type='TDC' AND status='completed'")->fetch_assoc()['c'];
$instrPDCSess=(int)q($conn,"SELECT COUNT(*) AS c FROM sessions WHERE instructor_id=$instructor_id AND session_type='PDC' AND status='completed'")->fetch_assoc()['c'];

// Sidebar stats
$sbTotalStudents =(int)q($conn,"SELECT COUNT(DISTINCT student_id) AS c FROM appointments WHERE instructor_id=$instructor_id AND status IN('approved','in_progress','completed')")->fetch_assoc()['c'];
$sbTotalHrsTaught=(float)q($conn,"SELECT COALESCE(SUM(hours_covered),0) AS h FROM sessions WHERE instructor_id=$instructor_id AND status='completed'")->fetch_assoc()['h'];
$sbMonthHrs      =(float)q($conn,"SELECT COALESCE(SUM(hours_covered),0) AS h FROM sessions WHERE instructor_id=$instructor_id AND status='completed' AND DATE_FORMAT(session_date,'%Y-%m')=DATE_FORMAT(NOW(),'%Y-%m')")->fetch_assoc()['h'];
$sbCertsIssued   =(int)q($conn,"SELECT COUNT(*) AS c FROM tdc_certificates WHERE issued_by=$instructor_id")->fetch_assoc()['c'];
$sbAvgRatingSb   = $avgRating ?? 0;
$sbRatColor      = $sbAvgRatingSb >= 4 ? '#3cb97a' : ($sbAvgRatingSb >= 3 ? '#f59e0b' : '#e55');
