<?php
require_once 'admin_backend.php';
$thisMonth = date('Y-m');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Dashboard – MotoSafe Driving School</title>
<link href="../assets/img/logo.png" rel="icon">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
:root{--bg:#0f1117;--bg2:#181c27;--bg3:#1e2436;--border:#2a3147;--accent:#7c6af7;--green:#3cb97a;--amber:#f59e0b;--red:#e55;--text:#e2e5f0;--muted:#8490a8}
*{box-sizing:border-box;margin:0;padding:0}
body{background:var(--bg);color:var(--text);font-family:'Segoe UI',sans-serif;min-height:100vh;display:flex}
.sidebar{width:240px;background:var(--bg2);border-right:1px solid var(--border);display:flex;flex-direction:column;padding:20px 0;flex-shrink:0;position:fixed;height:100vh;overflow-y:auto;z-index:100}
.brand{padding:0 18px 20px;font-size:15px;font-weight:700;color:var(--accent);border-bottom:1px solid var(--border);margin-bottom:12px}
.nav-item{display:flex;align-items:center;gap:10px;padding:10px 18px;color:var(--muted);font-size:13px;cursor:pointer;text-decoration:none;border:none;background:none;width:100%;text-align:left;transition:.15s}
.nav-item:hover,.nav-item.active{background:rgba(124,106,247,.12);color:var(--text)}
.nav-item.active{border-left:3px solid var(--accent);color:var(--accent)}
.nav-item i{width:16px;text-align:center}
.section-divider{font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;padding:8px 18px 4px;margin-top:4px}
.main{margin-left:240px;flex:1;padding:24px;min-height:100vh}
.page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px}
.page-title{font-size:18px;font-weight:600}
.section{display:none}.section.active{display:block}
.panel{background:var(--bg2);border:1px solid var(--border);border-radius:10px;overflow:hidden;margin-bottom:16px}
.panel-head{padding:14px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}
.panel-title{font-size:13px;font-weight:600;display:flex;align-items:center;gap:8px}
.stats-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:12px;margin-bottom:20px}
.stat-card{background:var(--bg2);border:1px solid var(--border);border-radius:10px;padding:16px;cursor:pointer;transition:.15s}
.stat-card:hover{border-color:var(--accent)}
.stat-num{font-size:26px;font-weight:700;color:var(--accent)}
.stat-label{font-size:11px;color:var(--muted);margin-top:4px}
.analytics-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px;margin-bottom:20px}
.analytic-card{background:var(--bg3);border:1px solid var(--border);border-radius:10px;padding:16px;display:flex;flex-direction:column;gap:6px}
.analytic-num{font-size:22px;font-weight:700}
.analytic-label{font-size:11px;color:var(--muted)}
.analytic-sub{font-size:10px;color:var(--muted)}
.duo-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px}
@media(max-width:900px){.duo-grid{grid-template-columns:1fr}}
.tag{display:inline-flex;align-items:center;gap:4px;padding:2px 8px;border-radius:20px;font-size:11px;font-weight:500}
.tag-amber{background:rgba(245,158,11,.15);color:#f59e0b}.tag-blue{background:rgba(59,130,246,.15);color:#60a5fa}
.tag-green{background:rgba(59,185,122,.15);color:#3cb97a}.tag-teal{background:rgba(20,184,166,.15);color:#2dd4bf}
.tag-coral{background:rgba(239,68,68,.15);color:#f87171}.tag-purple{background:rgba(124,106,247,.15);color:#a78bfa}
.tag-gray{background:rgba(100,116,139,.15);color:#94a3b8}
.bkp{background:rgba(245,158,11,.15);color:#f59e0b;padding:3px 10px;border-radius:20px;font-size:11px}
.bksu{background:rgba(59,130,246,.15);color:#60a5fa;padding:3px 10px;border-radius:20px;font-size:11px}
.bkb{background:rgba(124,106,247,.15);color:#a78bfa;padding:3px 10px;border-radius:20px;font-size:11px}
.bkg{background:rgba(59,185,122,.15);color:#3cb97a;padding:3px 10px;border-radius:20px;font-size:11px}
.bkr{background:rgba(229,85,85,.15);color:#f87171;padding:3px 10px;border-radius:20px;font-size:11px}
.bkd{background:rgba(100,116,139,.15);color:#94a3b8;padding:3px 10px;border-radius:20px;font-size:11px}
.be{background:var(--accent);color:#fff;border:none;padding:6px 14px;border-radius:6px;font-size:12px;cursor:pointer;display:inline-flex;align-items:center;gap:6px;transition:.15s}
.be:hover{opacity:.85}.bds{background:transparent;border:1px solid var(--border);color:var(--muted);padding:5px 12px;border-radius:6px;font-size:12px;cursor:pointer}
.ber{background:rgba(229,85,85,.15);color:#f87171;border:1px solid rgba(229,85,85,.3);padding:5px 12px;border-radius:6px;font-size:12px;cursor:pointer}
.tbl{width:100%;border-collapse:collapse;font-size:12px}
.tbl th{padding:10px 12px;text-align:left;color:var(--muted);font-weight:500;border-bottom:1px solid var(--border);font-size:11px}
.tbl td{padding:10px 12px;border-bottom:1px solid var(--border);vertical-align:middle}
.tbl tr:last-child td{border-bottom:none}
.tbl tbody tr:hover{background:rgba(255,255,255,.02)}
.di{background:var(--bg);border:1px solid var(--border);color:var(--text);padding:7px 10px;border-radius:6px;font-size:12px;width:100%}
.di:focus{outline:none;border-color:var(--accent)}
.form-label{font-size:11px;color:var(--muted);margin-bottom:4px;display:block}
.flash{padding:10px 16px;border-radius:8px;margin-bottom:16px;font-size:12px}
.flash-ok{background:rgba(59,185,122,.15);border:1px solid rgba(59,185,122,.3);color:var(--green)}
.prog-bar{height:8px;background:var(--border);border-radius:4px;overflow:hidden}
.prog-fill{height:100%;background:var(--accent);border-radius:4px}
.cert-badge{background:rgba(245,158,11,.12);border:1px solid rgba(245,158,11,.3);color:#f59e0b;padding:4px 10px;border-radius:20px;font-size:11px;display:inline-flex;align-items:center;gap:5px}
.rank-medal{font-size:14px;width:22px;text-align:center}
.grade-bar{height:6px;background:var(--border);border-radius:3px;overflow:hidden;margin-top:3px}
.grade-fill{height:100%;border-radius:3px}
.sb-analytics{padding:14px 14px 0;border-top:1px solid var(--border);margin-top:8px}
.sb-analytics-title{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:10px;display:flex;align-items:center;gap:6px}
.sb-stat{display:flex;justify-content:space-between;align-items:center;padding:6px 0;border-bottom:1px solid rgba(42,49,71,.5)}
.sb-stat:last-child{border-bottom:none}
.sb-stat-label{font-size:11px;color:var(--muted)}
.sb-stat-val{font-size:12px;font-weight:700;color:var(--text)}
.sb-mini-bar{height:4px;background:var(--border);border-radius:2px;overflow:hidden;margin-top:3px;width:70px}
.sb-mini-fill{height:100%;border-radius:2px;background:var(--accent)}
</style>
</head>
<body>
<script>
var T={dashboard:'Dashboard',students:'Students',instructors:'Instructors',
  appointments:'Appointments',certificates:'Certificates',rankings:'Rankings',
  reports:'Reports',communication:'Announcements',workload:'Workload Distribution',
  student_profile:'Student Profile',instructor_profile:'Instructor Profile'};
</script>
<div class="sidebar">
    <div class="brand"><i class="fa-solid fa-shield-halved me-2"></i>ABROT Admin</div>
    <a class="nav-item <?= $activeSection==='dashboard'?'active':'' ?>" onclick="showSection('dashboard')" href="javascript:void(0)"><i class="fa-solid fa-gauge"></i> Dashboard</a>
    <div class="section-divider">Management</div>
    <a class="nav-item <?= in_array($activeSection,['students','student_profile'])?'active':'' ?>" onclick="showSection('students')" href="javascript:void(0)"><i class="fa-solid fa-user-graduate"></i> Students</a>
    <a class="nav-item <?= in_array($activeSection,['instructors','instructor_profile'])?'active':'' ?>" onclick="showSection('instructors')" href="javascript:void(0)"><i class="fa-solid fa-chalkboard-user"></i> Instructors</a>
    <a class="nav-item <?= $activeSection==='appointments'?'active':'' ?>" onclick="showSection('appointments')" href="javascript:void(0)"><i class="fa-solid fa-calendar-check"></i> Appointments<?php if($pendingAppts>0): ?><span class="tag tag-amber"><?= $pendingAppts ?></span><?php endif; ?></a>
    <a class="nav-item <?= $activeSection==='certificates'?'active':'' ?>" onclick="showSection('certificates')" href="javascript:void(0)"><i class="fa-solid fa-certificate"></i> Certificates</a>
    <div class="section-divider">Insights</div>
    <a class="nav-item <?= $activeSection==='rankings'?'active':'' ?>" onclick="showSection('rankings')" href="javascript:void(0)"><i class="fa-solid fa-ranking-star"></i> Rankings</a>
    <a class="nav-item <?= $activeSection==='reports'?'active':'' ?>" onclick="showSection('reports')" href="javascript:void(0)"><i class="fa-solid fa-chart-bar"></i> Reports</a>
    <a class="nav-item <?= $activeSection==='communication'?'active':'' ?>" onclick="showSection('communication')" href="javascript:void(0)"><i class="fa-solid fa-bullhorn"></i> Announcements</a>
    <a class="nav-item <?= $activeSection==='workload'?'active':'' ?>" onclick="showSection('workload')" href="javascript:void(0)"><i class="fa-solid fa-chart-gantt"></i> Workload</a>
    <div style="flex:1"></div>
    <div class="sb-analytics">
        <div class="sb-analytics-title"><i class="fa-solid fa-chart-line" style="color:var(--accent)"></i> Live Stats</div>
        <div class="sb-stat"><div class="sb-stat-label">Active Appointments</div><div class="sb-stat-val" style="color:var(--amber)"><?= $sbActiveNow ?></div></div>
        <div class="sb-stat"><div class="sb-stat-label">Today's Sessions</div><div class="sb-stat-val"><?= $sbSessionsToday ?></div></div>
        <div class="sb-stat"><div class="sb-stat-label">Total Sessions</div><div class="sb-stat-val"><?= $totalSessions ?></div></div>
        <div class="sb-stat"><div><div class="sb-stat-label">Avg Instr. Rating</div><div class="sb-mini-bar"><div class="sb-mini-fill" style="width:<?= $sbAvgRatingSb*20 ?>%;background:<?= $sbRatColor ?>"></div></div></div><div class="sb-stat-val" style="color:<?= $sbRatColor ?>">★<?= $sbAvgRatingSb ?></div></div>
        <div class="sb-stat"><div class="sb-stat-label">TDC Certs Issued</div><div class="sb-stat-val" style="color:var(--amber)"><?= $certsIssued ?></div></div>
        <div class="sb-stat"><div class="sb-stat-label">PDC Certs Issued</div><div class="sb-stat-val" style="color:var(--green)"><?= $pdcCertsIssued ?></div></div>
        <div class="sb-stat"><div class="sb-stat-label">Unverified Students</div><div class="sb-stat-val" style="color:<?= $sbUnverified>0?'var(--amber)':'var(--muted)' ?>"><?= $sbUnverified ?></div></div>
        <div class="sb-stat"><div class="sb-stat-label">New Students/Mo</div><div class="sb-stat-val" style="color:var(--accent)"><?= $newStudentsMonth ?></div></div>
        <div class="sb-stat"><div class="sb-stat-label">Today's Logins</div><div class="sb-stat-val"><?= $todayLogins ?></div></div>
    </div>
    <a class="nav-item" href="../admin_logout.php"><i class="fa-solid fa-sign-out-alt"></i> Logout</a>
</div>

<div class="main">
<div class="page-header">
    <div class="page-title" id="page-title-text">Dashboard</div>
    <div style="font-size:12px;color:var(--muted)"><?= $today ?></div>
</div>
<?php if($flash): ?><div class="flash flash-ok"><i class="fa-solid fa-circle-check me-2"></i><?= htmlspecialchars($flash) ?></div><?php endif; ?>

<!-- ══ DASHBOARD ══════════════════════════════════════════════ -->
<div id="section-dashboard" class="section <?= $activeSection==='dashboard'?'active':'' ?>">
<div class="stats-row">
    <div class="stat-card" onclick="showSection('students')"><div class="stat-num"><?= $totalStudents ?></div><div class="stat-label">Total Students</div></div>
    <div class="stat-card" onclick="showSection('instructors')"><div class="stat-num"><?= $totalInstructors ?></div><div class="stat-label">Instructors</div></div>
    <div class="stat-card" onclick="showSection('appointments')"><div class="stat-num" style="color:var(--amber)"><?= $pendingAppts ?></div><div class="stat-label">Pending Appts</div></div>
    <div class="stat-card" onclick="showSection('appointments')"><div class="stat-num" style="color:var(--green)"><?= $completedAppts ?></div><div class="stat-label">Completed</div></div>
    <div class="stat-card" onclick="showSection('certificates')"><div class="stat-num" style="color:var(--amber)"><?= $certsIssued ?></div><div class="stat-label">TDC Certs</div></div>
    <div class="stat-card" onclick="showSection('certificates')"><div class="stat-num" style="color:var(--green)"><?= $pdcCertsIssued ?></div><div class="stat-label">PDC Certs</div></div>
    <div class="stat-card"><div class="stat-num" style="color:var(--accent)"><?= $todayLogins ?></div><div class="stat-label">Today's Logins</div></div>
</div>

<!-- Top & Low Instructors -->
<div class="duo-grid">
<div class="panel">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-trophy" style="color:var(--amber)"></i> Top 5 Rated Instructors</div>
        <a class="bds" onclick="showSection('instructors')" href="javascript:void(0)" style="font-size:11px">View All</a></div>
    <table class="tbl"><thead><tr><th>#</th><th>Instructor</th><th>Rating</th><th>Reviews</th></tr></thead><tbody>
    <?php $topInstructors->data_seek(0); $rank=1; if($topInstructors->num_rows===0): ?>
    <tr><td colspan="4" style="color:var(--muted);text-align:center;padding:20px">No ratings yet.</td></tr>
    <?php else: while($ti=$topInstructors->fetch_assoc()): $medals=['🥇','🥈','🥉','4️⃣','5️⃣']; ?>
    <tr><td><span class="rank-medal"><?= $medals[$rank-1]??$rank ?></span></td>
        <td><a href="<?= $selfFile ?>?section=instructor_profile&view_instructor=<?= $ti['instructor_id'] ?>" style="color:var(--text);text-decoration:none"><?= htmlspecialchars($ti['fullname']) ?></a></td>
        <td style="color:var(--amber)">★ <?= $ti['avg_r'] ?></td><td><?= $ti['cnt'] ?></td></tr>
    <?php $rank++; endwhile; endif; ?></tbody></table>
</div>
<div class="panel">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-arrow-trend-down" style="color:var(--red)"></i> Low Rated Instructors</div></div>
    <table class="tbl"><thead><tr><th>#</th><th>Instructor</th><th>Rating</th><th>Reviews</th></tr></thead><tbody>
    <?php $lowInstructors->data_seek(0); $rank=1; if($lowInstructors->num_rows===0): ?>
    <tr><td colspan="4" style="color:var(--muted);text-align:center;padding:20px">No ratings yet.</td></tr>
    <?php else: while($li=$lowInstructors->fetch_assoc()): ?>
    <tr><td><span style="color:var(--muted);font-size:12px"><?= $rank ?></span></td>
        <td><a href="<?= $selfFile ?>?section=instructor_profile&view_instructor=<?= $li['instructor_id'] ?>" style="color:var(--text);text-decoration:none"><?= htmlspecialchars($li['fullname']) ?></a></td>
        <td style="color:var(--red)">★ <?= $li['avg_r'] ?></td><td><?= $li['cnt'] ?></td></tr>
    <?php $rank++; endwhile; endif; ?></tbody></table>
</div>
</div>

<!-- Top & Low Students This Month -->
<div class="duo-grid">
<div class="panel">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-star" style="color:var(--amber)"></i> Top 5 Students – <?= date('F Y') ?></div>
        <small style="color:var(--muted);font-size:10px">Resets monthly</small></div>
    <table class="tbl"><thead><tr><th>#</th><th>Student</th><th>Avg Grade</th><th>Sessions</th></tr></thead><tbody>
    <?php $topStudents->data_seek(0); $rank=1; if($topStudents->num_rows===0): ?>
    <tr><td colspan="4" style="color:var(--muted);text-align:center;padding:20px">No grades this month.</td></tr>
    <?php else: while($ts=$topStudents->fetch_assoc()): $medals=['🥇','🥈','🥉','4️⃣','5️⃣']; $gc=$ts['avg_grade']>=80?'var(--green)':($ts['avg_grade']>=60?'var(--amber)':'var(--red)'); ?>
    <tr><td><span class="rank-medal"><?= $medals[$rank-1]??$rank ?></span></td>
        <td><a href="<?= $selfFile ?>?section=student_profile&view_student=<?= $ts['student_id'] ?>" style="color:var(--text);text-decoration:none"><?= htmlspecialchars($ts['fullname']) ?></a></td>
        <td style="color:<?= $gc ?>;font-weight:600"><?= $ts['avg_grade'] ?>/100</td><td><?= $ts['sess_cnt'] ?></td></tr>
    <?php $rank++; endwhile; endif; ?></tbody></table>
</div>
<div class="panel">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-arrow-trend-down" style="color:var(--red)"></i> Low Performing Students – <?= date('F Y') ?></div>
        <small style="color:var(--muted);font-size:10px">Resets monthly</small></div>
    <table class="tbl"><thead><tr><th>#</th><th>Student</th><th>Avg Grade</th><th>Sessions</th></tr></thead><tbody>
    <?php $lowStudents->data_seek(0); $rank=1; if($lowStudents->num_rows===0): ?>
    <tr><td colspan="4" style="color:var(--muted);text-align:center;padding:20px">No grades this month.</td></tr>
    <?php else: while($ls=$lowStudents->fetch_assoc()): ?>
    <tr><td><span style="color:var(--muted);font-size:12px"><?= $rank ?></span></td>
        <td><a href="<?= $selfFile ?>?section=student_profile&view_student=<?= $ls['student_id'] ?>" style="color:var(--text);text-decoration:none"><?= htmlspecialchars($ls['fullname']) ?></a></td>
        <td style="color:var(--red);font-weight:600"><?= $ls['avg_grade'] ?>/100</td><td><?= $ls['sess_cnt'] ?></td></tr>
    <?php $rank++; endwhile; endif; ?></tbody></table>
</div>
</div>

<!-- Recent Appointments -->
<div class="panel">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-clock"></i> Recent Appointments</div>
        <a class="be" onclick="showSection('appointments')" href="javascript:void(0)" style="font-size:11px">View All</a></div>
    <table class="tbl"><thead><tr><th>Student</th><th>Instructor</th><th>Course</th><th>Start Date</th><th>Status</th></tr></thead><tbody>
    <?php $recentA=q($conn,"SELECT a.*,s.fullname AS sname,i.fullname AS iname,ic.course_type FROM appointments a JOIN students s ON s.student_id=a.student_id JOIN instructors i ON i.instructor_id=a.instructor_id JOIN instructor_courses ic ON ic.course_id=a.course_id ORDER BY a.requested_at DESC LIMIT 8");
    if($recentA->num_rows===0): ?><tr><td colspan="5" style="color:var(--muted);text-align:center;padding:20px">No appointments yet.</td></tr>
    <?php else: while($ra=$recentA->fetch_assoc()): $tc=$cColors[$ra['course_type']]??'tag-gray'; ?>
    <tr><td><?= htmlspecialchars($ra['sname']) ?></td><td><?= htmlspecialchars($ra['iname']) ?></td>
        <td><span class="tag <?= $tc ?>"><?= htmlspecialchars($ra['course_type']) ?></span></td>
        <td><?= date('M d, Y',strtotime($ra['start_date'])) ?></td>
        <td><span class="<?= $scmS[$ra['status']]??'bkd' ?>"><?= $scmL[$ra['status']]??$ra['status'] ?></span></td></tr>
    <?php endwhile; endif; ?></tbody></table>
</div>

<!-- Analytics Overview -->
<div style="font-size:12px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin:20px 0 12px;padding-bottom:6px;border-bottom:1px solid var(--border)">
    <i class="fa-solid fa-chart-line me-2" style="color:var(--accent)"></i>Analytics Overview
</div>
<div class="analytics-grid" style="margin-bottom:16px">
    <div class="analytic-card"><div class="analytic-num" style="color:var(--accent)"><?= $todayLogins ?></div><div class="analytic-label"><i class="fa-solid fa-right-to-bracket me-1"></i>Today's Logins</div><div class="analytic-sub">All users</div></div>
    <div class="analytic-card"><div class="analytic-num" style="color:var(--green)"><?= $monthLogins ?></div><div class="analytic-label"><i class="fa-solid fa-calendar me-1"></i>Monthly Logins</div><div class="analytic-sub"><?= date('F Y') ?></div></div>
    <div class="analytic-card"><div class="analytic-num" style="color:var(--amber)"><?= $newStudentsMonth ?></div><div class="analytic-label"><i class="fa-solid fa-user-plus me-1"></i>New Students</div><div class="analytic-sub">This month</div></div>
    <div class="analytic-card"><div class="analytic-num" style="color:var(--amber)"><?= number_format($totalHoursTaught,1) ?>h</div><div class="analytic-label"><i class="fa-solid fa-clock me-1"></i>Hours Taught</div><div class="analytic-sub">All time</div></div>
    <div class="analytic-card"><div class="analytic-num" style="color:var(--amber)"><?= $certsIssued ?></div><div class="analytic-label"><i class="fa-solid fa-certificate me-1"></i>TDC Certs</div><div class="analytic-sub">All time</div></div>
    <div class="analytic-card"><div class="analytic-num" style="color:var(--green)"><?= $pdcCertsIssued ?></div><div class="analytic-label"><i class="fa-solid fa-certificate me-1"></i>PDC Certs</div><div class="analytic-sub">All time</div></div>
</div>
<div class="duo-grid" style="margin-bottom:16px">
    <div class="panel"><div class="panel-head"><div class="panel-title"><i class="fa-solid fa-chart-line me-1"></i> Monthly New Students (6 Months)</div></div>
        <div style="position:relative;height:220px;padding:12px"><canvas id="chartNewStudents"></canvas></div></div>
    <div class="panel"><div class="panel-head"><div class="panel-title"><i class="fa-solid fa-chart-bar me-1"></i> Appointment Status by Month</div></div>
        <div style="position:relative;height:220px;padding:12px"><canvas id="chartApptStatus"></canvas></div></div>
</div>
<div class="duo-grid" style="margin-bottom:16px">
    <div class="panel"><div class="panel-head"><div class="panel-title"><i class="fa-solid fa-chart-pie me-1"></i> TDC vs PDC Completion</div></div>
        <div style="display:flex;align-items:center;justify-content:center;padding:16px;gap:24px">
            <div style="position:relative;width:180px;height:180px;flex-shrink:0"><canvas id="chartTdcPdc"></canvas></div>
            <div style="font-size:12px;display:flex;flex-direction:column;gap:10px">
                <div style="display:flex;align-items:center;gap:8px"><span style="width:12px;height:12px;border-radius:50%;background:var(--amber);display:inline-block;flex-shrink:0"></span><div><div style="font-weight:700;font-size:16px;color:var(--amber)"><?= $tdcDoneCount ?></div><div style="color:var(--muted);font-size:11px">TDC Completed</div></div></div>
                <div style="display:flex;align-items:center;gap:8px"><span style="width:12px;height:12px;border-radius:50%;background:var(--green);display:inline-block;flex-shrink:0"></span><div><div style="font-weight:700;font-size:16px;color:var(--green)"><?= $pdcDoneCount ?></div><div style="color:var(--muted);font-size:11px">PDC Completed</div></div></div>
                <div style="display:flex;align-items:center;gap:8px"><span style="width:12px;height:12px;border-radius:50%;background:var(--accent);display:inline-block;flex-shrink:0"></span><div><div style="font-weight:700;font-size:16px;color:var(--accent)"><?= $tdcActiveCount+$pdcActiveCount ?></div><div style="color:var(--muted);font-size:11px">Currently Active</div></div></div>
                <div style="display:flex;align-items:center;gap:8px"><span style="width:12px;height:12px;border-radius:50%;background:var(--red);display:inline-block;flex-shrink:0"></span><div><div style="font-weight:700;font-size:16px;color:var(--red)"><?= array_sum($chartApptCancelled) ?></div><div style="color:var(--muted);font-size:11px">Cancelled/Rejected</div></div></div>
            </div>
        </div>
    </div>
    <div class="panel"><div class="panel-head"><div class="panel-title"><i class="fa-solid fa-chart-bar me-1"></i> Grade Distribution (All Sessions)</div></div>
        <div style="position:relative;height:220px;padding:12px"><canvas id="chartGradeDist"></canvas></div></div>
</div>
<div class="duo-grid" style="margin-bottom:16px">
    <div class="panel"><div class="panel-head"><div class="panel-title"><i class="fa-solid fa-venus-mars me-1"></i> Student Gender Distribution</div></div>
        <div style="display:flex;align-items:center;justify-content:center;padding:16px;gap:24px;min-height:200px">
            <div style="position:relative;width:180px;height:180px;flex-shrink:0"><canvas id="chartGender"></canvas></div>
            <div style="font-size:12px;display:flex;flex-direction:column;gap:12px">
                <div style="display:flex;align-items:center;gap:8px"><span style="width:12px;height:12px;border-radius:50%;background:#60a5fa;display:inline-block;flex-shrink:0"></span><div><div style="font-weight:700;font-size:22px;color:#60a5fa"><?= $maleCount ?></div><div style="color:var(--muted);font-size:11px"><i class="fa-solid fa-mars me-1"></i>Male</div></div></div>
                <div style="display:flex;align-items:center;gap:8px"><span style="width:12px;height:12px;border-radius:50%;background:#f472b6;display:inline-block;flex-shrink:0"></span><div><div style="font-weight:700;font-size:22px;color:#f472b6"><?= $femaleCount ?></div><div style="color:var(--muted);font-size:11px"><i class="fa-solid fa-venus me-1"></i>Female</div></div></div>
                <?php if($otherGenderCount>0): ?><div style="display:flex;align-items:center;gap:8px"><span style="width:12px;height:12px;border-radius:50%;background:var(--muted);display:inline-block;flex-shrink:0"></span><div><div style="font-weight:700;font-size:22px;color:var(--muted)"><?= $otherGenderCount ?></div><div style="color:var(--muted);font-size:11px">Not Specified</div></div></div><?php endif; ?>
            </div>
        </div>
    </div>
    <div class="panel"><div class="panel-head"><div class="panel-title"><i class="fa-solid fa-user-graduate"></i> Recent Student Logins</div></div>
        <table class="tbl"><thead><tr><th>Student</th><th>Status</th><th>⚠</th><th>Time</th></tr></thead><tbody>
        <?php $sLogins=q($conn,"SELECT slh.*,s.fullname FROM student_login_history slh JOIN students s ON s.student_id=slh.student_id ORDER BY slh.login_datetime DESC LIMIT 10");
        while($sl=$sLogins->fetch_assoc()): $fc=$sFailMap[(int)$sl['student_id']]??0; ?>
        <tr><td style="font-size:12px"><?= htmlspecialchars($sl['fullname']) ?></td>
            <td><?= $sl['status']==='success'?'<span class="bkg" style="font-size:10px">✓ OK</span>':'<span class="bkr" style="font-size:10px">✗ Fail</span>' ?></td>
            <td style="text-align:center"><?php if($fc>0): ?><span style="background:rgba(229,85,85,.15);color:var(--red);border-radius:8px;padding:1px 8px;font-size:11px"><?= $fc ?></span><?php else: ?>—<?php endif; ?></td>
            <td style="font-size:10px;color:var(--muted);white-space:nowrap"><?= date('M d, g:i A',strtotime($sl['login_datetime'])) ?></td></tr>
        <?php endwhile; ?></tbody></table>
    </div>
</div>
</div>

<!-- ══ STUDENTS ══════════════════════════════════════════════ -->
<div id="section-students" class="section <?= $activeSection==='students'?'active':'' ?>">
<div class="duo-grid">
<div class="panel">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-trophy" style="color:var(--amber)"></i> Top 5 Students This Month</div></div>
    <table class="tbl"><thead><tr><th>#</th><th>Student</th><th>Avg Grade</th></tr></thead><tbody>
    <?php $topStudents->data_seek(0); $rank=1; if($topStudents->num_rows===0): ?>
    <tr><td colspan="3" style="color:var(--muted);text-align:center;padding:16px">No grades yet.</td></tr>
    <?php else: while($ts=$topStudents->fetch_assoc()): $medals=['🥇','🥈','🥉','4️⃣','5️⃣']; $gc=$ts['avg_grade']>=80?'var(--green)':($ts['avg_grade']>=60?'var(--amber)':'var(--red)'); ?>
    <tr><td><?= $medals[$rank-1]??$rank ?></td><td><?= htmlspecialchars($ts['fullname']) ?></td><td style="color:<?= $gc ?>;font-weight:600"><?= $ts['avg_grade'] ?></td></tr>
    <?php $rank++; endwhile; endif; ?></tbody></table>
</div>
<div class="panel">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-arrow-trend-down" style="color:var(--red)"></i> Low Performing This Month</div></div>
    <table class="tbl"><thead><tr><th>#</th><th>Student</th><th>Avg Grade</th></tr></thead><tbody>
    <?php $lowStudents->data_seek(0); $rank=1; if($lowStudents->num_rows===0): ?>
    <tr><td colspan="3" style="color:var(--muted);text-align:center;padding:16px">No grades yet.</td></tr>
    <?php else: while($ls=$lowStudents->fetch_assoc()): ?>
    <tr><td><?= $rank ?></td><td><?= htmlspecialchars($ls['fullname']) ?></td><td style="color:var(--red);font-weight:600"><?= $ls['avg_grade'] ?></td></tr>
    <?php $rank++; endwhile; endif; ?></tbody></table>
</div>
</div>
<div class="panel">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-user-graduate"></i> All Students</div></div>
    <table class="tbl"><thead><tr><th>Name</th><th>Email</th><th>Mobile</th><th>Verified</th><th>TDC Done</th><th>PDC Done</th><th>TDC Cert</th><th>Joined</th><th></th></tr></thead><tbody>
    <?php $students=q($conn,"SELECT s.*,(SELECT COUNT(*) FROM appointments a JOIN instructor_courses ic ON ic.course_id=a.course_id WHERE a.student_id=s.student_id AND ic.course_type IN('TDC Face-to-Face','TDC Online') AND a.status='completed') AS tdc_done,(SELECT COUNT(*) FROM appointments a JOIN instructor_courses ic ON ic.course_id=a.course_id WHERE a.student_id=s.student_id AND ic.course_type LIKE 'PDC%' AND a.status='completed') AS pdc_done,(SELECT COUNT(*) FROM tdc_certificates WHERE student_id=s.student_id) AS has_cert FROM students s ORDER BY s.created_at DESC");
    if($students->num_rows===0): ?><tr><td colspan="9" style="color:var(--muted);text-align:center;padding:20px">No students.</td></tr>
    <?php else: while($st=$students->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($st['fullname']) ?></td>
        <td style="font-size:11px"><?= htmlspecialchars($st['email']) ?></td>
        <td style="font-size:11px"><?= $st['mobile']?htmlspecialchars($st['mobile']):'—' ?></td>
        <td><?= $st['is_verified']?'<span class="bkg">Yes</span>':'<span class="bkp">No</span>' ?></td>
        <td><?= $st['tdc_done']?'<span class="bkg">✓</span>':'—' ?></td>
        <td><?= $st['pdc_done']?'<span class="bkg">✓</span>':'—' ?></td>
        <td><?= $st['has_cert']?'<span class="cert-badge"><i class="fa-solid fa-certificate"></i> Yes</span>':'—' ?></td>
        <td style="font-size:11px;color:var(--muted)"><?= date('M d, Y',strtotime($st['created_at'])) ?></td>
        <td style="display:flex;gap:4px">
            <a class="bds" style="font-size:11px;text-decoration:none" href="<?= $selfFile ?>?section=student_profile&view_student=<?= $st['student_id'] ?>">View</a>
            <form method="POST" style="display:inline" onsubmit="return confirm('Delete this student?')">
                <input type="hidden" name="action" value="delete_student"><input type="hidden" name="student_id" value="<?= $st['student_id'] ?>">
                <button type="submit" class="ber" style="font-size:11px">Delete</button>
            </form>
        </td>
    </tr>
    <?php endwhile; endif; ?></tbody></table>
</div>
</div>

<!-- Student Profile -->
<div id="section-student_profile" class="section <?= $activeSection==='student_profile'?'active':'' ?>">
<?php if($viewStudentId): $sp=q($conn,"SELECT * FROM students WHERE student_id=$viewStudentId")->fetch_assoc(); ?>
<a href="<?= $selfFile ?>?section=students" class="bds mb-3" style="display:inline-flex;align-items:center;gap:6px"><i class="fa-solid fa-arrow-left"></i> Back to Students</a>
<?php if($sp):
$spIdPath='../assets/uploads/student_ids/';
$spHasTDC=(int)q($conn,"SELECT COUNT(*) AS c FROM tdc_certificates WHERE student_id=$viewStudentId")->fetch_assoc()['c'];
$spHasPDC=(int)q($conn,"SELECT COUNT(*) AS c FROM pdc_certificates WHERE student_id=$viewStudentId")->fetch_assoc()['c'];
$spTDCCerts=q($conn,"SELECT tc.*,i.fullname AS iname FROM tdc_certificates tc JOIN instructors i ON i.instructor_id=tc.issued_by WHERE tc.student_id=$viewStudentId ORDER BY tc.issued_at DESC");
$spPDCCerts=q($conn,"SELECT pc.*,i.fullname AS iname FROM pdc_certificates pc JOIN instructors i ON i.instructor_id=pc.issued_by WHERE pc.student_id=$viewStudentId ORDER BY pc.issued_at DESC");
?>
<div class="panel" style="margin-top:12px">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-user-graduate me-1"></i><?= htmlspecialchars($sp['fullname']) ?></div></div>
    <div style="padding:16px;display:flex;gap:20px;align-items:flex-start;flex-wrap:wrap">
        <div style="width:64px;height:64px;border-radius:50%;background:var(--accent);display:flex;align-items:center;justify-content:center;font-size:26px;font-weight:700;color:#fff;flex-shrink:0"><?= strtoupper(substr($sp['fullname'],0,1)) ?></div>
        <div style="flex:1;min-width:260px;display:grid;grid-template-columns:1fr 1fr;gap:12px">
            <div><span style="font-size:11px;color:var(--muted)">Email</span><div style="font-size:13px"><?= htmlspecialchars($sp['email']) ?></div></div>
            <div><span style="font-size:11px;color:var(--muted)">Mobile</span><div style="font-size:13px"><?= $sp['mobile']?htmlspecialchars($sp['mobile']):'—' ?></div></div>
            <div><span style="font-size:11px;color:var(--muted)">Gender</span><div style="font-size:13px"><?php if(!empty($sp['gender'])): ?><i class="fa-solid fa-<?= $sp['gender']==='Male'?'mars':($sp['gender']==='Female'?'venus':'genderless') ?> me-1" style="color:<?= $sp['gender']==='Male'?'#60a5fa':'#f472b6' ?>"></i><?= htmlspecialchars($sp['gender']) ?><?php else: ?>—<?php endif; ?></div></div>
            <div><span style="font-size:11px;color:var(--muted)">Verified</span><div><?= $sp['is_verified']?'<span class="bkg">Yes</span>':'<span class="bkp">No</span>' ?></div></div>
            <div><span style="font-size:11px;color:var(--muted)">Address</span><div style="font-size:12px"><?= !empty($sp['address'])?htmlspecialchars($sp['address']):'—' ?></div></div>
            <div><span style="font-size:11px;color:var(--muted)">Joined</span><div style="font-size:13px"><?= date('F d, Y',strtotime($sp['created_at'])) ?></div></div>
            <div><span style="font-size:11px;color:var(--muted)">Facebook</span><div style="font-size:12px"><?php if(!empty($sp['fb_link'])): ?><a href="<?= htmlspecialchars($sp['fb_link']) ?>" target="_blank" style="color:#60a5fa;text-decoration:none"><i class="fa-brands fa-facebook me-1"></i>View Profile</a><?php else: ?>—<?php endif; ?></div></div>
            <div><span style="font-size:11px;color:var(--muted)">Certificates</span><div style="display:flex;gap:6px;margin-top:3px;flex-wrap:wrap"><?php if($spHasTDC): ?><span class="cert-badge"><i class="fa-solid fa-certificate"></i> TDC</span><?php endif; ?><?php if($spHasPDC): ?><span class="cert-badge" style="color:var(--green)"><i class="fa-solid fa-certificate"></i> PDC</span><?php endif; ?><?php if(!$spHasTDC&&!$spHasPDC): ?>—<?php endif; ?></div></div>
        </div>
        <div style="flex-shrink:0;text-align:center">
            <div style="font-size:10px;color:var(--muted);margin-bottom:8px"><i class="fa-solid fa-id-card me-1"></i>National ID / Valid ID</div>
            <?php if(!empty($sp['id_photo'])&&file_exists($spIdPath.$sp['id_photo'])): ?>
            <img src="<?= $spIdPath.htmlspecialchars($sp['id_photo']) ?>" style="max-width:200px;max-height:120px;border-radius:8px;border:2px solid var(--border);object-fit:cover;display:block;cursor:pointer;transition:.15s" onclick="openLightbox(this.src,'<?= addslashes(htmlspecialchars($spIdPath.$sp['id_photo'])) ?>')" onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--border)'">
            <div style="display:flex;gap:6px;margin-top:6px;justify-content:center">
                <a href="<?= 'view_image.php?type=student_ids&file='.urlencode($sp['id_photo']).'&back='.urlencode($_SERVER['REQUEST_URI']) ?>" class="bds" style="font-size:11px"><i class="fa-solid fa-eye me-1"></i>View</a>
            </div>
            <?php else: ?>
            <div style="width:160px;height:100px;border:2px dashed var(--border);border-radius:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px">
                <i class="fa-solid fa-id-card" style="font-size:22px;color:var(--border)"></i>
                <span style="font-size:11px;color:var(--muted)">No ID uploaded</span>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Certificates -->
<?php if($spHasTDC||$spHasPDC): ?>
<div class="panel">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-certificate" style="color:var(--amber)"></i> Certificates Issued</div></div>
    <?php if($spTDCCerts->num_rows>0): ?>
    <div style="padding:8px 16px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--amber);border-bottom:1px solid var(--border);background:var(--bg3)">TDC Certificates</div>
    <table class="tbl"><thead><tr><th>Cert Number</th><th>Issued By</th><th>Delivery</th><th>Date</th><th>Image</th></tr></thead><tbody>
    <?php while($tc=$spTDCCerts->fetch_assoc()): ?>
    <tr>
        <td><?= $tc['cert_number']?htmlspecialchars($tc['cert_number']):'—' ?></td>
        <td><?= htmlspecialchars($tc['iname']) ?></td>
        <td><?= ($tc['delivery_method']??'face_to_face')==='online'?'<span class="bkb">Online</span>':'<span class="bkd">Face-to-Face</span>' ?></td>
        <td><?= date('M d, Y',strtotime($tc['issued_at'])) ?></td>
        <td><?php if(!empty($tc['cert_image'])&&file_exists('../assets/uploads/certs/'.$tc['cert_image'])): ?>
            <a href="<?= 'view_image.php?type=certs&file='.urlencode($tc['cert_image']).'&back='.urlencode($_SERVER['REQUEST_URI']) ?>" class="bds" style="font-size:11px"><i class="fa-solid fa-eye me-1"></i>View</a>
        <?php else: ?>—<?php endif; ?></td>
    </tr>
    <?php endwhile; ?></tbody></table>
    <?php endif; ?>
    <?php if($spPDCCerts->num_rows>0): ?>
    <div style="padding:8px 16px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--green);border-bottom:1px solid var(--border);background:var(--bg3)">PDC Certificates</div>
    <table class="tbl"><thead><tr><th>Cert Number</th><th>Issued By</th><th>Delivery</th><th>Date</th><th>Image</th></tr></thead><tbody>
    <?php while($pc=$spPDCCerts->fetch_assoc()): ?>
    <tr>
        <td><?= $pc['cert_number']?htmlspecialchars($pc['cert_number']):'—' ?></td>
        <td><?= htmlspecialchars($pc['iname']) ?></td>
        <td><?= ($pc['delivery_method']??'face_to_face')==='online'?'<span class="bkb">Online</span>':'<span class="bkd">Face-to-Face</span>' ?></td>
        <td><?= date('M d, Y',strtotime($pc['issued_at'])) ?></td>
        <td><?php if(!empty($pc['cert_image'])&&file_exists('../assets/uploads/certs/'.$pc['cert_image'])): ?>
            <a href="<?= 'view_image.php?type=certs&file='.urlencode($pc['cert_image']).'&back='.urlencode($_SERVER['REQUEST_URI']) ?>" class="bds" style="font-size:11px"><i class="fa-solid fa-eye me-1"></i>View</a>
        <?php else: ?>—<?php endif; ?></td>
    </tr>
    <?php endwhile; ?></tbody></table>
    <?php endif; ?>
</div>
<?php endif; ?>

<!-- Session Grades -->
<?php $studentGrades=q($conn,"SELECT sess.*,ic.course_type,DATE_FORMAT(sess.session_date,'%b %d') AS date_fmt FROM sessions sess JOIN appointments a ON a.appointment_id=sess.appointment_id JOIN instructor_courses ic ON ic.course_id=a.course_id WHERE sess.student_id=$viewStudentId AND sess.grade IS NOT NULL ORDER BY sess.session_date DESC LIMIT 20");
$avgGradeProfile=q($conn,"SELECT ROUND(AVG(grade),1) AS ag FROM sessions WHERE student_id=$viewStudentId AND grade IS NOT NULL AND DATE_FORMAT(session_date,'%Y-%m')='$thisMonth'")->fetch_assoc()['ag'];
if($studentGrades->num_rows>0): ?>
<div class="panel">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-graduation-cap"></i> Session Grades</div>
        <?php if($avgGradeProfile): $gc=$avgGradeProfile>=80?'var(--green)':($avgGradeProfile>=60?'var(--amber)':'var(--red)'); ?>
        <div style="font-size:12px">This month avg: <strong style="color:<?= $gc ?>"><?= $avgGradeProfile ?>/100</strong></div>
        <?php endif; ?></div>
    <table class="tbl"><thead><tr><th>Date</th><th>Course</th><th>Grade</th><th>Improvements</th></tr></thead><tbody>
    <?php while($sg=$studentGrades->fetch_assoc()): $gc=$sg['grade']>=80?'var(--green)':($sg['grade']>=60?'var(--amber)':'var(--red)'); ?>
    <tr><td><?= $sg['date_fmt'] ?></td>
        <td><span class="tag <?= $cColors[$sg['course_type']]??'tag-gray' ?>"><?= htmlspecialchars($sg['course_type']) ?></span></td>
        <td><strong style="color:<?= $gc ?>"><?= $sg['grade'] ?>/100</strong><div class="grade-bar"><div class="grade-fill" style="width:<?= $sg['grade'] ?>%;background:<?= $gc ?>"></div></div></td>
        <td style="font-size:11px;color:var(--muted)"><?= $sg['improvements']?htmlspecialchars($sg['improvements']):'—' ?></td></tr>
    <?php endwhile; ?></tbody></table>
</div>
<?php endif; ?>

<!-- Appointment History -->
<div class="panel">
    <div class="panel-head"><div class="panel-title">Appointment History</div></div>
    <table class="tbl"><thead><tr><th>Course</th><th>Instructor</th><th>Start Date</th><th>Hours</th><th>Status</th></tr></thead><tbody>
    <?php $sappts=q($conn,"SELECT a.*,ic.course_type,i.fullname AS iname,(SELECT SUM(hours_covered) FROM sessions WHERE appointment_id=a.appointment_id AND status='completed') AS hdone FROM appointments a JOIN instructor_courses ic ON ic.course_id=a.course_id JOIN instructors i ON i.instructor_id=a.instructor_id WHERE a.student_id=$viewStudentId ORDER BY a.requested_at DESC");
    if($sappts->num_rows===0): ?><tr><td colspan="5" style="color:var(--muted);text-align:center;padding:16px">No appointments.</td></tr>
    <?php else: while($sa=$sappts->fetch_assoc()): $tc=$cColors[$sa['course_type']]??'tag-gray'; ?>
    <tr><td><span class="tag <?= $tc ?>"><?= htmlspecialchars($sa['course_type']) ?></span></td>
        <td><?= htmlspecialchars($sa['iname']) ?></td>
        <td><?= date('M d, Y',strtotime($sa['start_date'])) ?></td>
        <td><?= (float)($sa['hdone']??0) ?>/<?= $sa['total_hours'] ?> hrs</td>
        <td><span class="<?= $scmS[$sa['status']]??'bkd' ?>"><?= $scmL[$sa['status']]??$sa['status'] ?></span></td></tr>
    <?php endwhile; endif; ?></tbody></table>
</div>
<?php endif; endif; ?>
</div>

<!-- ══ INSTRUCTORS ════════════════════════════════════════════ -->
<div id="section-instructors" class="section <?= $activeSection==='instructors'?'active':'' ?>">
<div class="duo-grid">
<div class="panel">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-trophy" style="color:var(--amber)"></i> Top 5 Rated Instructors</div></div>
    <table class="tbl"><thead><tr><th>#</th><th>Instructor</th><th>Rating</th><th>Reviews</th></tr></thead><tbody>
    <?php $topInstructors->data_seek(0); $rank=1; if($topInstructors->num_rows===0): ?>
    <tr><td colspan="4" style="color:var(--muted);text-align:center;padding:16px">No ratings yet.</td></tr>
    <?php else: while($ti=$topInstructors->fetch_assoc()): $medals=['🥇','🥈','🥉','4️⃣','5️⃣']; ?>
    <tr><td><?= $medals[$rank-1]??$rank ?></td><td><?= htmlspecialchars($ti['fullname']) ?></td><td style="color:var(--amber)">★ <?= $ti['avg_r'] ?></td><td><?= $ti['cnt'] ?></td></tr>
    <?php $rank++; endwhile; endif; ?></tbody></table>
</div>
<div class="panel">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-arrow-trend-down" style="color:var(--red)"></i> Low Rated Instructors</div></div>
    <table class="tbl"><thead><tr><th>#</th><th>Instructor</th><th>Rating</th></tr></thead><tbody>
    <?php $lowInstructors->data_seek(0); $rank=1; if($lowInstructors->num_rows===0): ?>
    <tr><td colspan="3" style="color:var(--muted);text-align:center;padding:16px">No ratings yet.</td></tr>
    <?php else: while($li=$lowInstructors->fetch_assoc()): ?>
    <tr><td><?= $rank ?></td><td><?= htmlspecialchars($li['fullname']) ?></td><td style="color:var(--red)">★ <?= $li['avg_r'] ?></td></tr>
    <?php $rank++; endwhile; endif; ?></tbody></table>
</div>
</div>
<div class="panel mb-3">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-plus"></i> Add Instructor</div></div>
    <div style="padding:16px">
    <form method="POST" style="display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:10px;align-items:end">
        <input type="hidden" name="action" value="add_instructor">
        <div><label class="form-label">Full Name</label><input type="text" name="fullname" class="di" required></div>
        <div><label class="form-label">Email</label><input type="email" name="email" class="di" required></div>
        <div><label class="form-label">Password</label><input type="password" name="password" class="di" required></div>
        <button type="submit" class="be" style="height:36px"><i class="fa-solid fa-plus"></i> Add</button>
    </form>
    </div>
</div>
<div class="panel">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-chalkboard-user"></i> All Instructors</div></div>
    <table class="tbl"><thead><tr><th>Name</th><th>Email</th><th>Mobile</th><th>Courses</th><th>Avg Rating</th><th>Appointments</th><th>Certified</th><th></th></tr></thead><tbody>
    <?php $instrs=q($conn,"SELECT i.*,(SELECT COUNT(*) FROM instructor_courses WHERE instructor_id=i.instructor_id AND is_active=1) AS course_cnt,(SELECT ROUND(AVG(rating),1) FROM instructor_ratings WHERE instructor_id=i.instructor_id) AS avg_r,(SELECT COUNT(*) FROM appointments WHERE instructor_id=i.instructor_id) AS appt_cnt FROM instructors i ORDER BY i.fullname ASC");
    if($instrs->num_rows===0): ?><tr><td colspan="8" style="color:var(--muted);text-align:center;padding:20px">No instructors.</td></tr>
    <?php else: while($ins=$instrs->fetch_assoc()): $certOk=!empty($ins['certificate_image'])&&file_exists($uploadDir.$ins['certificate_image']); ?>
    <tr>
        <td><?= htmlspecialchars($ins['fullname']) ?></td>
        <td style="font-size:11px"><?= htmlspecialchars($ins['email']) ?></td>
        <td style="font-size:11px"><?= isset($ins['mobile'])&&$ins['mobile']?htmlspecialchars($ins['mobile']):'—' ?></td>
        <td><?= $ins['course_cnt'] ?> active</td>
        <td><?= $ins['avg_r']?'★ '.$ins['avg_r']:'—' ?></td>
        <td><?= $ins['appt_cnt'] ?></td>
        <td><?= $certOk?'<span class="cert-badge"><i class="fa-solid fa-certificate"></i> Yes</span>':'—' ?></td>
        <td style="display:flex;gap:4px">
            <a class="bds" style="font-size:11px;text-decoration:none" href="<?= $selfFile ?>?section=instructor_profile&view_instructor=<?= $ins['instructor_id'] ?>">View</a>
            <form method="POST" style="display:inline" onsubmit="return confirm('Delete this instructor?')">
                <input type="hidden" name="action" value="delete_instructor"><input type="hidden" name="instructor_id" value="<?= $ins['instructor_id'] ?>">
                <button type="submit" class="ber" style="font-size:11px">Delete</button>
            </form>
        </td>
    </tr>
    <?php endwhile; endif; ?></tbody></table>
</div>
</div>

<!-- Instructor Profile -->
<div id="section-instructor_profile" class="section <?= $activeSection==='instructor_profile'?'active':'' ?>">
<?php if($viewInstructorId):
    $ip=q($conn,"SELECT * FROM instructors WHERE instructor_id=$viewInstructorId")->fetch_assoc();
?>
<a href="<?= $selfFile ?>?section=instructors" class="bds" style="display:inline-flex;align-items:center;gap:6px;margin-bottom:12px"><i class="fa-solid fa-arrow-left"></i> Back to Instructors</a>
<?php if($ip):
    $certOk=!empty($ip['certificate_image'])&&file_exists($uploadDir.$ip['certificate_image']);
    $instrRating=q($conn,"SELECT ROUND(AVG(rating),1) AS r,COUNT(*) AS c FROM instructor_ratings WHERE instructor_id=$viewInstructorId")->fetch_assoc();
    $ipTotalStudents=(int)q($conn,"SELECT COUNT(DISTINCT student_id) AS c FROM appointments WHERE instructor_id=$viewInstructorId AND status IN('approved','in_progress','completed')")->fetch_assoc()['c'];
    $ipTotalHrs=(float)q($conn,"SELECT COALESCE(SUM(hours_covered),0) AS h FROM sessions WHERE instructor_id=$viewInstructorId AND status='completed'")->fetch_assoc()['h'];
    $ipTotalSess=(int)q($conn,"SELECT COUNT(*) AS c FROM sessions WHERE instructor_id=$viewInstructorId AND status='completed'")->fetch_assoc()['c'];
    $ipTDCCerts=(int)q($conn,"SELECT COUNT(*) AS c FROM tdc_certificates WHERE issued_by=$viewInstructorId")->fetch_assoc()['c'];
    $ipPDCCerts=(int)q($conn,"SELECT COUNT(*) AS c FROM pdc_certificates WHERE issued_by=$viewInstructorId")->fetch_assoc()['c'];
?>
<!-- Info Card -->
<div class="panel" style="margin-top:12px;margin-bottom:16px">
    <div class="panel-head">
        <div class="panel-title">
            <div style="width:36px;height:36px;border-radius:50%;background:var(--accent);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;color:#fff;flex-shrink:0"><?= strtoupper(substr($ip['fullname'],0,1)) ?></div>
            <?= htmlspecialchars($ip['fullname']) ?>
        </div>
        <?php if($instrRating['r']): ?><div style="color:var(--amber)">★ <?= $instrRating['r'] ?> <span style="font-size:11px;color:var(--muted)">(<?= $instrRating['c'] ?> reviews)</span></div><?php endif; ?>
    </div>
    <div style="padding:16px;display:flex;gap:20px;align-items:flex-start;flex-wrap:wrap">
        <!-- Info grid -->
        <div style="flex:1;min-width:260px;display:grid;grid-template-columns:1fr 1fr;gap:12px">
            <div><span style="font-size:11px;color:var(--muted)">Email</span><div style="font-size:13px"><?= htmlspecialchars($ip['email']) ?></div></div>
            <div><span style="font-size:11px;color:var(--muted)">Mobile</span><div style="font-size:13px"><?= isset($ip['mobile'])&&$ip['mobile']?htmlspecialchars($ip['mobile']):'—' ?></div></div>
            <div><span style="font-size:11px;color:var(--muted)">Joined</span><div style="font-size:13px"><?= date('F d, Y',strtotime($ip['created_at'])) ?></div></div>
            <div><span style="font-size:11px;color:var(--muted)">Certificates Issued</span>
                <div style="display:flex;gap:6px;margin-top:3px;flex-wrap:wrap">
                    <?php if($ipTDCCerts): ?><span class="cert-badge"><i class="fa-solid fa-certificate"></i> TDC: <?= $ipTDCCerts ?></span><?php endif; ?>
                    <?php if($ipPDCCerts): ?><span class="cert-badge" style="color:var(--green)"><i class="fa-solid fa-certificate"></i> PDC: <?= $ipPDCCerts ?></span><?php endif; ?>
                    <?php if(!$ipTDCCerts&&!$ipPDCCerts): ?>—<?php endif; ?>
                </div>
            </div>
            <?php if(isset($ip['bio'])&&$ip['bio']): ?>
            <div style="grid-column:1/-1"><span style="font-size:11px;color:var(--muted)">Bio</span><div style="font-size:12px;margin-top:2px"><?= nl2br(htmlspecialchars($ip['bio'])) ?></div></div>
            <?php endif; ?>
            <?php if($certOk): ?>
            <div style="grid-column:1/-1">
                <span style="font-size:11px;color:var(--muted)">Instructor Certificate</span>
                <div style="margin-top:6px">
                    <img src="<?= htmlspecialchars('../assets/uploads/certificates/'.$ip['certificate_image']) ?>" style="max-height:100px;border-radius:6px;border:1px solid var(--border);display:block;margin-bottom:6px">
                    <a href="<?= 'view_image.php?type=certificates&file='.urlencode($ip['certificate_image']).'&back='.urlencode($_SERVER['REQUEST_URI']) ?>" class="bds" style="font-size:11px"><i class="fa-solid fa-eye me-1"></i>View</a>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <!-- Stats -->
        <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-start">
            <div style="background:var(--bg3);border-radius:10px;padding:12px 18px;text-align:center;min-width:80px"><div style="font-size:20px;font-weight:700;color:var(--accent)"><?= $ipTotalStudents ?></div><div style="font-size:10px;color:var(--muted)">Students</div></div>
            <div style="background:var(--bg3);border-radius:10px;padding:12px 18px;text-align:center;min-width:80px"><div style="font-size:20px;font-weight:700;color:var(--green)"><?= $ipTotalHrs ?>h</div><div style="font-size:10px;color:var(--muted)">Hours Taught</div></div>
            <div style="background:var(--bg3);border-radius:10px;padding:12px 18px;text-align:center;min-width:80px"><div style="font-size:20px;font-weight:700;color:var(--text)"><?= $ipTotalSess ?></div><div style="font-size:10px;color:var(--muted)">Sessions</div></div>
            <?php if($instrRating['r']): ?><div style="background:var(--bg3);border-radius:10px;padding:12px 18px;text-align:center;min-width:80px"><div style="font-size:20px;font-weight:700;color:var(--amber)">★ <?= $instrRating['r'] ?></div><div style="font-size:10px;color:var(--muted)">Avg Rating</div></div><?php endif; ?>
        </div>
    </div>
</div>

<!-- Courses Offered -->
<div class="panel" style="margin-bottom:16px">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-book-open"></i> Courses Offered</div></div>
    <table class="tbl"><thead><tr><th>Course Type</th><th>Price/Hr</th><th>Student Limit/Date</th><th>Status</th></tr></thead><tbody>
    <?php $icr=q($conn,"SELECT * FROM instructor_courses WHERE instructor_id=$viewInstructorId ORDER BY course_type");
    while($c=$icr->fetch_assoc()): $tc=$cColors[$c['course_type']]??'tag-gray'; ?>
    <tr><td><span class="tag <?= $tc ?>"><?= htmlspecialchars($c['course_type']) ?></span></td>
        <td>₱<?= number_format($c['price_per_hr'],2) ?></td>
        <td><?php if(!empty($c['student_limit'])): ?><span style="background:rgba(124,106,247,.15);color:var(--accent);padding:2px 10px;border-radius:20px;font-size:11px"><i class="fa-solid fa-users me-1"></i><?= $c['student_limit'] ?>/date</span><?php else: ?><span style="color:var(--muted);font-size:11px">Unlimited</span><?php endif; ?></td>
        <td><?= $c['is_active']?'<span class="bkg">Active</span>':'<span class="bkd">Inactive</span>' ?></td></tr>
    <?php endwhile; ?></tbody></table>
</div>

<!-- Certificates Issued -->
<?php
$ipTDCCertsRes=q($conn,"SELECT tc.*,s.fullname AS sname FROM tdc_certificates tc JOIN students s ON s.student_id=tc.student_id WHERE tc.issued_by=$viewInstructorId ORDER BY tc.issued_at DESC");
$ipPDCCertsRes=q($conn,"SELECT pc.*,s.fullname AS sname FROM pdc_certificates pc JOIN students s ON s.student_id=pc.student_id WHERE pc.issued_by=$viewInstructorId ORDER BY pc.issued_at DESC");
if($ipTDCCertsRes->num_rows>0||$ipPDCCertsRes->num_rows>0):
?>
<div class="panel" style="margin-bottom:16px">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-certificate" style="color:var(--amber)"></i> Certificates Issued</div></div>
    <?php if($ipTDCCertsRes->num_rows>0): ?>
    <div style="padding:8px 16px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--amber);border-bottom:1px solid var(--border);background:var(--bg3)">TDC Certificates</div>
    <table class="tbl"><thead><tr><th>Student</th><th>Cert Number</th><th>Delivery</th><th>Date</th><th>Image</th></tr></thead><tbody>
    <?php while($tc=$ipTDCCertsRes->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($tc['sname']) ?></td>
        <td><?= $tc['cert_number']?htmlspecialchars($tc['cert_number']):'—' ?></td>
        <td><?= ($tc['delivery_method']??'face_to_face')==='online'?'<span class="bkb">Online</span>':'<span class="bkd">Face-to-Face</span>' ?></td>
        <td><?= date('M d, Y',strtotime($tc['issued_at'])) ?></td>
        <td><?php if(!empty($tc['cert_image'])&&file_exists('../assets/uploads/certs/'.$tc['cert_image'])): ?>
            <a href="<?= 'view_image.php?type=certs&file='.urlencode($tc['cert_image']).'&back='.urlencode($_SERVER['REQUEST_URI']) ?>" class="bds" style="font-size:11px"><i class="fa-solid fa-eye me-1"></i>View</a>
        <?php else: ?>—<?php endif; ?></td>
    </tr>
    <?php endwhile; ?></tbody></table>
    <?php endif; ?>
    <?php if($ipPDCCertsRes->num_rows>0): ?>
    <div style="padding:8px 16px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--green);border-bottom:1px solid var(--border);background:var(--bg3)">PDC Certificates</div>
    <table class="tbl"><thead><tr><th>Student</th><th>Cert Number</th><th>Delivery</th><th>Date</th><th>Image</th></tr></thead><tbody>
    <?php while($pc=$ipPDCCertsRes->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($pc['sname']) ?></td>
        <td><?= $pc['cert_number']?htmlspecialchars($pc['cert_number']):'—' ?></td>
        <td><?= ($pc['delivery_method']??'face_to_face')==='online'?'<span class="bkb">Online</span>':'<span class="bkd">Face-to-Face</span>' ?></td>
        <td><?= date('M d, Y',strtotime($pc['issued_at'])) ?></td>
        <td><?php if(!empty($pc['cert_image'])&&file_exists('../assets/uploads/certs/'.$pc['cert_image'])): ?>
            <a href="<?= 'view_image.php?type=certs&file='.urlencode($pc['cert_image']).'&back='.urlencode($_SERVER['REQUEST_URI']) ?>" class="bds" style="font-size:11px"><i class="fa-solid fa-eye me-1"></i>View</a>
        <?php else: ?>—<?php endif; ?></td>
    </tr>
    <?php endwhile; ?></tbody></table>
    <?php endif; ?>
</div>
<?php endif; ?>

<!-- Appointment History -->
<div class="panel" style="margin-bottom:16px">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-calendar-check"></i> Appointment History</div></div>
    <table class="tbl"><thead><tr><th>Student</th><th>Course</th><th>Start Date</th><th>Hours Done</th><th>TDC Grade</th><th>Status</th></tr></thead><tbody>
    <?php $ipAppts=q($conn,"SELECT a.*,s.fullname AS sname,ic.course_type,(SELECT SUM(hours_covered) FROM sessions WHERE appointment_id=a.appointment_id AND status='completed') AS hdone FROM appointments a JOIN students s ON s.student_id=a.student_id JOIN instructor_courses ic ON ic.course_id=a.course_id WHERE a.instructor_id=$viewInstructorId ORDER BY a.requested_at DESC");
    if($ipAppts->num_rows===0): ?><tr><td colspan="6" style="color:var(--muted);text-align:center;padding:20px">No appointments yet.</td></tr>
    <?php else: while($ap=$ipAppts->fetch_assoc()): $tc=$cColors[$ap['course_type']]??'tag-gray'; $gc=isset($ap['tdc_overall_grade'])&&$ap['tdc_overall_grade']?($ap['tdc_overall_grade']>=80?'var(--green)':($ap['tdc_overall_grade']>=60?'var(--amber)':'var(--red)')):''; ?>
    <tr>
        <td><?= htmlspecialchars($ap['sname']) ?></td>
        <td><span class="tag <?= $tc ?>"><?= htmlspecialchars($ap['course_type']) ?></span></td>
        <td><?= date('M d, Y',strtotime($ap['start_date'])) ?></td>
        <td><?= (float)($ap['hdone']??0) ?>/<?= $ap['total_hours'] ?> hrs</td>
        <td><?= ($gc&&$ap['tdc_overall_grade'])?'<strong style="color:'.$gc.'">'.$ap['tdc_overall_grade'].'/100</strong>':'—' ?></td>
        <td><span class="<?= $scmS[$ap['status']]??'bkd' ?>"><?= $scmL[$ap['status']]??$ap['status'] ?></span></td>
    </tr>
    <?php endwhile; endif; ?></tbody></table>
</div>

<!-- Student Reviews -->
<?php $ipReviews=q($conn,"SELECT ir.*,s.fullname AS sname,ic.course_type FROM instructor_ratings ir JOIN students s ON s.student_id=ir.student_id JOIN appointments a ON a.appointment_id=ir.appointment_id JOIN instructor_courses ic ON ic.course_id=a.course_id WHERE ir.instructor_id=$viewInstructorId ORDER BY ir.rated_at DESC");
if($ipReviews->num_rows>0): ?>
<div class="panel" style="margin-bottom:16px">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-star" style="color:var(--amber)"></i> Student Reviews</div></div>
    <?php while($rv=$ipReviews->fetch_assoc()): ?>
    <div style="padding:12px 16px;border-bottom:1px solid var(--border)">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px">
            <div style="font-size:12px;font-weight:600"><?= htmlspecialchars($rv['sname']) ?></div>
            <span class="tag <?= $cColors[$rv['course_type']]??'tag-gray' ?>"><?= htmlspecialchars($rv['course_type']) ?></span>
            <div style="color:var(--amber);font-size:12px;margin-left:auto"><?php for($i=1;$i<=5;$i++) echo $i<=$rv['rating']?'★':'☆'; ?></div>
            <div style="font-size:10px;color:var(--muted)"><?= date('M d, Y',strtotime($rv['rated_at'])) ?></div>
        </div>
        <?php if($rv['comment']): ?><div style="font-size:12px;color:var(--muted)"><?= nl2br(htmlspecialchars($rv['comment'])) ?></div><?php endif; ?>
    </div>
    <?php endwhile; ?>
</div>
<?php endif; ?>

<?php endif; endif; ?>
</div>

<!-- ══ APPOINTMENTS ══════════════════════════════════════════ -->
<div id="section-appointments" class="section <?= $activeSection==='appointments'?'active':'' ?>">
<div class="panel">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-calendar-check"></i> All Appointments</div></div>
    <table class="tbl"><thead><tr><th>Student</th><th>Instructor</th><th>Course</th><th>Start Date</th><th>Hours</th><th>Price</th><th>TDC Grade</th><th>Status</th><th>Actions</th></tr></thead><tbody>
    <?php $apts=q($conn,"SELECT a.*,s.fullname AS sname,i.fullname AS iname,ic.course_type,(SELECT SUM(hours_covered) FROM sessions WHERE appointment_id=a.appointment_id AND status='completed') AS hdone FROM appointments a JOIN students s ON s.student_id=a.student_id JOIN instructors i ON i.instructor_id=a.instructor_id JOIN instructor_courses ic ON ic.course_id=a.course_id ORDER BY a.requested_at DESC");
    if($apts->num_rows===0): ?><tr><td colspan="9" style="color:var(--muted);text-align:center;padding:20px">No appointments.</td></tr>
    <?php else: while($ap=$apts->fetch_assoc()): $tc=$cColors[$ap['course_type']]??'tag-gray'; ?>
    <tr>
        <td><?= htmlspecialchars($ap['sname']) ?></td>
        <td><?= htmlspecialchars($ap['iname']) ?></td>
        <td><span class="tag <?= $tc ?>"><?= htmlspecialchars($ap['course_type']) ?></span></td>
        <td><?= date('M d, Y',strtotime($ap['start_date'])) ?></td>
        <td><?= (float)($ap['hdone']??0) ?>/<?= $ap['total_hours'] ?> hrs</td>
        <td>₱<?= number_format($ap['total_price'],2) ?></td>
        <td><?php if(isset($ap['tdc_overall_grade'])&&$ap['tdc_overall_grade']): $gc=$ap['tdc_overall_grade']>=80?'var(--green)':($ap['tdc_overall_grade']>=60?'var(--amber)':'var(--red)'); ?><strong style="color:<?= $gc ?>"><?= $ap['tdc_overall_grade'] ?>/100</strong><?php else: ?>—<?php endif; ?></td>
        <td><span class="<?= $scmS[$ap['status']]??'bkd' ?>"><?= $scmL[$ap['status']]??$ap['status'] ?></span></td>
        <td style="display:flex;gap:4px;flex-wrap:wrap">
            <?php if(in_array($ap['status'],['approved','in_progress'])): ?>
            <form method="POST" onsubmit="return confirm('Force-complete?')" style="display:inline">
                <input type="hidden" name="action" value="force_complete"><input type="hidden" name="appointment_id" value="<?= $ap['appointment_id'] ?>">
                <button type="submit" class="bds" style="font-size:11px">Force Complete</button>
            </form>
            <?php endif; ?>
            <form method="POST" onsubmit="return confirm('Permanently delete?')" style="display:inline">
                <input type="hidden" name="action" value="delete_appointment"><input type="hidden" name="appointment_id" value="<?= $ap['appointment_id'] ?>">
                <button type="submit" class="ber" style="font-size:11px"><i class="fa-solid fa-trash"></i></button>
            </form>
        </td>
    </tr>
    <?php endwhile; endif; ?></tbody></table>
</div>
</div>

<!-- ══ CERTIFICATES ══════════════════════════════════════════ -->
<div id="section-certificates" class="section <?= $activeSection==='certificates'?'active':'' ?>">
<div class="panel">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-certificate"></i> TDC Certificates Issued</div></div>
    <table class="tbl"><thead><tr><th>Student</th><th>Issued By</th><th>Cert Number</th><th>Delivery</th><th>Issued At</th><th>Cert Image</th></tr></thead><tbody>
    <?php $certs=q($conn,"SELECT tc.*,s.fullname AS sname,i.fullname AS iname FROM tdc_certificates tc JOIN students s ON s.student_id=tc.student_id JOIN instructors i ON i.instructor_id=tc.issued_by ORDER BY tc.issued_at DESC");
    if($certs->num_rows===0): ?><tr><td colspan="6" style="color:var(--muted);text-align:center;padding:20px">No TDC certificates yet.</td></tr>
    <?php else: while($c=$certs->fetch_assoc()): $dm=$c['delivery_method']??'face_to_face'; ?>
    <tr>
        <td><?= htmlspecialchars($c['sname']) ?></td>
        <td><?= htmlspecialchars($c['iname']) ?></td>
        <td><?= $c['cert_number']?htmlspecialchars($c['cert_number']):'—' ?></td>
        <td><?= $dm==='online'?'<span class="bkb"><i class="fa-solid fa-globe me-1"></i>Online</span>':'<span class="bkd"><i class="fa-solid fa-handshake me-1"></i>Face-to-Face</span>' ?></td>
        <td><?= date('M d, Y',strtotime($c['issued_at'])) ?></td>
        <td style="white-space:nowrap"><?php if(!empty($c['cert_image'])&&file_exists('../assets/uploads/certs/'.$c['cert_image'])): ?><a href="<?= 'view_image.php?type=certs&file='.urlencode($c['cert_image']).'&back='.urlencode($_SERVER['REQUEST_URI']) ?>" class="bds" style="font-size:11px"><i class="fa-solid fa-eye"></i> View</a><?php else: ?>—<?php endif; ?></td>
    </tr>
    <?php endwhile; endif; ?></tbody></table>
</div>
<?php $pdcCerts=q($conn,"SELECT pc.*,s.fullname AS sname,i.fullname AS iname FROM pdc_certificates pc JOIN students s ON s.student_id=pc.student_id JOIN instructors i ON i.instructor_id=pc.issued_by ORDER BY pc.issued_at DESC");
if($pdcCerts->num_rows>0): ?>
<div class="panel">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-certificate" style="color:var(--green)"></i> PDC Certificates Issued</div></div>
    <table class="tbl"><thead><tr><th>Student</th><th>Issued By</th><th>Cert Number</th><th>Delivery</th><th>Issued At</th><th>Cert Image</th></tr></thead><tbody>
    <?php while($c=$pdcCerts->fetch_assoc()): $dm=$c['delivery_method']??'face_to_face'; ?>
    <tr>
        <td><?= htmlspecialchars($c['sname']) ?></td>
        <td><?= htmlspecialchars($c['iname']) ?></td>
        <td><?= $c['cert_number']?htmlspecialchars($c['cert_number']):'—' ?></td>
        <td><?= $dm==='online'?'<span class="bkb"><i class="fa-solid fa-globe me-1"></i>Online</span>':'<span class="bkd"><i class="fa-solid fa-handshake me-1"></i>Face-to-Face</span>' ?></td>
        <td><?= date('M d, Y',strtotime($c['issued_at'])) ?></td>
        <td style="white-space:nowrap"><?php if(!empty($c['cert_image'])&&file_exists('../assets/uploads/certs/'.$c['cert_image'])): ?><a href="<?= 'view_image.php?type=certs&file='.urlencode($c['cert_image']).'&back='.urlencode($_SERVER['REQUEST_URI']) ?>" class="bds" style="font-size:11px"><i class="fa-solid fa-eye"></i> View</a><?php else: ?>—<?php endif; ?></td>
    </tr>
    <?php endwhile; ?></tbody></table>
</div>
<?php endif; ?>
</div>

<!-- ══ RANKINGS ══════════════════════════════════════════════ -->
<div id="section-rankings" class="section <?= $activeSection==='rankings'?'active':'' ?>">
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px">
    <div style="font-size:13px;font-weight:600"><i class="fa-solid fa-ranking-star me-2" style="color:var(--amber)"></i>Rankings – <?= date('F Y') ?></div>
    <small style="color:var(--muted);font-size:11px"><i class="fa-solid fa-rotate me-1"></i>Student grades reset monthly</small>
</div>
<div style="font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px">Instructors – by Rating</div>
<div class="duo-grid">
<div class="panel">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-trophy" style="color:var(--amber)"></i> Top 5 Highest Rated</div><span style="font-size:10px;color:var(--muted)">All-time</span></div>
    <table class="tbl"><thead><tr><th>#</th><th>Instructor</th><th>Avg Rating</th><th>Reviews</th><th>Sessions Done</th></tr></thead><tbody>
    <?php $topInstructors->data_seek(0); $rank=1; if($topInstructors->num_rows===0): ?>
    <tr><td colspan="5" style="color:var(--muted);text-align:center;padding:20px">No ratings yet.</td></tr>
    <?php else: $medals=['🥇','🥈','🥉','4️⃣','5️⃣']; while($ti=$topInstructors->fetch_assoc()): ?>
    <tr><td><span class="rank-medal"><?= $medals[$rank-1]??$rank ?></span></td>
        <td><a href="<?= $selfFile ?>?section=instructor_profile&view_instructor=<?= $ti['instructor_id'] ?>" style="color:var(--text);text-decoration:none"><?= htmlspecialchars($ti['fullname']) ?></a></td>
        <td><span style="color:var(--amber);font-weight:600">★ <?= $ti['avg_r'] ?></span><div style="height:4px;background:var(--border);border-radius:2px;margin-top:3px;width:80px"><div style="height:4px;background:var(--amber);border-radius:2px;width:<?= round($ti['avg_r']/5*100) ?>%"></div></div></td>
        <td style="color:var(--muted)"><?= $ti['cnt'] ?></td><td style="color:var(--muted)"><?= $ti['done'] ?></td></tr>
    <?php $rank++; endwhile; endif; ?></tbody></table>
</div>
<div class="panel">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-arrow-trend-down" style="color:var(--red)"></i> Top 5 Lowest Rated</div><span style="font-size:10px;color:var(--muted)">All-time · min 1 review</span></div>
    <table class="tbl"><thead><tr><th>#</th><th>Instructor</th><th>Avg Rating</th><th>Reviews</th></tr></thead><tbody>
    <?php $lowInstructors->data_seek(0); $rank=1; if($lowInstructors->num_rows===0): ?>
    <tr><td colspan="4" style="color:var(--muted);text-align:center;padding:20px">No ratings yet.</td></tr>
    <?php else: while($li=$lowInstructors->fetch_assoc()): ?>
    <tr><td><span style="color:var(--muted);font-size:12px"><?= $rank ?></span></td>
        <td><a href="<?= $selfFile ?>?section=instructor_profile&view_instructor=<?= $li['instructor_id'] ?>" style="color:var(--text);text-decoration:none"><?= htmlspecialchars($li['fullname']) ?></a></td>
        <td><span style="color:var(--red);font-weight:600">★ <?= $li['avg_r'] ?></span><div style="height:4px;background:var(--border);border-radius:2px;margin-top:3px;width:80px"><div style="height:4px;background:var(--red);border-radius:2px;width:<?= round($li['avg_r']/5*100) ?>%"></div></div></td>
        <td style="color:var(--muted)"><?= $li['cnt'] ?></td></tr>
    <?php $rank++; endwhile; endif; ?></tbody></table>
</div>
</div>
<div style="font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin:4px 0 8px">Students – by Session Grade (<?= date('F Y') ?>)</div>
<div class="duo-grid">
<div class="panel">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-star" style="color:var(--amber)"></i> Top 5 Highest Grades</div><small style="color:var(--muted);font-size:10px"><i class="fa-solid fa-rotate me-1"></i>Resets monthly</small></div>
    <table class="tbl"><thead><tr><th>#</th><th>Student</th><th>Avg Grade</th><th>Sessions</th></tr></thead><tbody>
    <?php $topStudents->data_seek(0); $rank=1; if($topStudents->num_rows===0): ?>
    <tr><td colspan="4" style="color:var(--muted);text-align:center;padding:20px">No grades recorded this month.</td></tr>
    <?php else: $medals=['🥇','🥈','🥉','4️⃣','5️⃣']; while($ts=$topStudents->fetch_assoc()): $gc=$ts['avg_grade']>=80?'var(--green)':($ts['avg_grade']>=60?'var(--amber)':'var(--red)'); ?>
    <tr><td><span class="rank-medal"><?= $medals[$rank-1]??$rank ?></span></td>
        <td><a href="<?= $selfFile ?>?section=student_profile&view_student=<?= $ts['student_id'] ?>" style="color:var(--text);text-decoration:none"><?= htmlspecialchars($ts['fullname']) ?></a></td>
        <td><span style="color:<?= $gc ?>;font-weight:600"><?= $ts['avg_grade'] ?>/100</span><div class="grade-bar" style="width:90px;margin-top:3px"><div class="grade-fill" style="width:<?= $ts['avg_grade'] ?>%;background:<?= $gc ?>"></div></div></td>
        <td style="color:var(--muted)"><?= $ts['sess_cnt'] ?></td></tr>
    <?php $rank++; endwhile; endif; ?></tbody></table>
</div>
<div class="panel">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-arrow-trend-down" style="color:var(--red)"></i> Top 5 Lowest Grades</div><small style="color:var(--muted);font-size:10px"><i class="fa-solid fa-rotate me-1"></i>Resets monthly</small></div>
    <table class="tbl"><thead><tr><th>#</th><th>Student</th><th>Avg Grade</th><th>Sessions</th></tr></thead><tbody>
    <?php $lowStudents->data_seek(0); $rank=1; if($lowStudents->num_rows===0): ?>
    <tr><td colspan="4" style="color:var(--muted);text-align:center;padding:20px">No grades recorded this month.</td></tr>
    <?php else: while($ls=$lowStudents->fetch_assoc()): ?>
    <tr><td><span style="color:var(--muted);font-size:12px"><?= $rank ?></span></td>
        <td><a href="<?= $selfFile ?>?section=student_profile&view_student=<?= $ls['student_id'] ?>" style="color:var(--text);text-decoration:none"><?= htmlspecialchars($ls['fullname']) ?></a></td>
        <td><span style="color:var(--red);font-weight:600"><?= $ls['avg_grade'] ?>/100</span><div class="grade-bar" style="width:90px;margin-top:3px"><div class="grade-fill" style="width:<?= $ls['avg_grade'] ?>%;background:var(--red)"></div></div></td>
        <td style="color:var(--muted)"><?= $ls['sess_cnt'] ?></td></tr>
    <?php $rank++; endwhile; endif; ?></tbody></table>
</div>
</div>
</div>

<!-- ══ REPORTS ════════════════════════════════════════════════ -->
<div id="section-reports" class="section <?= $activeSection==='reports'?'active':'' ?>">
<div class="panel mb-3">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-chart-pie"></i> Course Breakdown</div></div>
    <table class="tbl"><thead><tr><th>Course Type</th><th>Total</th><th>Completed</th><th>In Progress</th><th>Pending</th></tr></thead><tbody>
    <?php $courseReport=q($conn,"SELECT ic.course_type,COUNT(*) AS total,SUM(a.status='completed') AS done,SUM(a.status IN('approved','in_progress')) AS active,SUM(a.status='pending') AS pending FROM appointments a JOIN instructor_courses ic ON ic.course_id=a.course_id GROUP BY ic.course_type ORDER BY ic.course_type");
    while($cr=$courseReport->fetch_assoc()): $tc=$cColors[$cr['course_type']]??'tag-gray'; ?>
    <tr><td><span class="tag <?= $tc ?>"><?= htmlspecialchars($cr['course_type']) ?></span></td>
        <td><?= $cr['total'] ?></td><td><?= $cr['done'] ?></td><td><?= $cr['active'] ?></td><td><?= $cr['pending'] ?></td></tr>
    <?php endwhile; ?></tbody></table>
</div>
<div class="panel">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-users"></i> Student Progress Summary</div></div>
    <table class="tbl"><thead><tr><th>Student</th><th>TDC Done</th><th>PDC Done</th><th>Cert</th><th>Avg Grade (TDC)</th><th>Avg Perf</th></tr></thead><tbody>
    <?php $sProgress=q($conn,"SELECT s.student_id,s.fullname,(SELECT COUNT(*) FROM appointments a JOIN instructor_courses ic ON ic.course_id=a.course_id WHERE a.student_id=s.student_id AND ic.course_type IN('TDC Face-to-Face','TDC Online') AND a.status='completed') AS tdc_done,(SELECT COUNT(*) FROM appointments a JOIN instructor_courses ic ON ic.course_id=a.course_id WHERE a.student_id=s.student_id AND ic.course_type LIKE 'PDC%' AND a.status='completed') AS pdc_done,(SELECT COUNT(*) FROM tdc_certificates WHERE student_id=s.student_id) AS cert,(SELECT ROUND(AVG(grade),1) FROM sessions WHERE student_id=s.student_id AND grade IS NOT NULL) AS avg_grade,(SELECT ROUND(AVG(performance_rating),1) FROM sessions WHERE student_id=s.student_id AND performance_rating IS NOT NULL) AS avg_perf FROM students s ORDER BY s.fullname ASC");
    while($sp=$sProgress->fetch_assoc()): $gc=$sp['avg_grade']?($sp['avg_grade']>=80?'var(--green)':($sp['avg_grade']>=60?'var(--amber)':'var(--red)')):''; ?>
    <tr><td><?= htmlspecialchars($sp['fullname']) ?></td>
        <td><?= $sp['tdc_done']?'<span class="bkg">✓</span>':'—' ?></td>
        <td><?= $sp['pdc_done']?'<span class="bkg">✓</span>':'—' ?></td>
        <td><?= $sp['cert']?'<span class="cert-badge"><i class="fa-solid fa-certificate"></i></span>':'—' ?></td>
        <td><?= $sp['avg_grade']?'<strong style="color:'.$gc.'">'.$sp['avg_grade'].'/100</strong>':'—' ?></td>
        <td><?= $sp['avg_perf']?'★ '.$sp['avg_perf']:'—' ?></td></tr>
    <?php endwhile; ?></tbody></table>
</div>
</div>

<!-- ══ ANNOUNCEMENTS ════════════════════════════════════════ -->
<div id="section-communication" class="section <?= $activeSection==='communication'?'active':'' ?>">
<div class="panel" style="margin-bottom:16px">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-bullhorn"></i> Send Announcement</div></div>
    <div style="padding:16px">
    <form method="POST" id="ann-form">
        <input type="hidden" name="action" value="announce">
        <div style="margin-bottom:14px">
            <label class="form-label">Send To</label>
            <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:10px">
            <?php $stoOptions=['all_students'=>['fa-user-graduate','All Students','var(--accent)',$stuRecipCount],'all_instructors'=>['fa-chalkboard-user','All Instructors','var(--green)',$insRecipCount],'everyone'=>['fa-users','Everyone','var(--amber)',$stuRecipCount+$insRecipCount],'specific_student'=>['fa-user','One Student','var(--accent)',null],'specific_instructor'=>['fa-id-badge','One Instructor','var(--green)',null]];
            foreach($stoOptions as $val=>[$ico,$lbl,$clr,$cnt2]): ?>
            <label style="flex:1;min-width:130px;cursor:pointer">
                <input type="radio" name="send_to" value="<?= $val ?>" <?= $val==='all_students'?'checked':'' ?> class="sto-radio" style="display:none">
                <div class="sto-btn" data-val="<?= $val ?>" data-color="<?= $clr ?>" style="border:2px solid var(--border);border-radius:8px;padding:10px 14px;display:flex;align-items:center;gap:8px;transition:.15s;user-select:none">
                    <i class="fa-solid <?= $ico ?>" style="color:<?= $clr ?>"></i>
                    <span style="font-size:12px;font-weight:500"><?= $lbl ?></span>
                    <?php if($cnt2!==null): ?><span style="margin-left:auto;background:rgba(255,255,255,.06);border-radius:10px;padding:1px 8px;font-size:11px;color:var(--muted)"><?= $cnt2 ?></span><?php endif; ?>
                </div>
            </label>
            <?php endforeach; ?>
            </div>
            <div id="ann-specific-student" style="display:none;margin-top:8px">
                <label class="form-label">Select Student</label>
                <select name="specific_id" id="ann-sid" class="di"><option value="">— choose student —</option>
                <?php $allStudentsList->data_seek(0); while($sr=$allStudentsList->fetch_assoc()): ?><option value="<?= $sr['student_id'] ?>"><?= htmlspecialchars($sr['fullname']) ?></option><?php endwhile; ?></select>
            </div>
            <div id="ann-specific-instructor" style="display:none;margin-top:8px">
                <label class="form-label">Select Instructor</label>
                <select name="specific_id" id="ann-iid" class="di"><option value="">— choose instructor —</option>
                <?php $allInstructorsList->data_seek(0); while($ir=$allInstructorsList->fetch_assoc()): ?><option value="<?= $ir['instructor_id'] ?>"><?= htmlspecialchars($ir['fullname']) ?></option><?php endwhile; ?></select>
            </div>
        </div>
        <div style="margin-bottom:14px"><label class="form-label">Message</label>
            <textarea name="message" class="di" rows="4" required placeholder="Type your announcement…"></textarea></div>
        <button type="submit" class="be"><i class="fa-solid fa-paper-plane me-1"></i> Send Announcement</button>
    </form>
    </div>
</div>
<?php $recentAnn=q($conn,"SELECT n.*,CASE WHEN n.user_type='student' THEN (SELECT fullname FROM students WHERE student_id=n.user_id) WHEN n.user_type='instructor' THEN (SELECT fullname FROM instructors WHERE instructor_id=n.user_id) END AS recip FROM notifications n WHERE n.type='announcement' ORDER BY n.created_at DESC LIMIT 50");
if($recentAnn->num_rows>0): ?>
<div class="panel" style="margin-bottom:16px">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-clock-rotate-left me-1"></i> Recent Announcements</div></div>
    <table class="tbl"><thead><tr><th>Message</th><th>Recipient</th><th>Audience</th><th>Read</th><th>Sent</th></tr></thead><tbody>
    <?php $targetMap=['all_students'=>'All Students','all_instructors'=>'All Instructors','everyone'=>'Everyone','specific_student'=>'Single Student','specific_instructor'=>'Single Instructor',''=>'—',null=>'—'];
    while($ra=$recentAnn->fetch_assoc()): $isInst=$ra['user_type']==='instructor'; $targetLbl=$targetMap[$ra['recipient_target']]??'—'; ?>
    <tr>
        <td style="max-width:260px;font-size:12px"><?= htmlspecialchars(substr(str_replace('📢 Admin: ','',$ra['message']),0,80)) ?><?= strlen($ra['message'])>88?'…':'' ?></td>
        <td style="font-size:12px"><?= htmlspecialchars($ra['recip']??'—') ?></td>
        <td><span class="tag <?= $isInst?'tag-green':'tag-blue' ?>" style="font-size:10px"><?= $targetLbl ?></span></td>
        <td><?= $ra['is_read']?'<span class="bkg" style="font-size:10px">✓</span>':'<span style="font-size:10px;color:var(--muted)">—</span>' ?></td>
        <td style="font-size:10px;color:var(--muted);white-space:nowrap"><?= date('M d, g:i A',strtotime($ra['created_at'])) ?></td>
    </tr>
    <?php endwhile; ?></tbody></table>
</div>
<?php endif; ?>
</div>

<!-- ══ WORKLOAD DISTRIBUTION ══════════════════════════════════ -->
<div id="section-workload" class="section <?= $activeSection==='workload'?'active':'' ?>">
<?php
$wlInstrs=q($conn,"SELECT i.instructor_id,i.fullname,
    (SELECT COUNT(*) FROM appointments WHERE instructor_id=i.instructor_id AND status IN('pending','approved','in_progress')) AS active_appts,
    (SELECT COUNT(*) FROM appointments WHERE instructor_id=i.instructor_id AND status='completed') AS done_appts,
    (SELECT COUNT(DISTINCT student_id) FROM appointments WHERE instructor_id=i.instructor_id AND status IN('approved','in_progress')) AS current_students,
    (SELECT COALESCE(SUM(hours_covered),0) FROM sessions WHERE instructor_id=i.instructor_id AND status='completed' AND DATE_FORMAT(session_date,'%Y-%m')=DATE_FORMAT(NOW(),'%Y-%m')) AS month_hrs,
    (SELECT COALESCE(SUM(hours_covered),0) FROM sessions WHERE instructor_id=i.instructor_id AND status='completed') AS total_hrs,
    (SELECT ROUND(AVG(rating),1) FROM instructor_ratings WHERE instructor_id=i.instructor_id) AS avg_rating,
    (SELECT COUNT(*) FROM instructor_courses WHERE instructor_id=i.instructor_id AND is_active=1) AS active_courses
    FROM instructors i ORDER BY active_appts ASC, month_hrs ASC");
$totalActiveAppts=(int)q($conn,"SELECT COUNT(*) AS c FROM appointments WHERE status IN('pending','approved','in_progress')")->fetch_assoc()['c'];
// Build instructor course map for assign modal
$instrCourseMap=[];
$icRes=q($conn,"SELECT ic.*,i.fullname AS iname FROM instructor_courses ic JOIN instructors i ON i.instructor_id=ic.instructor_id WHERE ic.is_active=1 ORDER BY i.fullname,ic.course_type");
while($ic=$icRes->fetch_assoc()) $instrCourseMap[$ic['instructor_id']][]=$ic;
$unassignedStudents=q($conn,"SELECT s.student_id,s.fullname FROM students s WHERE s.is_verified=1 AND NOT EXISTS(SELECT 1 FROM appointments a WHERE a.student_id=s.student_id AND a.status IN('pending','approved','in_progress')) ORDER BY s.fullname");
?>

<!-- ── Assign Student Panel ─────────────────────────────────── -->
<div class="panel" style="margin-bottom:16px">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-user-plus" style="color:var(--green)"></i> Assign Student to Instructor</div>
        <span style="font-size:11px;color:var(--muted)">Pre-approved — bypasses student booking flow</span></div>
    <div style="padding:16px">
    <?php if($flash): ?><div class="flash flash-ok" style="margin-bottom:12px"><i class="fa-solid fa-circle-check me-2"></i><?= htmlspecialchars($flash) ?></div><?php endif; ?>
    <?php if($flashErr): ?><div style="padding:10px 16px;border-radius:8px;margin-bottom:12px;font-size:12px;background:rgba(229,85,85,.15);border:1px solid rgba(229,85,85,.3);color:var(--red)"><i class="fa-solid fa-triangle-exclamation me-2"></i><?= htmlspecialchars($flashErr) ?></div><?php endif; ?>
    <form method="POST" id="assign_main_form" style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr auto;gap:12px;align-items:end">
        <input type="hidden" name="action" value="assign_student">
        <input type="hidden" name="total_hours" id="assign_hrs_hidden" value="8">
        <input type="hidden" name="admin_note" id="assign_note_hidden" value="">
        <div>
            <label class="form-label">Student *</label>
            <select name="student_id" class="di" required id="assign_student_sel">
                <option value="">— Select Student —</option>
                <?php
                // Show all verified students (not just unassigned) so admin can assign anyone
                $allStuRes=q($conn,"SELECT student_id,fullname FROM students WHERE is_verified=1 ORDER BY fullname");
                while($sv=$allStuRes->fetch_assoc()):
                ?>
                <option value="<?= $sv['student_id'] ?>"><?= htmlspecialchars($sv['fullname']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div>
            <label class="form-label">Instructor *</label>
            <select name="instructor_id" class="di" required id="assign_instr_sel" onchange="loadAssignCourses(this.value)">
                <option value="">— Select Instructor —</option>
                <?php $wlInstrs->data_seek(0); while($wli=$wlInstrs->fetch_assoc()): $loadPct2=0; ?>
                <option value="<?= $wli['instructor_id'] ?>" data-load="<?= $wli['active_appts'] ?>">
                    <?= htmlspecialchars($wli['fullname']) ?> (<?= $wli['active_appts'] ?> active)
                </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div>
            <label class="form-label">Course *</label>
            <select name="course_id" class="di" required id="assign_course_sel">
                <option value="">— Select Instructor First —</option>
            </select>
        </div>
        <div>
            <label class="form-label">Start Date *</label>
            <input type="date" name="start_date" class="di" required min="<?= date('Y-m-d',strtotime('+1 day')) ?>" id="assign_date">
        </div>
        <div>
            <button type="submit" class="be" style="height:36px" onclick="return syncAndConfirmAssign()"><i class="fa-solid fa-user-plus"></i> Assign</button>
        </div>
    </form>
    <div style="margin-top:10px;display:flex;gap:10px;align-items:center;flex-wrap:wrap">
        <div style="flex:1">
            <label class="form-label">Hours (PDC only)</label>
            <input type="number" id="assign_hrs" class="di" value="8" min="1" max="40" style="width:80px" oninput="document.getElementById('assign_hrs_hidden').value=this.value">
        </div>
        <div style="flex:2">
            <label class="form-label">Admin Note (optional)</label>
            <input type="text" id="assign_note" class="di" placeholder="e.g. Reassigned from unavailable instructor" oninput="document.getElementById('assign_note_hidden').value=this.value">
        </div>
    </div>
    <!-- Slot check display -->
    <div id="assign_slot_info" style="display:none;margin-top:10px;padding:8px 12px;border-radius:6px;font-size:12px"></div>
    <!-- Light-load instructors hint -->
    <div style="margin-top:10px;padding:10px 12px;background:var(--bg3);border-radius:8px;font-size:11px;color:var(--muted)">
        <i class="fa-solid fa-lightbulb me-1" style="color:var(--amber)"></i>
        <strong>Light load instructors</strong> (recommended for assignment):
        <?php
        $wlInstrs->data_seek(0); $lightOnes=[]; $maxA2=1;
        while($wli2=$wlInstrs->fetch_assoc()){if((int)$wli2['active_appts']>$maxA2)$maxA2=(int)$wli2['active_appts'];}
        $wlInstrs->data_seek(0);
        while($wli2=$wlInstrs->fetch_assoc()){
            $p2=$maxA2>0?round($wli2['active_appts']/$maxA2*100):0;
            if($p2<50) $lightOnes[]=htmlspecialchars($wli2['fullname']).' ('.$wli2['active_appts'].' active)';
        }
        echo $lightOnes?implode(', ',$lightOnes):'All instructors are moderately or heavily loaded.';
        ?>
    </div>
    </div>
</div>
    <div style="background:var(--bg2);border:1px solid var(--border);border-radius:10px;padding:14px 20px;flex:1;min-width:140px">
        <div style="font-size:22px;font-weight:700;color:var(--amber)"><?= $totalActiveAppts ?></div>
        <div style="font-size:11px;color:var(--muted)">Total Active Appointments</div>
    </div>
    <div style="background:var(--bg2);border:1px solid var(--border);border-radius:10px;padding:14px 20px;flex:1;min-width:140px">
        <div style="font-size:22px;font-weight:700;color:var(--accent)"><?= $wlInstrs->num_rows ?></div>
        <div style="font-size:11px;color:var(--muted)">Total Instructors</div>
    </div>
</div>

<div class="panel" style="margin-bottom:16px">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-chart-gantt" style="color:var(--accent)"></i> Instructor Load Overview</div>
        <small style="font-size:10px;color:var(--muted)">↑ Light load → Heavy load</small></div>
    <table class="tbl"><thead><tr><th>Instructor</th><th>Active Appts</th><th>Current Students</th><th>Month Hours</th><th>Total Hours</th><th>Completed</th><th>Avg Rating</th><th>Courses</th><th>Load</th><th>Students</th></tr></thead><tbody>
    <?php $wlInstrs->data_seek(0); $maxActive=1; $rows=[];
    while($r=$wlInstrs->fetch_assoc()){$rows[]=$r;if((int)$r['active_appts']>$maxActive)$maxActive=(int)$r['active_appts'];}
    if(empty($rows)): ?><tr><td colspan="10" style="text-align:center;color:var(--muted);padding:20px">No instructors yet.</td></tr>
    <?php else: foreach($rows as $r):
        $pct=$maxActive>0?round($r['active_appts']/$maxActive*100):0;
        $barColor=$pct>=80?'var(--red)':($pct>=50?'var(--amber)':'var(--green)');
        $loadLabel=$pct>=80?'Heavy':($pct>=50?'Moderate':'Light');
        $loadClass=$pct>=80?'bkr':($pct>=50?'bkp':'bkg');
        $iid=$r['instructor_id'];
    ?>
    <tr>
        <td><a href="<?= $selfFile ?>?section=instructor_profile&view_instructor=<?= $iid ?>" style="color:var(--text);text-decoration:none;font-weight:500"><?= htmlspecialchars($r['fullname']) ?></a></td>
        <td><strong style="color:<?= $r['active_appts']>0?'var(--amber)':'var(--muted)' ?>"><?= $r['active_appts'] ?></strong></td>
        <td><?= $r['current_students'] ?></td>
        <td><?= number_format($r['month_hrs'],1) ?>h</td>
        <td><?= number_format($r['total_hrs'],1) ?>h</td>
        <td style="color:var(--green)"><?= $r['done_appts'] ?></td>
        <td><?= $r['avg_rating']?'<span style="color:var(--amber)">★ '.$r['avg_rating'].'</span>':'<span style="color:var(--muted)">—</span>' ?></td>
        <td><?= $r['active_courses'] ?></td>
        <td style="min-width:120px">
            <div style="display:flex;align-items:center;gap:8px">
                <div style="flex:1;height:8px;background:var(--border);border-radius:4px;overflow:hidden"><div style="height:100%;width:<?= $pct ?>%;background:<?= $barColor ?>;border-radius:4px;transition:width .3s"></div></div>
                <span class="<?= $loadClass ?>" style="font-size:10px;white-space:nowrap"><?= $loadLabel ?></span>
            </div>
        </td>
        <td>
            <?php if($r['active_appts']>0): ?>
            <button class="bds" style="font-size:11px" onclick="toggleWlDetail(<?= $iid ?>)"><i class="fa-solid fa-eye me-1"></i>View (<?= $r['active_appts'] ?>)</button>
            <?php else: ?><span style="color:var(--muted);font-size:11px">—</span><?php endif; ?>
        </td>
    </tr>
    <?php if($r['active_appts']>0): ?>
    <tr id="wl-detail-<?= $iid ?>" style="display:none">
        <td colspan="10" style="padding:0;background:var(--bg3)">
            <div style="padding:12px 16px">
                <div style="font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px"><i class="fa-solid fa-users me-1"></i>Students currently with <?= htmlspecialchars($r['fullname']) ?></div>
                <table class="tbl" style="background:var(--bg2);border-radius:8px;overflow:hidden">
                    <thead><tr><th>Student</th><th>Course</th><th>Start Date</th><th>Hours</th><th>Status</th><th></th></tr></thead><tbody>
                    <?php
                    $wlStudents=q($conn,"SELECT a.appointment_id,a.student_id,a.course_id,a.start_date,a.total_hours,a.status,ic.course_type,s.fullname AS sname
                        FROM appointments a
                        JOIN students s ON s.student_id=a.student_id
                        JOIN instructor_courses ic ON ic.course_id=a.course_id
                        WHERE a.instructor_id=$iid AND a.status IN('pending','approved','in_progress')
                        ORDER BY a.start_date ASC");
                    while($ws=$wlStudents->fetch_assoc()):
                        $tcw=$cColors[$ws['course_type']]??'tag-gray';
                        $isTDCw=stripos($ws['course_type'],'TDC')===0?1:0;
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($ws['sname']) ?></td>
                        <td><span class="tag <?= $tcw ?>"><?= htmlspecialchars($ws['course_type']) ?></span></td>
                        <td><?= date('M d, Y',strtotime($ws['start_date'])) ?></td>
                        <td><?= $ws['total_hours'] ?> hrs</td>
                        <td><span class="<?= $scmS[$ws['status']]??'bkd' ?>"><?= $scmL[$ws['status']]??$ws['status'] ?></span></td>
                        <td>
                            <button class="be" style="font-size:11px"
                                onclick="openReassignModal(<?= $ws['appointment_id'] ?>,<?= $iid ?>,'<?= addslashes(htmlspecialchars($ws['sname'])) ?>','<?= addslashes(htmlspecialchars($ws['course_type'])) ?>','<?= $ws['start_date'] ?>',<?= $ws['total_hours'] ?>,<?= $isTDCw ?>)">
                                <i class="fa-solid fa-right-left"></i> Reassign
                            </button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    </tbody></table>
            </div>
        </td>
    </tr>
    <?php endif; ?>
    <?php endforeach; endif; ?></tbody></table>
</div>

<!-- Upcoming schedule conflicts / full dates -->
<?php $fullSlots=q($conn,"SELECT cs.*,ic.course_type,i.fullname AS iname FROM course_schedules cs JOIN instructor_courses ic ON ic.course_id=cs.course_id JOIN instructors i ON i.instructor_id=cs.instructor_id WHERE cs.student_limit IS NOT NULL AND cs.student_limit>0 AND cs.booked_count>=cs.student_limit AND cs.schedule_date>=CURDATE() ORDER BY cs.schedule_date ASC LIMIT 20");
if($fullSlots->num_rows>0): ?>
<div class="panel" style="margin-bottom:16px">
    <div class="panel-head"><div class="panel-title" style="color:var(--red)"><i class="fa-solid fa-ban"></i> Fully Booked Upcoming Dates</div></div>
    <table class="tbl"><thead><tr><th>Date</th><th>Instructor</th><th>Course</th><th>Booked/Limit</th></tr></thead><tbody>
    <?php while($fs=$fullSlots->fetch_assoc()): $tc=$cColors[$fs['course_type']]??'tag-gray'; ?>
    <tr>
        <td><strong><?= date('M d, Y (D)',strtotime($fs['schedule_date'])) ?></strong></td>
        <td><?= htmlspecialchars($fs['iname']) ?></td>
        <td><span class="tag <?= $tc ?>"><?= htmlspecialchars($fs['course_type']) ?></span></td>
        <td><span class="bkr"><?= $fs['booked_count'] ?>/<?= $fs['student_limit'] ?> FULL</span></td>
    </tr>
    <?php endwhile; ?></tbody></table>
</div>
<?php endif; ?>

<!-- Per-instructor course schedules -->
<div class="panel">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-calendar-days"></i> Upcoming Course Schedules (with Limits)</div></div>
    <?php $schedAll=q($conn,"SELECT cs.*,ic.course_type,i.fullname AS iname FROM course_schedules cs JOIN instructor_courses ic ON ic.course_id=cs.course_id JOIN instructors i ON i.instructor_id=cs.instructor_id WHERE cs.schedule_date>=CURDATE() AND cs.student_limit IS NOT NULL ORDER BY cs.schedule_date ASC, i.fullname ASC LIMIT 40");
    if($schedAll->num_rows===0): ?><div style="padding:16px;color:var(--muted);text-align:center;font-size:12px">No scheduled dates with limits yet. Instructors must set a student limit on their courses.</div>
    <?php else: ?>
    <table class="tbl"><thead><tr><th>Date</th><th>Instructor</th><th>Course</th><th>Booked</th><th>Limit</th><th>Slots Left</th></tr></thead><tbody>
    <?php while($sc=$schedAll->fetch_assoc()):
        $left=max(0,$sc['student_limit']-$sc['booked_count']);
        $pct2=round($sc['booked_count']/$sc['student_limit']*100);
        $barC=$pct2>=100?'var(--red)':($pct2>=75?'var(--amber)':'var(--green)');
        $tc=$cColors[$sc['course_type']]??'tag-gray';
    ?>
    <tr>
        <td><?= date('M d, Y (D)',strtotime($sc['schedule_date'])) ?></td>
        <td><?= htmlspecialchars($sc['iname']) ?></td>
        <td><span class="tag <?= $tc ?>"><?= htmlspecialchars($sc['course_type']) ?></span></td>
        <td>
            <div style="display:flex;align-items:center;gap:6px">
                <strong><?= $sc['booked_count'] ?></strong>
                <div style="width:50px;height:6px;background:var(--border);border-radius:3px;overflow:hidden"><div style="height:100%;width:<?= $pct2 ?>%;background:<?= $barC ?>;border-radius:3px"></div></div>
            </div>
        </td>
        <td><?= $sc['student_limit'] ?></td>
        <td><?= $left===0?'<span class="bkr">FULL</span>':'<span style="color:var(--green);font-weight:600">'.$left.' open</span>' ?></td>
    </tr>
    <?php endwhile; ?></tbody></table>
    <?php endif; ?>
</div>
</div>

</div><!-- /main -->

<!-- ══ REASSIGN MODAL ════════════════════════════════════════ -->
<div id="reassignModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:300;align-items:center;justify-content:center;padding:16px">
<div style="background:var(--bg2);border:1px solid var(--border);border-radius:12px;width:440px;max-width:100%;padding:24px">
    <div style="font-size:15px;font-weight:600;margin-bottom:4px"><i class="fa-solid fa-right-left me-2" style="color:var(--accent)"></i>Reassign Appointment</div>
    <div id="ra_summary" style="font-size:12px;color:var(--muted);margin-bottom:16px"></div>
    <form method="POST">
    <input type="hidden" name="action" value="reassign_appointment">
    <input type="hidden" name="appointment_id" id="ra_appt_id">
    <input type="hidden" name="old_instructor_id" id="ra_old_instr">
    <div style="margin-bottom:12px">
        <label class="form-label">Course Type (fixed)</label>
        <div id="ra_course_type_display" style="font-size:13px;font-weight:600"></div>
    </div>
    <div style="margin-bottom:12px">
        <label class="form-label">New Instructor *</label>
        <select id="ra_instr_sel" class="di" required onchange="loadReassignCourses()">
            <option value="">— Select Instructor —</option>
        </select>
    </div>
    <div style="margin-bottom:12px">
        <label class="form-label">Matching Course *</label>
        <select name="course_id" id="ra_course_sel" class="di" required onchange="checkReassignSlot()">
            <option value="">— Select Instructor First —</option>
        </select>
        <div style="font-size:10px;color:var(--muted);margin-top:3px">Only courses of the same type (<span id="ra_course_type_hint"></span>) offered by the new instructor are shown.</div>
    </div>
    <div style="margin-bottom:6px">
        <label class="form-label">Start Date * <span id="ra_day_hint" style="color:var(--amber);font-size:10px"></span></label>
        <input type="date" name="start_date" id="ra_date" class="di" required onchange="validateReassignDate();checkReassignSlot()">
    </div>
    <div id="ra_slot_info" style="display:none;margin-bottom:12px;padding:8px 12px;border-radius:6px;font-size:12px"></div>
    <div id="ra_hours_wrap" style="margin-bottom:16px">
        <label class="form-label">Hours</label>
        <input type="number" name="total_hours" id="ra_hours" class="di" min="1" max="40">
    </div>
    <div style="display:flex;gap:8px;justify-content:flex-end">
        <button type="button" class="bds" onclick="closeReassignModal()">Cancel</button>
        <button type="submit" class="be" id="ra_submit_btn"><i class="fa-solid fa-right-left me-1"></i>Reassign</button>
    </div>
    </form>
</div></div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
var chartsOk=false; var _ci={};
function _destroy(id){if(_ci[id]){try{_ci[id].destroy();}catch(e){}delete _ci[id];}}

function initCharts(){
    if(chartsOk||typeof Chart==='undefined') return;
    chartsOk=true;
    var cs=getComputedStyle(document.documentElement);
    var accent=cs.getPropertyValue('--accent').trim()||'#7c6af7';
    var green=cs.getPropertyValue('--green').trim()||'#3cb97a';
    var amber=cs.getPropertyValue('--amber').trim()||'#f59e0b';
    var red=cs.getPropertyValue('--red').trim()||'#e55555';
    var muted=cs.getPropertyValue('--muted').trim()||'#8490a8';
    var border=cs.getPropertyValue('--border').trim()||'#2a3147';
    var bg=cs.getPropertyValue('--bg').trim()||'#0f1117';
    Chart.defaults.color=muted; Chart.defaults.font.family='Segoe UI,sans-serif'; Chart.defaults.font.size=11;

    _destroy('c1'); var e1=document.getElementById('chartNewStudents');
    if(e1){_ci['c1']=new Chart(e1,{type:'line',data:{labels:<?= json_encode($chartMonths) ?>,datasets:[{label:'New Students',data:<?= json_encode($chartNewStudents) ?>,borderColor:accent,backgroundColor:accent+'33',fill:true,tension:0.4,pointBackgroundColor:accent,pointRadius:4,pointHoverRadius:6}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{x:{grid:{color:border+'44'}},y:{grid:{color:border+'44'},beginAtZero:true,ticks:{precision:0,stepSize:1}}}}});}

    _destroy('c2'); var e2=document.getElementById('chartApptStatus');
    if(e2){_ci['c2']=new Chart(e2,{type:'bar',data:{labels:<?= json_encode($chartMonths) ?>,datasets:[{label:'Completed',data:<?= json_encode($chartApptCompleted) ?>,backgroundColor:green+'bb',borderRadius:3,stack:'s'},{label:'Active/Pending',data:<?= json_encode($chartApptPending) ?>,backgroundColor:amber+'bb',borderRadius:3,stack:'s'},{label:'Cancelled',data:<?= json_encode($chartApptCancelled) ?>,backgroundColor:red+'88',borderRadius:3,stack:'s'}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom',labels:{boxWidth:10,padding:8}}},scales:{x:{grid:{color:border+'44'},stacked:true},y:{grid:{color:border+'44'},beginAtZero:true,stacked:true,ticks:{precision:0,stepSize:1}}}}});}

    _destroy('c3'); var e3=document.getElementById('chartTdcPdc');
    if(e3){_ci['c3']=new Chart(e3,{type:'doughnut',data:{labels:['TDC Done','PDC Done','Active','Cancelled'],datasets:[{data:[<?= $tdcDoneCount ?>,<?= $pdcDoneCount ?>,<?= $tdcActiveCount+$pdcActiveCount ?>,<?= array_sum($chartApptCancelled) ?>],backgroundColor:[amber+'dd',green+'dd',accent+'dd',red+'99'],borderColor:bg,borderWidth:2,hoverOffset:8}]},options:{responsive:true,maintainAspectRatio:false,cutout:'55%',plugins:{legend:{display:false}}}});}

    _destroy('c4'); var e4=document.getElementById('chartGradeDist');
    if(e4){_ci['c4']=new Chart(e4,{type:'bar',data:{labels:['90–100','80–89','70–79','60–69','< 60'],datasets:[{label:'Sessions',data:[<?= implode(',',$gradeBuckets) ?>],backgroundColor:[green+'cc',accent+'cc',amber+'cc','#fb923c99',red+'99'],borderRadius:4}]},options:{indexAxis:'y',responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{x:{grid:{color:border+'44'},beginAtZero:true,ticks:{precision:0,stepSize:1}},y:{grid:{color:border+'22'}}}}});}

    _destroy('c5'); var e5=document.getElementById('chartGender');
    if(e5){_ci['c5']=new Chart(e5,{type:'doughnut',data:{labels:['Male','Female','Not Specified'],datasets:[{data:[<?= $maleCount ?>,<?= $femaleCount ?>,<?= $otherGenderCount ?>],backgroundColor:['#60a5fadd','#f472b6dd',border+'cc'],borderColor:bg,borderWidth:2,hoverOffset:6}]},options:{responsive:true,maintainAspectRatio:false,cutout:'55%',plugins:{legend:{display:false}}}});}
}

window.addEventListener('load',function(){
    document.querySelectorAll('.section').forEach(function(el){el.style.display=el.classList.contains('active')?'block':'none';});
    var dash=document.getElementById('section-dashboard');
    if(dash&&dash.style.display==='block') initCharts();
});

function showSection(n){
    document.querySelectorAll('.section').forEach(function(el){el.classList.remove('active');el.style.display='none';});
    var el=document.getElementById('section-'+n);
    if(el){el.classList.add('active');el.style.display='block';}
    document.querySelectorAll('.nav-item').forEach(function(nav){nav.classList.remove('active');var oc=nav.getAttribute('onclick')||'';if(oc.indexOf("'"+n+"'")>-1)nav.classList.add('active');});
    var ti=document.getElementById('page-title-text'); if(ti)ti.textContent=T[n]||n;
    if(n==='dashboard'){chartsOk=false;initCharts();}
    return false;
}

function refreshSto(){
    var radios=document.querySelectorAll('.sto-radio'); var val='';
    for(var i=0;i<radios.length;i++){var r=radios[i],lbl=r.closest('label');if(!lbl)continue;var btn=lbl.querySelector('.sto-btn');if(!btn)continue;if(r.checked){btn.style.borderColor=btn.getAttribute('data-color');btn.style.background='rgba(255,255,255,.04)';val=r.value;}else{btn.style.borderColor='var(--border)';btn.style.background='';}}
    var ss=document.getElementById('ann-specific-student'); var si=document.getElementById('ann-specific-instructor');
    if(ss) ss.style.display=(val==='specific_student')?'block':'none';
    if(si) si.style.display=(val==='specific_instructor')?'block':'none';
    var sid=document.getElementById('ann-sid'); var iid=document.getElementById('ann-iid');
    if(sid) sid.required=(val==='specific_student');
    if(iid) iid.required=(val==='specific_instructor');
}
document.querySelectorAll('.sto-radio').forEach(function(r){r.addEventListener('change',refreshSto);});
document.querySelectorAll('.sto-btn').forEach(function(btn){btn.addEventListener('click',function(){var lbl=this.closest('label');if(lbl){var r=lbl.querySelector('.sto-radio');if(r){r.checked=true;refreshSto();}}});});
refreshSto();

// ── Assign Student JS ──────────────────────────────────────────────────────
var _instrCourses = <?php
    $icMapJs=[];
    $icRes2=q($conn,"SELECT ic.*,i.fullname AS iname FROM instructor_courses ic JOIN instructors i ON i.instructor_id=ic.instructor_id WHERE ic.is_active=1 ORDER BY i.fullname,ic.course_type");
    while($ic2=$icRes2->fetch_assoc()){
        $icMapJs[$ic2['instructor_id']][]=['course_id'=>$ic2['course_id'],'course_type'=>$ic2['course_type'],'price_per_hr'=>$ic2['price_per_hr'],'student_limit'=>$ic2['student_limit']??null,'is_tdc'=>stripos($ic2['course_type'],'TDC')===0];
    }
    echo json_encode($icMapJs);
?>;

var _scheduleMap = <?php
    $smRes=q($conn,"SELECT cs.course_id,cs.schedule_date,cs.booked_count,cs.student_limit FROM course_schedules cs WHERE cs.schedule_date>=CURDATE()");
    $smJs=[];
    while($sm=$smRes->fetch_assoc()){
        $key=$sm['course_id'].':'.$sm['schedule_date'];
        $smJs[$key]=['booked'=>(int)$sm['booked_count'],'limit'=>(int)$sm['student_limit']];
    }
    echo json_encode($smJs);
?>;

var _instrNames = <?php
    $inRes=q($conn,"SELECT instructor_id,fullname FROM instructors ORDER BY fullname");
    $inJs=[];
    while($in=$inRes->fetch_assoc()){ $inJs[$in['instructor_id']]=$in['fullname']; }
    echo json_encode($inJs);
?>;

function loadAssignCourses(instrId){
    var sel=document.getElementById('assign_course_sel');
    sel.innerHTML='<option value="">— Select Course —</option>';
    if(!instrId||!_instrCourses[instrId]){return;}
    _instrCourses[instrId].forEach(function(c){
        var opt=document.createElement('option');
        opt.value=c.course_id;
        var limitStr=c.student_limit?(' | '+c.student_limit+'/date'):'';
        opt.textContent=c.course_type+' — ₱'+parseFloat(c.price_per_hr).toFixed(2)+limitStr;
        opt.dataset.isTdc=c.is_tdc?'1':'0';
        opt.dataset.limit=c.student_limit||'0';
        sel.appendChild(opt);
    });
    checkAssignSlot();
}

function checkAssignSlot(){
    var courseId=document.getElementById('assign_course_sel').value;
    var date=document.getElementById('assign_date').value;
    var info=document.getElementById('assign_slot_info');
    if(!courseId||!date){info.style.display='none';return;}
    var sel=document.getElementById('assign_course_sel');
    var opt=sel.options[sel.selectedIndex];
    var limit=opt?parseInt(opt.dataset.limit||'0'):0;
    if(!limit){info.style.display='none';return;}
    var key=courseId+':'+date;
    var slot=_scheduleMap[key];
    var booked=slot?slot.booked:0;
    var left=limit-booked;
    info.style.display='block';
    if(left<=0){
        info.style.cssText='display:block;padding:8px 12px;border-radius:6px;font-size:12px;background:rgba(229,85,85,.12);border:1px solid rgba(229,85,85,.3);color:var(--red)';
        info.innerHTML='<i class="fa-solid fa-ban me-1"></i><strong>Fully booked</strong> on this date ('+booked+'/'+limit+'). Choose a different date.';
    } else {
        info.style.cssText='display:block;padding:8px 12px;border-radius:6px;font-size:12px;background:rgba(59,185,122,.1);border:1px solid rgba(59,185,122,.3);color:var(--green)';
        info.innerHTML='<i class="fa-solid fa-circle-check me-1"></i><strong>'+left+' slot'+(left>1?'s':'')+' available</strong> ('+booked+'/'+limit+' booked).';
    }
}

document.getElementById('assign_course_sel').addEventListener('change',checkAssignSlot);
document.getElementById('assign_date').addEventListener('change',function(){
    // TDC date validation
    var sel=document.getElementById('assign_course_sel');
    var opt=sel.options[sel.selectedIndex];
    if(opt&&opt.dataset.isTdc==='1'&&this.value){
        var d=new Date(this.value+'T00:00:00');
        if(d.getDay()!==0&&d.getDay()!==6){
            alert('TDC courses must start on Saturday or Sunday.');
            this.value='';return;
        }
    }
    checkAssignSlot();
});

function syncAndConfirmAssign(){
    document.getElementById('assign_hrs_hidden').value=document.getElementById('assign_hrs').value||8;
    document.getElementById('assign_note_hidden').value=document.getElementById('assign_note').value||'';
    var s=document.getElementById('assign_student_sel');
    var i=document.getElementById('assign_instr_sel');
    var c=document.getElementById('assign_course_sel');
    var d=document.getElementById('assign_date');
    if(!s.value||!i.value||!c.value||!d.value){alert('Please fill in all required fields.');return false;}
    var sName=s.options[s.selectedIndex].text;
    var iName=i.options[i.selectedIndex].text;
    var cName=c.options[c.selectedIndex].text;
    return confirm('Assign '+sName+' to '+iName+'\nCourse: '+cName+'\nDate: '+d.value+'\n\nThis will create a pre-approved appointment. Continue?');
}

// ── Workload: View Students toggle ─────────────────────────────────────────
function toggleWlDetail(instrId){
    var row=document.getElementById('wl-detail-'+instrId);
    if(!row)return;
    var isHidden=row.style.display==='none';
    row.style.display=isHidden?'table-row':'none';
}

// ── Reassign Modal ───────────────────────────────────────────────────────────
var _raCurrentInstr=0, _raIsTDC=0, _raCourseType='', _raOrigDate='', _raOrigHours=8;

function openReassignModal(apptId,oldInstrId,studentName,courseType,startDate,hours,isTDC){
    _raCurrentInstr=oldInstrId;
    _raIsTDC=isTDC;
    _raCourseType=courseType;
    _raOrigDate=startDate;
    _raOrigHours=hours;

    document.getElementById('ra_appt_id').value=apptId;
    document.getElementById('ra_old_instr').value=oldInstrId;
    document.getElementById('ra_summary').textContent='Student: '+studentName;
    document.getElementById('ra_course_type_display').textContent=courseType;
    document.getElementById('ra_course_type_hint').textContent=courseType;
    document.getElementById('ra_date').value=startDate;
    document.getElementById('ra_hours').value=hours;
    document.getElementById('ra_day_hint').textContent=isTDC?'(Saturday or Sunday only)':'';
    document.getElementById('ra_hours_wrap').style.display=isTDC?'none':'block';
    document.getElementById('ra_slot_info').style.display='none';

    // Populate instructor dropdown — exclude current instructor, only those offering this course type
    var sel=document.getElementById('ra_instr_sel');
    sel.innerHTML='<option value="">— Select Instructor —</option>';
    Object.keys(_instrCourses).forEach(function(instrId){
        if(parseInt(instrId)===oldInstrId)return;
        var hasMatch=_instrCourses[instrId].some(function(c){return c.course_type===courseType;});
        if(!hasMatch)return;
        var opt=document.createElement('option');
        opt.value=instrId;
        opt.textContent=_instrNames[instrId]||('Instructor #'+instrId);
        sel.appendChild(opt);
    });
    document.getElementById('ra_course_sel').innerHTML='<option value="">— Select Instructor First —</option>';

    document.getElementById('reassignModal').style.display='flex';
}

function loadReassignCourses(){
    var instrId=document.getElementById('ra_instr_sel').value;
    var sel=document.getElementById('ra_course_sel');
    sel.innerHTML='<option value="">— Select Course —</option>';
    if(!instrId||!_instrCourses[instrId]){return;}
    _instrCourses[instrId].forEach(function(c){
        if(c.course_type!==_raCourseType)return;
        var opt=document.createElement('option');
        opt.value=c.course_id;
        var limitStr=c.student_limit?(' | '+c.student_limit+'/date'):'';
        opt.textContent=c.course_type+' — ₱'+parseFloat(c.price_per_hr).toFixed(2)+limitStr;
        opt.dataset.limit=c.student_limit||'0';
        sel.appendChild(opt);
    });
    checkReassignSlot();
}

function validateReassignDate(){
    var dateInput=document.getElementById('ra_date');
    if(_raIsTDC&&dateInput.value){
        var d=new Date(dateInput.value+'T00:00:00');
        if(d.getDay()!==0&&d.getDay()!==6){
            alert('TDC courses must start on Saturday or Sunday.');
            dateInput.value='';
        }
    }
}

function checkReassignSlot(){
    var courseSel=document.getElementById('ra_course_sel');
    var date=document.getElementById('ra_date').value;
    var info=document.getElementById('ra_slot_info');
    if(!courseSel.value||!date){info.style.display='none';return;}
    var opt=courseSel.options[courseSel.selectedIndex];
    var limit=opt?parseInt(opt.dataset.limit||'0'):0;
    if(!limit){info.style.display='none';return;}
    var key=courseSel.value+':'+date;
    var slot=_scheduleMap[key];
    var booked=slot?slot.booked:0;
    var left=limit-booked;
    info.style.display='block';
    if(left<=0){
        info.style.cssText='display:block;padding:8px 12px;border-radius:6px;font-size:12px;background:rgba(229,85,85,.12);border:1px solid rgba(229,85,85,.3);color:var(--red)';
        info.innerHTML='<i class="fa-solid fa-ban me-1"></i><strong>Fully booked</strong> on this date ('+booked+'/'+limit+'). Choose a different date.';
    } else {
        info.style.cssText='display:block;padding:8px 12px;border-radius:6px;font-size:12px;background:rgba(59,185,122,.1);border:1px solid rgba(59,185,122,.3);color:var(--green)';
        info.innerHTML='<i class="fa-solid fa-circle-check me-1"></i><strong>'+left+' slot'+(left>1?'s':'')+' available</strong> ('+booked+'/'+limit+' booked).';
    }
}

function closeReassignModal(){document.getElementById('reassignModal').style.display='none';}
document.getElementById('reassignModal').addEventListener('click',function(e){if(e.target===this)closeReassignModal();});
</script>

</body>
</html>
