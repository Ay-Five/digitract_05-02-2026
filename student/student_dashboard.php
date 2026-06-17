<?php
require_once 'student_backend.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Student Dashboard – MotoSafe Driving School</title>
<link href="../assets/img/logo.png" rel="icon">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
:root{--bg:#0f1117;--bg2:#181c27;--bg3:#1e2436;--border:#2a3147;--accent:#7c6af7;--green:#3cb97a;--amber:#f59e0b;--red:#e55;--text:#e2e5f0;--muted:#8490a8;--sidebar-w:240px}
*{box-sizing:border-box;margin:0;padding:0}
body{background:var(--bg);color:var(--text);font-family:'Segoe UI',sans-serif;min-height:100vh;display:flex}
.sidebar{width:var(--sidebar-w);background:var(--bg2);border-right:1px solid var(--border);display:flex;flex-direction:column;padding:20px 0;flex-shrink:0;position:fixed;height:100vh;overflow-y:auto;z-index:200;transition:transform .3s ease}
.brand{padding:0 18px 20px;font-size:15px;font-weight:700;color:var(--accent);border-bottom:1px solid var(--border);margin-bottom:12px;display:flex;align-items:center;justify-content:space-between}
.sidebar-close{display:none;background:none;border:none;color:var(--muted);font-size:18px;cursor:pointer;padding:4px}
.nav-item{display:flex;align-items:center;gap:10px;padding:10px 18px;color:var(--muted);font-size:13px;cursor:pointer;border-radius:0;transition:.15s;text-decoration:none;border:none;background:none;width:100%;text-align:left}
.nav-item:hover,.nav-item.active{background:rgba(124,106,247,.12);color:var(--text)}
.nav-item.active{border-left:3px solid var(--accent);color:var(--accent)}
.nav-item i{width:16px;text-align:center}
.main{margin-left:var(--sidebar-w);flex:1;padding:24px;min-height:100vh;transition:margin-left .3s ease}
.top-bar{display:none;align-items:center;gap:12px;margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid var(--border)}
.hamburger{background:none;border:none;color:var(--text);font-size:20px;cursor:pointer;padding:4px}
.page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:8px}
.page-title{font-size:18px;font-weight:600}
.section{display:none}.section.active{display:block}
.panel{background:var(--bg2);border:1px solid var(--border);border-radius:10px;overflow:hidden;margin-bottom:16px}
.panel-head{padding:14px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px}
.panel-title{font-size:13px;font-weight:600;display:flex;align-items:center;gap:8px}
.stats-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(130px,1fr));gap:12px;margin-bottom:20px}
.stat-card{background:var(--bg2);border:1px solid var(--border);border-radius:10px;padding:16px;cursor:pointer;transition:.15s}
.stat-card:hover{border-color:var(--accent)}
.stat-num{font-size:26px;font-weight:700;color:var(--accent)}
.stat-label{font-size:11px;color:var(--muted);margin-top:4px}
.duo-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px}
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
.be:hover{opacity:.85}
.bds{background:transparent;border:1px solid var(--border);color:var(--muted);padding:5px 12px;border-radius:6px;font-size:12px;cursor:pointer}
.tbl-wrap{overflow-x:auto;-webkit-overflow-scrolling:touch}
.tbl{width:100%;border-collapse:collapse;font-size:12px;min-width:480px}
.tbl th{padding:10px 12px;text-align:left;color:var(--muted);font-weight:500;border-bottom:1px solid var(--border);font-size:11px;white-space:nowrap}
.tbl td{padding:10px 12px;border-bottom:1px solid var(--border);vertical-align:middle}
.tbl tr:last-child td{border-bottom:none}
.tbl tbody tr:hover{background:rgba(255,255,255,.02)}
.di{background:var(--bg);border:1px solid var(--border);color:var(--text);padding:7px 10px;border-radius:6px;font-size:12px;width:100%}
.di:focus{outline:none;border-color:var(--accent)}
.form-label{font-size:11px;color:var(--muted);margin-bottom:4px;display:block}
.prog-bar{height:8px;background:var(--border);border-radius:4px;overflow:hidden}
.prog-fill{height:100%;background:var(--accent);border-radius:4px}
.flash{padding:10px 16px;border-radius:8px;margin-bottom:16px;font-size:12px}
.flash-ok{background:rgba(59,185,122,.15);border:1px solid rgba(59,185,122,.3);color:var(--green)}
.flash-err{background:rgba(229,85,85,.15);border:1px solid rgba(229,85,85,.3);color:var(--red)}
.cert-badge{background:rgba(245,158,11,.12);border:1px solid rgba(245,158,11,.3);color:#f59e0b;padding:4px 10px;border-radius:20px;font-size:11px;display:inline-flex;align-items:center;gap:5px}
.lock-banner{background:rgba(229,85,85,.08);border:1px solid rgba(229,85,85,.25);border-radius:8px;padding:12px 16px;font-size:12px;color:var(--red);display:flex;align-items:center;gap:10px;margin-bottom:12px}
.active-instr-banner{background:rgba(124,106,247,.08);border:1px solid rgba(124,106,247,.3);border-radius:8px;padding:12px 16px;font-size:12px;color:var(--accent);display:flex;align-items:center;gap:10px;margin-bottom:12px}
.grade-bar{height:6px;background:var(--border);border-radius:3px;overflow:hidden;margin-top:3px}
.grade-fill{height:100%;border-radius:3px}
.instr-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:12px}
.instr-card{background:var(--bg3);border:1px solid var(--border);border-radius:10px;padding:16px;display:flex;flex-direction:column;gap:10px;transition:.15s;cursor:pointer}
.instr-card:hover{border-color:var(--accent);transform:translateY(-2px)}
.avatar{width:44px;height:44px;border-radius:50%;background:var(--accent);display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:700;color:#fff;flex-shrink:0}
.course-section-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);padding:8px 12px;border-bottom:1px solid var(--border);background:var(--bg3)}
.stars-input label{color:var(--border);cursor:pointer;font-size:20px}
.stars-input input:checked ~ label,.stars-input label:hover,.stars-input label:hover ~ label{color:var(--amber)}
.stars-input{display:flex;flex-direction:row-reverse;justify-content:flex-end}
.sb-analytics{padding:14px 14px 0;border-top:1px solid var(--border);margin-top:8px}
.sb-analytics-title{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:10px;display:flex;align-items:center;gap:6px}
.sb-stat{display:flex;justify-content:space-between;align-items:center;padding:7px 0;border-bottom:1px solid rgba(42,49,71,.5)}
.sb-stat:last-child{border-bottom:none}
.sb-stat-label{font-size:11px;color:var(--muted)}
.sb-stat-val{font-size:12px;font-weight:700;color:var(--text)}
.sb-mini-bar{height:4px;background:var(--border);border-radius:2px;overflow:hidden;margin-top:3px}
.sb-mini-fill{height:100%;border-radius:2px;background:var(--accent)}
.sidebar-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:150}

@media(max-width:900px){.duo-grid{grid-template-columns:1fr}}
@media(max-width:768px){
    .sidebar{transform:translateX(-100%)}
    .sidebar.open{transform:translateX(0)}
    .sidebar-close{display:block}
    .sidebar-overlay.open{display:block}
    .main{margin-left:0;padding:16px}
    .top-bar{display:flex}
    .page-header{flex-direction:column;align-items:flex-start}
    .stats-row{grid-template-columns:repeat(2,1fr)}
    .instr-grid{grid-template-columns:1fr}
    .profile-grid{grid-template-columns:1fr!important}
    .chat-layout{flex-direction:column!important;height:auto!important}
    .chat-contacts{width:100%!important}
}
@media(max-width:480px){
    .stats-row{grid-template-columns:repeat(2,1fr)}
    .panel-head{flex-direction:column;align-items:flex-start}
}
</style>
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<div class="sidebar" id="sidebar">
    <div class="brand">
        <span><i class="fa-solid fa-car me-2"></i>ABROT Student</span>
        <button class="sidebar-close" onclick="closeSidebar()"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <a class="nav-item <?= $activeSection==='dashboard'?'active':'' ?>" onclick="showSection('dashboard')" href="#">
        <i class="fa-solid fa-gauge"></i> Dashboard</a>
    <a class="nav-item <?= in_array($activeSection,['instructors','view_instr'])?'active':'' ?>" onclick="showSection('instructors')" href="#">
        <i class="fa-solid fa-users"></i> Browse Instructors</a>
    <a class="nav-item <?= $activeSection==='my_appointments'?'active':'' ?>" onclick="showSection('my_appointments')" href="#">
        <i class="fa-solid fa-calendar-check"></i> My Appointments</a>
    <a class="nav-item <?= $activeSection==='progress'?'active':'' ?>" onclick="showSection('progress')" href="#">
        <i class="fa-solid fa-chart-line"></i> My Progress</a>
    <a class="nav-item <?= $activeSection==='messages'?'active':'' ?>" onclick="showSection('messages')" href="#">
        <i class="fa-solid fa-bell"></i> Notifications
        <?php if($unreadCnt>0): ?><span class="tag tag-amber"><?= $unreadCnt ?></span><?php endif; ?></a>
    <a class="nav-item <?= $activeSection==='chat'?'active':'' ?>" onclick="showSection('chat')" href="#">
        <i class="fa-solid fa-comments"></i> Messages
        <?php if($totalUnreadMsgS>0): ?><span class="tag tag-blue"><?= $totalUnreadMsgS ?></span><?php endif; ?></a>
    <a class="nav-item <?= $activeSection==='profile'?'active':'' ?>" onclick="showSection('profile')" href="#">
        <i class="fa-solid fa-user"></i> Profile</a>
    <div style="flex:1"></div>

    <div class="sb-analytics">
        <div class="sb-analytics-title"><i class="fa-solid fa-chart-pie" style="color:var(--accent)"></i> My Analytics</div>
        <div class="sb-stat"><div><div class="sb-stat-label">Total Hours</div><div class="sb-mini-bar" style="width:80px"><div class="sb-mini-fill" style="width:<?= min(100,$sbTotalHrs/50*100) ?>%"></div></div></div><div class="sb-stat-val" style="color:var(--accent)"><?= $sbTotalHrs ?>h</div></div>
        <div class="sb-stat"><div><div class="sb-stat-label">TDC Progress</div><div class="sb-mini-bar" style="width:80px"><div class="sb-mini-fill" style="width:<?= $sbTDCProgress ?>%;background:var(--amber)"></div></div></div><div class="sb-stat-val" style="color:var(--amber)"><?= $sbTDCProgress ?>%</div></div>
        <div class="sb-stat"><div><div class="sb-stat-label">PDC Hours</div><div class="sb-mini-bar" style="width:80px"><div class="sb-mini-fill" style="width:<?= min(100,$sbPDCHrs/8*100) ?>%;background:var(--green)"></div></div></div><div class="sb-stat-val" style="color:var(--green)"><?= $sbPDCHrs ?>h</div></div>
        <div class="sb-stat"><div class="sb-stat-label">Sessions Done</div><div class="sb-stat-val"><?= $sbSessions ?></div></div>
        <div class="sb-stat"><div class="sb-stat-label">This Month</div><div class="sb-stat-val" style="color:var(--green)"><?= $sbThisMonthHrs ?>h</div></div>
        <?php if($sbAvgGradeSb): ?><div class="sb-stat"><div><div class="sb-stat-label">Avg Grade</div><div class="sb-mini-bar" style="width:80px"><div class="sb-mini-fill" style="width:<?= $sbAvgGradeSb ?>%;background:<?= $sbGradeColor ?>"></div></div></div><div class="sb-stat-val" style="color:<?= $sbGradeColor ?>"><?= $sbAvgGradeSb ?></div></div><?php endif; ?>
        <div class="sb-stat"><div class="sb-stat-label">Courses Done</div><div class="sb-stat-val" style="color:var(--green)"><?= $sbCoursesDone ?></div></div>
    </div>
    <a class="nav-item" href="../student_logout.php"><i class="fa-solid fa-sign-out-alt"></i> Logout</a>
</div>

<div class="main">
<!-- Mobile top bar -->
<div class="top-bar">
    <button class="hamburger" onclick="openSidebar()"><i class="fa-solid fa-bars"></i></button>
    <span style="font-size:15px;font-weight:600;color:var(--accent)"><i class="fa-solid fa-car me-1"></i>Student</span>
    <span style="margin-left:auto;font-size:12px;color:var(--muted)"><?= htmlspecialchars($student['fullname']) ?></span>
</div>

<div class="page-header">
    <div class="page-title" id="page-title-text">Dashboard</div>
    <div style="font-size:12px;color:var(--muted)">Welcome, <?= htmlspecialchars($student['fullname']) ?></div>
</div>

<?php if($flash): ?><div class="flash flash-ok"><i class="fa-solid fa-circle-check me-2"></i><?= htmlspecialchars($flash) ?></div><?php endif; ?>
<?php if($flashErr): ?><div class="flash flash-err"><i class="fa-solid fa-circle-exclamation me-2"></i><?= htmlspecialchars($flashErr) ?></div><?php endif; ?>

<!-- ══ DASHBOARD ══════════════════════════════════════════════ -->
<div id="section-dashboard" class="section <?= $activeSection==='dashboard'?'active':'' ?>">
<?php
$totalAppts=(int)q($conn,"SELECT COUNT(*) AS c FROM appointments WHERE student_id=$student_id")->fetch_assoc()['c'];
$activeAppts=(int)q($conn,"SELECT COUNT(*) AS c FROM appointments WHERE student_id=$student_id AND status IN('approved','in_progress')")->fetch_assoc()['c'];
$completedAppts=(int)q($conn,"SELECT COUNT(*) AS c FROM appointments WHERE student_id=$student_id AND status='completed'")->fetch_assoc()['c'];
$hasCert=(int)q($conn,"SELECT COUNT(*) AS c FROM tdc_certificates WHERE student_id=$student_id")->fetch_assoc()['c'];
?>
<div class="stats-row">
    <div class="stat-card" onclick="showSection('my_appointments')"><div class="stat-num"><?= $totalAppts ?></div><div class="stat-label">Total Appointments</div></div>
    <div class="stat-card" onclick="showSection('my_appointments')"><div class="stat-num" style="color:var(--amber)"><?= $activeAppts ?></div><div class="stat-label">Active</div></div>
    <div class="stat-card" onclick="showSection('my_appointments')"><div class="stat-num" style="color:var(--green)"><?= $completedAppts ?></div><div class="stat-label">Completed</div></div>
    <div class="stat-card"><div class="stat-num" style="color:var(--amber)"><?= $hasCert?'✓':'–' ?></div><div class="stat-label">TDC Certificate</div></div>
    <?php if($avgGrade): $gc=$avgGrade>=80?'var(--green)':($avgGrade>=60?'var(--amber)':'var(--red)'); ?><div class="stat-card" onclick="showSection('progress')"><div class="stat-num" style="color:<?= $gc ?>"><?= $avgGrade ?></div><div class="stat-label">Avg Grade</div></div><?php endif; ?>
</div>

<?php if($activeInstructor): ?>
<div class="active-instr-banner"><i class="fa-solid fa-chalkboard-user"></i><span>You are currently enrolled with <strong><?= htmlspecialchars($activeInstructor['fullname']) ?></strong>. Complete all sessions to book a new instructor.</span></div>
<?php elseif(!$tdcDone): ?>
<div class="lock-banner"><i class="fa-solid fa-lock"></i><span>Complete <strong>15 hours of TDC</strong> first to unlock PDC courses.</span></div>
<?php else: ?>
<div class="flash flash-ok mb-3"><i class="fa-solid fa-certificate me-2"></i>TDC complete — PDC courses are now unlocked!</div>
<?php endif; ?>

<div class="panel">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-calendar-check"></i> Recent Appointments</div>
        <a class="be" onclick="showSection('my_appointments')" href="#" style="font-size:11px">View All</a></div>
    <div class="tbl-wrap"><table class="tbl"><thead><tr><th>Course</th><th>Instructor</th><th>Start Date</th><th>Hours</th><th>Status</th></tr></thead><tbody>
    <?php $recentA=q($conn,"SELECT a.*,ic.course_type,i.fullname AS iname FROM appointments a JOIN instructor_courses ic ON ic.course_id=a.course_id JOIN instructors i ON i.instructor_id=a.instructor_id WHERE a.student_id=$student_id ORDER BY a.requested_at DESC LIMIT 5");
    if($recentA->num_rows===0): ?><tr><td colspan="5" style="color:var(--muted);text-align:center;padding:24px">No appointments yet. <a href="#" onclick="showSection('instructors')">Browse instructors</a> to book one.</td></tr>
    <?php else: while($ra=$recentA->fetch_assoc()): $tc=$cColors[$ra['course_type']]??'tag-gray'; ?>
    <tr><td><span class="tag <?= $tc ?>"><?= htmlspecialchars($ra['course_type']) ?></span></td><td><?= htmlspecialchars($ra['iname']) ?></td><td><?= date('M d, Y',strtotime($ra['start_date'])) ?></td><td><?= $ra['total_hours'] ?> hrs</td><td><span class="<?= $scmS[$ra['status']]??'bkd' ?>"><?= $scmL[$ra['status']]??$ra['status'] ?></span></td></tr>
    <?php endwhile; endif; ?></tbody></table></div>
</div>

<!-- Charts -->
<div style="font-size:12px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin:20px 0 12px;padding-bottom:6px;border-bottom:1px solid var(--border)">
    <i class="fa-solid fa-chart-line me-2" style="color:var(--accent)"></i>My Learning Analytics
</div>
<div class="duo-grid">
    <div class="panel"><div class="panel-head"><div class="panel-title"><i class="fa-solid fa-chart-bar me-1"></i> TDC vs PDC Hours – Last 6 Months</div></div>
        <div style="padding:16px;height:200px;position:relative"><canvas id="stuChartHours"></canvas></div></div>
    <div class="panel"><div class="panel-head"><div class="panel-title"><i class="fa-solid fa-chart-pie me-1"></i> Training Progress</div></div>
        <div style="padding:16px;display:flex;align-items:center;gap:16px;min-height:200px;flex-wrap:wrap">
            <div style="width:150px;height:150px;position:relative;flex-shrink:0"><canvas id="stuChartProgress"></canvas></div>
            <div style="flex:1;min-width:120px;font-size:12px;display:flex;flex-direction:column;gap:10px">
                <div><div style="color:var(--amber);font-weight:700;font-size:18px"><?= $sbTDCHrsPre ?>/15h</div><div style="color:var(--muted);font-size:11px">TDC Progress (<?= $sbTDCProgressPre ?>%)</div></div>
                <div><div style="color:var(--green);font-weight:700;font-size:18px"><?= $sbPDCHrsPre ?>h</div><div style="color:var(--muted);font-size:11px">PDC Hours</div></div>
                <div><div style="color:var(--accent);font-weight:700;font-size:18px"><?= $sbSessionsPre ?></div><div style="color:var(--muted);font-size:11px">Sessions Done</div></div>
            </div>
        </div>
    </div>
</div>
<?php if(count($stuGradeTrend)>=2): ?>
<div class="panel">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-chart-line me-1"></i> My Grade Trend</div></div>
    <div style="padding:16px;height:180px;position:relative"><canvas id="stuChartGrades"></canvas></div>
</div>
<?php endif; ?>
</div>

<!-- ══ BROWSE INSTRUCTORS ═════════════════════════════════════ -->
<div id="section-instructors" class="section <?= $activeSection==='instructors'?'active':'' ?>">
<div class="panel-head" style="background:var(--bg2);border-radius:10px;margin-bottom:16px;border:1px solid var(--border)">
    <div class="panel-title"><i class="fa-solid fa-users"></i> Available Instructors</div>
    <input type="text" class="di" style="width:200px;max-width:100%" placeholder="Search instructors…" oninput="handleSearch(this.value)">
</div>
<?php if($activeInstructor): ?>
<div class="active-instr-banner"><i class="fa-solid fa-info-circle"></i><span>You have an active appointment. You can browse instructors but cannot book until your current sessions are complete.</span></div>
<?php elseif(!$tdcDone): ?>
<div class="lock-banner"><i class="fa-solid fa-info-circle"></i><span>You can book any TDC course. PDC courses unlock after completing 15 hrs of TDC.</span></div>
<?php endif; ?>
<div class="instr-grid">
<?php $allInstructors->data_seek(0); while($ins=$allInstructors->fetch_assoc()): $certOk=$ins['certificate_image']&&file_exists($uploadDir.$ins['certificate_image']); ?>
<div class="instr-card" onclick="viewInstructor(<?= $ins['instructor_id'] ?>)">
    <div style="display:flex;align-items:center;gap:12px">
        <div class="avatar"><?= strtoupper(substr($ins['fullname'],0,1)) ?></div>
        <div style="flex:1"><div style="font-size:13px;font-weight:600"><?= htmlspecialchars($ins['fullname']) ?></div>
        <?php if($certOk): ?><div style="font-size:11px;color:var(--green);margin-top:2px"><i class="fa-solid fa-certificate me-1"></i>Certified</div><?php endif; ?></div>
        <?php if($ins['avg_rating']): ?><div style="text-align:right"><div style="color:var(--amber);font-size:13px">★ <?= $ins['avg_rating'] ?></div><div style="font-size:10px;color:var(--muted)"><?= $ins['rating_count'] ?> reviews</div></div><?php endif; ?>
    </div>
    <div style="font-size:11px;color:var(--muted);display:flex;flex-direction:column;gap:3px">
        <div><i class="fa-solid fa-envelope me-1"></i><?= htmlspecialchars($ins['email']) ?></div>
        <?php if(!empty($ins['phone'])): ?><div><i class="fa-solid fa-phone me-1"></i><?= htmlspecialchars($ins['phone']) ?></div><?php endif; ?>
    </div>
    <div style="display:flex;flex-wrap:wrap;gap:4px">
        <?php foreach(explode('|',$ins['courses']??'') as $ct): if(!$ct)continue; $tc=$cColors[$ct]??'tag-gray'; ?><span class="tag <?= $tc ?>"><?= htmlspecialchars($ct) ?></span><?php endforeach; ?>
    </div>
    <div style="font-size:11px;color:var(--accent)">View All Courses & Book →</div>
</div>
<?php endwhile; ?>
</div>
</div>

<!-- ══ VIEW INSTRUCTOR ════════════════════════════════════════ -->
<div id="section-view_instr" class="section <?= $activeSection==='view_instr'?'active':'' ?>">
<?php if($viewInstr): $certOk2=!empty($viewInstr['certificate_image'])&&file_exists($uploadDir.$viewInstr['certificate_image']); ?>
<a href="#" onclick="showSection('instructors')" class="bds" style="display:inline-flex;align-items:center;gap:6px;margin-bottom:12px"><i class="fa-solid fa-arrow-left"></i> Back to Instructors</a>
<div class="panel" style="margin-top:12px;margin-bottom:16px">
    <div class="panel-head">
        <div class="panel-title">
            <div class="avatar" style="width:36px;height:36px;font-size:14px"><?= strtoupper(substr($viewInstr['fullname'],0,1)) ?></div>
            <?= htmlspecialchars($viewInstr['fullname']) ?>
        </div>
        <?php if($viewInstr['avg_rating']): ?><div style="color:var(--amber)">★ <?= $viewInstr['avg_rating'] ?> <span style="font-size:11px;color:var(--muted)">(<?= $viewInstr['rating_count'] ?> reviews)</span></div><?php endif; ?>
    </div>
    <div style="padding:16px;display:flex;gap:20px;align-items:flex-start;flex-wrap:wrap">
        <!-- Info grid -->
        <div style="flex:1;min-width:260px;display:grid;grid-template-columns:1fr 1fr;gap:12px">
            <div><span style="font-size:11px;color:var(--muted)">Email</span><div style="font-size:13px"><?= htmlspecialchars($viewInstr['email']) ?></div></div>
            <div><span style="font-size:11px;color:var(--muted)">Mobile</span><div style="font-size:13px"><?= !empty($viewInstr['mobile'])?htmlspecialchars($viewInstr['mobile']):'—' ?></div></div>
            <div><span style="font-size:11px;color:var(--muted)">Joined</span><div style="font-size:13px"><?= !empty($viewInstr['created_at'])?date('F d, Y',strtotime($viewInstr['created_at'])):'—' ?></div></div>
            <div><span style="font-size:11px;color:var(--muted)">Certificates Issued</span>
                <div style="display:flex;gap:6px;margin-top:3px;flex-wrap:wrap">
                    <?php if($viewInstrTDCCerts): ?><span class="cert-badge"><i class="fa-solid fa-certificate"></i> TDC: <?= $viewInstrTDCCerts ?></span><?php endif; ?>
                    <?php if($viewInstrPDCCerts): ?><span class="cert-badge" style="color:var(--green)"><i class="fa-solid fa-certificate"></i> PDC: <?= $viewInstrPDCCerts ?></span><?php endif; ?>
                    <?php if(!$viewInstrTDCCerts && !$viewInstrPDCCerts): ?>—<?php endif; ?>
                </div>
            </div>
            <?php if(!empty($viewInstr['bio'])): ?>
            <div style="grid-column:1/-1"><span style="font-size:11px;color:var(--muted)">Bio</span><div style="font-size:12px;margin-top:2px"><?= nl2br(htmlspecialchars($viewInstr['bio'])) ?></div></div>
            <?php endif; ?>
            <?php if($certOk2): ?>
            <div style="grid-column:1/-1">
                <span style="font-size:11px;color:var(--muted)">Instructor Certificate</span>
                <div style="margin-top:6px">
                    <img src="<?= htmlspecialchars($uploadDir.$viewInstr['certificate_image']) ?>" style="max-height:100px;border-radius:6px;border:1px solid var(--border);display:block;margin-bottom:6px">
                    <a href="<?= 'view_image.php?type=certificates&file='.urlencode($viewInstr['certificate_image']).'&back='.urlencode($_SERVER['REQUEST_URI']) ?>" class="bds" style="font-size:11px"><i class="fa-solid fa-eye me-1"></i>View</a>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <!-- Stats -->
        <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-start">
            <div style="background:var(--bg3);border-radius:10px;padding:12px 18px;text-align:center;min-width:80px"><div style="font-size:20px;font-weight:700;color:var(--accent)"><?= $viewInstrTotalStudents ?></div><div style="font-size:10px;color:var(--muted)">Students</div></div>
            <div style="background:var(--bg3);border-radius:10px;padding:12px 18px;text-align:center;min-width:80px"><div style="font-size:20px;font-weight:700;color:var(--green)"><?= $viewInstrTotalHrs ?>h</div><div style="font-size:10px;color:var(--muted)">Hours Taught</div></div>
            <div style="background:var(--bg3);border-radius:10px;padding:12px 18px;text-align:center;min-width:80px"><div style="font-size:20px;font-weight:700;color:var(--text)"><?= $viewInstrTotalSess ?></div><div style="font-size:10px;color:var(--muted)">Sessions</div></div>
            <?php if($viewInstr['avg_rating']): ?><div style="background:var(--bg3);border-radius:10px;padding:12px 18px;text-align:center;min-width:80px"><div style="font-size:20px;font-weight:700;color:var(--amber)">★ <?= $viewInstr['avg_rating'] ?></div><div style="font-size:10px;color:var(--muted)">Avg Rating</div></div><?php endif; ?>
        </div>
    </div>
</div>

<?php if($instrAllCourses):
    $instrAllCourses->data_seek(0);
    $tdcCourses=[]; $pdcCourses=[];
    while($c=$instrAllCourses->fetch_assoc()){ if(stripos($c['course_type'],'TDC')===0)$tdcCourses[]=$c; else $pdcCourses[]=$c; }

    // Build slot map: course_id → [date => [booked,limit]]
    $slotMap=[];
    foreach(array_merge($tdcCourses,$pdcCourses) as $c){
        $slotMap[$c['course_id']]=[];
        if(!empty($c['upcoming_slots'])){
            foreach(explode('|',$c['upcoming_slots']) as $entry){
                $parts=explode(':',$entry);
                if(count($parts)===3) $slotMap[$c['course_id']][$parts[0]]=['booked'=>(int)$parts[1],'limit'=>(int)$parts[2]];
            }
        }
    }
?>
<div class="panel">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-book-open"></i> All Courses Offered</div></div>
    <?php if(!empty($tdcCourses)): ?>
    <div class="course-section-label"><i class="fa-solid fa-book me-2" style="color:#f59e0b"></i>TDC – Theoretical Driving Course (Saturday & Sunday only)</div>
    <div class="tbl-wrap"><table class="tbl"><thead><tr><th>Course</th><th>Price/Hr</th><th>Hours</th><th>Total</th><th>Slots</th><th></th></tr></thead><tbody>
    <?php foreach($tdcCourses as $c): $tc=$cColors[$c['course_type']]??'tag-gray';
        $slotJson=json_encode($slotMap[$c['course_id']]??[]);
        $limit=$c['student_limit']??null;
    ?>
    <tr><td><span class="tag <?= $tc ?>"><?= htmlspecialchars($c['course_type']) ?></span></td>
        <td>₱<?= number_format($c['price_per_hr'],2) ?></td><td><strong>15 hrs</strong></td>
        <td>₱<?= number_format($c['price_per_hr']*15,2) ?></td>
        <td><?php if($limit): ?><span style="font-size:11px;color:var(--muted)"><i class="fa-solid fa-users me-1"></i><?= $limit ?>/date</span><?php else: ?><span style="font-size:11px;color:var(--muted)">Unlimited</span><?php endif; ?></td>
        <td><?php if($activeInstructor): ?><span style="font-size:11px;color:var(--muted)">Active appt</span>
        <?php elseif($tdcDone): ?><span style="font-size:11px;color:var(--green)"><i class="fa-solid fa-check me-1"></i>Complete</span>
        <?php else: ?><button class="be" onclick="openBookModal(<?= $viewInstr['instructor_id'] ?>,<?= $c['course_id'] ?>,'<?= addslashes($c['course_type']) ?>',<?= $c['price_per_hr'] ?>,true,<?= $limit??0 ?>,<?= htmlspecialchars($slotJson,ENT_QUOTES) ?>)" style="font-size:11px"><i class="fa-solid fa-calendar-plus"></i> Book</button><?php endif; ?></td></tr>
    <?php endforeach; ?></tbody></table></div>
    <?php endif; ?>
    <?php if(!empty($pdcCourses)): ?>
    <div class="course-section-label"><i class="fa-solid fa-car me-2" style="color:#3cb97a"></i>PDC – Practical Driving Course <?= !$tdcDone?'<span style="color:var(--red)">(Locked – Complete TDC first)</span>':'' ?></div>
    <div class="tbl-wrap"><table class="tbl"><thead><tr><th>Course</th><th>Price/Hr</th><th>Hours</th><th>Total</th><th>Slots</th><th></th></tr></thead><tbody>
    <?php foreach($pdcCourses as $c): $locked=!$tdcDone;
        $slotJson=json_encode($slotMap[$c['course_id']]??[]);
        $limit=$c['student_limit']??null;
    ?>
    <tr><td><span class="tag <?= $cColors[$c['course_type']]??'tag-gray' ?>"><?= htmlspecialchars($c['course_type']) ?></span></td>
        <td>₱<?= number_format($c['price_per_hr'],2) ?></td><td>Flexible</td><td>Based on hours</td>
        <td><?php if($limit): ?><span style="font-size:11px;color:var(--muted)"><i class="fa-solid fa-users me-1"></i><?= $limit ?>/date</span><?php else: ?><span style="font-size:11px;color:var(--muted)">Unlimited</span><?php endif; ?></td>
        <td><?php if($locked): ?><span style="font-size:11px;color:var(--red)"><i class="fa-solid fa-lock me-1"></i>TDC first</span>
        <?php elseif($activeInstructor): ?><span style="font-size:11px;color:var(--muted)">Active appt</span>
        <?php else: ?><button class="be" onclick="openBookModal(<?= $viewInstr['instructor_id'] ?>,<?= $c['course_id'] ?>,'<?= addslashes($c['course_type']) ?>',<?= $c['price_per_hr'] ?>,false,<?= $limit??0 ?>,<?= htmlspecialchars($slotJson,ENT_QUOTES) ?>)" style="font-size:11px"><i class="fa-solid fa-calendar-plus"></i> Book</button><?php endif; ?></td></tr>
    <?php endforeach; ?></tbody></table></div>
    <?php endif; ?>
</div>

<!-- Reviews -->
<?php $reviews=q($conn,"SELECT ir.*,s.fullname AS sname FROM instructor_ratings ir JOIN students s ON s.student_id=ir.student_id WHERE ir.instructor_id={$viewInstr['instructor_id']} ORDER BY ir.rated_at DESC LIMIT 5");
if($reviews->num_rows>0): ?>
<div class="panel">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-star"></i> Student Reviews</div></div>
    <div style="padding:12px 16px;display:flex;flex-direction:column;gap:12px">
    <?php while($rv=$reviews->fetch_assoc()): ?>
    <div style="padding:10px;background:var(--bg3);border-radius:8px">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px">
            <div style="font-size:12px;font-weight:600"><?= htmlspecialchars($rv['sname']) ?></div>
            <div style="color:var(--amber);font-size:12px"><?= stars($rv['rating']) ?></div>
            <div style="font-size:10px;color:var(--muted);margin-left:auto"><?= date('M d, Y',strtotime($rv['rated_at'])) ?></div>
        </div>
        <?php if($rv['comment']): ?><div style="font-size:12px;color:var(--muted)"><?= nl2br(htmlspecialchars($rv['comment'])) ?></div><?php endif; ?>
    </div>
    <?php endwhile; ?></div>
</div>
<?php endif; ?>
<?php endif; ?>
<?php else: ?><p style="color:var(--muted)">Instructor not found.</p><?php endif; ?>
</div>

<!-- ══ MY APPOINTMENTS ════════════════════════════════════════ -->
<div id="section-my_appointments" class="section <?= $activeSection==='my_appointments'?'active':'' ?>">
<div class="panel">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-calendar-check"></i> My Appointments</div></div>
    <div class="tbl-wrap"><table class="tbl"><thead><tr><th>Course</th><th>Instructor</th><th>Start</th><th>Progress</th><th>Grade</th><th>Status</th><th>Cert</th><th>Action</th></tr></thead><tbody>
    <?php $myAppts->data_seek(0); if($myAppts->num_rows===0): ?>
    <tr><td colspan="8" style="color:var(--muted);text-align:center;padding:24px">No appointments yet. <a href="#" onclick="showSection('instructors')">Browse instructors</a> to book one.</td></tr>
    <?php else: while($ap=$myAppts->fetch_assoc()):
        $tc=$cColors[$ap['course_type']]??'tag-gray';
        $hoursDone=(float)($ap['hours_done']??0);
        $pct=min(100,round($hoursDone/$ap['total_hours']*100));
        $isTDC=stripos($ap['course_type'],'TDC')===0;
        $gc=isset($ap['tdc_overall_grade'])&&$ap['tdc_overall_grade']?($ap['tdc_overall_grade']>=80?'var(--green)':($ap['tdc_overall_grade']>=60?'var(--amber)':'var(--red)')):'';
    ?>
    <tr>
        <td><span class="tag <?= $tc ?>"><?= htmlspecialchars($ap['course_type']) ?></span></td>
        <td><?= htmlspecialchars($ap['instr_name']) ?></td>
        <td style="font-size:11px;white-space:nowrap"><?= date('M d, Y',strtotime($ap['start_date'])) ?></td>
        <td style="min-width:80px"><div class="prog-bar"><div class="prog-fill" style="width:<?= $pct ?>%"></div></div><div style="font-size:10px;color:var(--muted);margin-top:2px"><?= $hoursDone ?>/<?= $ap['total_hours'] ?>h</div></td>
        <td><?= ($isTDC&&isset($ap['tdc_overall_grade'])&&$ap['tdc_overall_grade'])?'<strong style="color:'.$gc.'">'.$ap['tdc_overall_grade'].'/100</strong>':'—' ?></td>
        <td><span class="<?= $scmS[$ap['status']]??'bkd' ?>"><?= $scmL[$ap['status']]??$ap['status'] ?></span></td>
        <td><?php if($ap['cert_id']): ?><span class="cert-badge"><i class="fa-solid fa-certificate"></i>
            <?php if(isset($ap['cert_delivery'])&&$ap['cert_delivery']==='online'&&!empty($ap['cert_img'])&&file_exists('../assets/uploads/certs/'.$ap['cert_img'])): ?><a href="<?= 'view_image.php?type=certs&file='.urlencode($ap['cert_img']).'&back='.urlencode($_SERVER['REQUEST_URI']) ?>" style="color:inherit;text-decoration:none;font-size:11px"><i class="fa-solid fa-eye me-1"></i>View</a> <a href="<?= '../assets/uploads/certs/'.htmlspecialchars($ap['cert_img']) ?>" download style="color:inherit;text-decoration:none;font-size:11px"><i class="fa-solid fa-download me-1"></i>Download</a><?php else: ?>Issued<?php endif; ?></span><?php else: ?>—<?php endif; ?></td>
        <td style="display:flex;gap:6px;flex-wrap:wrap">
            <?php if(in_array($ap['status'],['pending','approved','in_progress'])):
                $isActiveAppt = in_array($ap['status'],['approved','in_progress']);
                $confirmMsg = $isActiveAppt
                    ? 'Cancel this appointment? Your instructor will be notified and your slot for this date will be freed up.'
                    : 'Cancel this appointment?';
            ?>
            <form method="POST" onsubmit="return confirm('<?= $confirmMsg ?>')"><input type="hidden" name="action" value="cancel_appointment"><input type="hidden" name="appointment_id" value="<?= $ap['appointment_id'] ?>"><button type="submit" class="<?= $isActiveAppt?'ber':'bds' ?>" style="font-size:11px"><i class="fa-solid fa-xmark me-1"></i>Cancel</button></form>
            <?php endif; ?>
            <?php if($ap['status']==='completed' && !$ap['my_rating']): ?>
            <button class="be" style="font-size:11px" onclick="openRateModal(<?= $ap['appointment_id'] ?>,'<?= addslashes($ap['instr_name']) ?>')"><i class="fa-solid fa-star"></i> Rate</button>
            <?php elseif($ap['my_rating']): ?><span style="color:var(--amber);font-size:12px"><?= stars($ap['my_rating']) ?></span><?php endif; ?>
        </td>
    </tr>
    <?php endwhile; endif; ?></tbody></table></div>
</div>
</div>

<!-- ══ MY PROGRESS ════════════════════════════════════════════ -->
<div id="section-progress" class="section <?= $activeSection==='progress'?'active':'' ?>">
<?php
$tdcHours=(float)q($conn,"SELECT COALESCE(SUM(s.hours_covered),0) AS h FROM sessions s JOIN appointments a ON a.appointment_id=s.appointment_id JOIN instructor_courses ic ON ic.course_id=a.course_id WHERE s.student_id=$student_id AND ic.course_type IN('TDC Face-to-Face','TDC Online') AND s.status='completed'")->fetch_assoc()['h'];
$pdcHours=(float)q($conn,"SELECT COALESCE(SUM(s.hours_covered),0) AS h FROM sessions s JOIN appointments a ON a.appointment_id=s.appointment_id JOIN instructor_courses ic ON ic.course_id=a.course_id WHERE s.student_id=$student_id AND ic.course_type LIKE 'PDC%' AND s.status='completed'")->fetch_assoc()['h'];
$tdcPct=min(100,round($tdcHours/15*100));
$overallGradeRow=q($conn,"SELECT tdc_overall_grade FROM appointments a JOIN instructor_courses ic ON ic.course_id=a.course_id WHERE a.student_id=$student_id AND ic.course_type LIKE 'TDC%' AND a.status='completed' AND a.tdc_overall_grade IS NOT NULL ORDER BY a.completed_at DESC LIMIT 1")->fetch_assoc();
?>
<div class="panel mb-3">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-road"></i> Training Progress</div></div>
    <div style="padding:20px;display:flex;flex-direction:column;gap:20px">
        <div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;flex-wrap:wrap;gap:4px">
                <div style="font-size:13px;font-weight:600"><span class="tag tag-amber me-2">TDC</span> Theoretical Driving Course <small style="color:var(--muted);font-size:11px">(Sat & Sun)</small></div>
                <div style="font-size:12px;color:var(--muted)"><?= $tdcHours ?>/15 hrs</div>
            </div>
            <div class="prog-bar" style="height:12px"><div class="prog-fill" style="width:<?= $tdcPct ?>%;background:<?= $tdcDone?'var(--green)':'var(--accent)' ?>"></div></div>
            <?php if($tdcDone): ?>
            <div style="margin-top:8px;display:flex;gap:12px;align-items:center;flex-wrap:wrap">
                <span class="cert-badge"><i class="fa-solid fa-certificate"></i> TDC Complete — PDC Unlocked</span>
                <?php if($overallGradeRow&&$overallGradeRow['tdc_overall_grade']): $gc=$overallGradeRow['tdc_overall_grade']>=80?'var(--green)':($overallGradeRow['tdc_overall_grade']>=60?'var(--amber)':'var(--red)'); ?><div style="font-size:12px">Overall: <strong style="color:<?= $gc ?>"><?= $overallGradeRow['tdc_overall_grade'] ?>/100</strong></div><?php endif; ?>
            </div>
            <?php else: ?><div style="font-size:11px;color:var(--muted);margin-top:4px"><?= 15-$tdcHours ?> more hours needed to unlock PDC</div><?php endif; ?>
        </div>
        <div style="<?= !$tdcDone?'opacity:.4':'' ?>">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;flex-wrap:wrap;gap:4px">
                <div style="font-size:13px;font-weight:600"><?php if(!$tdcDone): ?><i class="fa-solid fa-lock me-2" style="color:var(--red)"></i><?php endif; ?><span class="tag tag-green me-2">PDC</span> Practical Driving Course</div>
                <div style="font-size:12px;color:var(--muted)"><?= $pdcHours ?> hrs</div>
            </div>
            <?php if(!$tdcDone): ?><div style="font-size:11px;color:var(--red)">Complete TDC first</div>
            <?php else: ?><div class="prog-bar" style="height:12px"><div class="prog-fill" style="width:<?= min(100,round($pdcHours/8*100)) ?>%;background:var(--green)"></div></div><?php endif; ?>
        </div>
    </div>
</div>
<div class="panel">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-list-check"></i> Session History & Grades</div></div>
    <div class="tbl-wrap"><table class="tbl"><thead><tr><th>Date</th><th>Course</th><th>Day#</th><th>Instructor</th><th>Hours</th><th>Grade</th><th>Performance</th></tr></thead><tbody>
    <?php $mySessions->data_seek(0); if($mySessions->num_rows===0): ?>
    <tr><td colspan="7" style="color:var(--muted);text-align:center;padding:24px">No session records yet.</td></tr>
    <?php else: while($ss=$mySessions->fetch_assoc()): $tc=$cColors[$ss['course_type']]??'tag-gray'; $gc=$ss['grade']?($ss['grade']>=80?'var(--green)':($ss['grade']>=60?'var(--amber)':'var(--red)')):''; ?>
    <tr>
        <td style="white-space:nowrap"><?= date('M d, Y',strtotime($ss['session_date'])) ?></td>
        <td><span class="tag <?= $tc ?>"><?= htmlspecialchars($ss['course_type']) ?></span></td>
        <td>Day <?= $ss['session_number']??'—' ?></td>
        <td><?= htmlspecialchars($ss['instr_name']) ?></td>
        <td><?= $ss['hours_covered'] ?> hrs</td>
        <td><?php if($ss['grade']): ?><strong style="color:<?= $gc ?>"><?= $ss['grade'] ?>/100</strong><div class="grade-bar"><div class="grade-fill" style="width:<?= $ss['grade'] ?>%;background:<?= $gc ?>"></div></div><?php else: ?><span style="color:var(--muted);font-size:11px">Not graded</span><?php endif; ?></td>
        <td><?php if($ss['performance_rating']): ?><span style="color:var(--amber)"><?= stars($ss['performance_rating']) ?></span><?php else: ?>—<?php endif; ?></td>
    </tr>
    <?php endwhile; endif; ?></tbody></table></div>
</div>
</div>

<!-- ══ NOTIFICATIONS ══════════════════════════════════════════ -->
<div id="section-messages" class="section <?= $activeSection==='messages'?'active':'' ?>">
<div class="panel">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-bell"></i> Notifications</div>
        <form method="POST"><input type="hidden" name="action" value="mark_read_student"><button type="submit" class="bds" style="font-size:11px">Mark all read</button></form></div>
    <div style="padding:12px 16px;display:flex;flex-direction:column;gap:8px">
    <?php $notifs->data_seek(0); if($notifs->num_rows===0): ?>
    <p style="color:var(--muted);text-align:center;padding:20px">No notifications.</p>
    <?php else: while($n=$notifs->fetch_assoc()): ?>
    <div style="padding:10px 12px;background:<?= !$n['is_read']?'rgba(124,106,247,.06)':'var(--bg3)' ?>;border-radius:8px;border-left:3px solid <?= !$n['is_read']?'var(--accent)':'var(--border)' ?>">
        <div style="font-size:12px"><?= htmlspecialchars($n['message']) ?></div>
        <div style="font-size:10px;color:var(--muted);margin-top:3px"><?= date('M d, Y g:i A',strtotime($n['created_at'])) ?></div>
    </div>
    <?php endwhile; endif; ?></div>
</div>
</div>

<!-- ══ CHAT ══════════════════════════════════════════════════ -->
<div id="section-chat" class="section <?= $activeSection==='chat'?'active':'' ?>">
<?php $instrMap=[]; $myInstructors->data_seek(0); while($mi=$myInstructors->fetch_assoc()){ $iid=(int)$mi['instructor_id']; if(!isset($instrMap[$iid]))$instrMap[$iid]=$mi; } ?>
<div style="display:flex;gap:16px;min-height:400px" class="chat-layout">
    <div style="width:200px;flex-shrink:0;background:var(--bg2);border:1px solid var(--border);border-radius:10px;overflow-y:auto" class="chat-contacts">
        <div style="padding:10px 14px;border-bottom:1px solid var(--border);font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em">My Instructors</div>
        <?php if(empty($instrMap)): ?><div style="padding:20px;text-align:center;color:var(--muted);font-size:12px">No enrolled instructors yet.</div>
        <?php else: foreach($instrMap as $iid=>$mi): $unread=$unreadMsgMapS[$iid]??0; $isActive=($chatInstrId===$iid); ?>
        <a href="<?= $selfFile ?>?section=chat&chat_instr=<?= $iid ?>" style="display:flex;align-items:center;gap:10px;padding:10px 14px;text-decoration:none;border-bottom:1px solid var(--border);background:<?= $isActive?'rgba(124,106,247,.12)':'transparent' ?>;transition:.15s">
            <div style="width:32px;height:32px;border-radius:50%;background:var(--green);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#fff;flex-shrink:0"><?= strtoupper(substr($mi['fullname'],0,1)) ?></div>
            <div style="flex:1;min-width:0"><div style="font-size:12px;font-weight:600;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= htmlspecialchars($mi['fullname']) ?></div><div style="font-size:10px;color:var(--muted)"><?= htmlspecialchars($mi['course_type']) ?></div></div>
            <?php if($unread>0): ?><span class="tag tag-blue" style="font-size:10px"><?= $unread ?></span><?php endif; ?>
        </a>
        <?php endforeach; endif; ?>
    </div>
    <div style="flex:1;display:flex;flex-direction:column;background:var(--bg2);border:1px solid var(--border);border-radius:10px;overflow:hidden;min-height:360px">
    <?php if($chatInstrId>0&&isset($instrMap[$chatInstrId])): $cInstr=$instrMap[$chatInstrId];
        $conn->query("UPDATE messages SET is_read=1 WHERE receiver_id=$student_id AND receiver_type='student' AND sender_id=$chatInstrId AND sender_type='instructor'");
        $convRes=q($conn,"SELECT * FROM messages WHERE (sender_id=$student_id AND sender_type='student' AND receiver_id=$chatInstrId AND receiver_type='instructor') OR (sender_id=$chatInstrId AND sender_type='instructor' AND receiver_id=$student_id AND receiver_type='student') ORDER BY created_at ASC"); ?>
    <div style="padding:12px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:10px">
        <div style="width:32px;height:32px;border-radius:50%;background:var(--green);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#fff"><?= strtoupper(substr($cInstr['fullname'],0,1)) ?></div>
        <div><div style="font-size:13px;font-weight:600"><?= htmlspecialchars($cInstr['fullname']) ?></div><div style="font-size:10px;color:var(--muted)"><?= htmlspecialchars($cInstr['course_type']) ?></div></div>
    </div>
    <div style="flex:1;overflow-y:auto;padding:16px;display:flex;flex-direction:column;gap:10px" id="msg-thread">
        <?php if($convRes->num_rows===0): ?><div style="text-align:center;color:var(--muted);font-size:12px;margin:auto">No messages yet. Say hello!</div>
        <?php else: while($cv=$convRes->fetch_assoc()): $isMine=$cv['sender_type']==='student'; ?>
        <div style="display:flex;justify-content:<?= $isMine?'flex-end':'flex-start' ?>">
            <div style="max-width:72%;background:<?= $isMine?'var(--accent)':'var(--bg3)' ?>;color:<?= $isMine?'#fff':'var(--text)' ?>;padding:8px 12px;border-radius:<?= $isMine?'14px 14px 4px 14px':'14px 14px 14px 4px' ?>;font-size:12px;line-height:1.5">
                <?= nl2br(htmlspecialchars($cv['body'])) ?>
                <div style="font-size:9px;opacity:.65;margin-top:4px;text-align:right"><?= date('M d, g:i A',strtotime($cv['created_at'])) ?></div>
            </div>
        </div>
        <?php endwhile; endif; ?>
    </div>
    <form method="POST" style="padding:12px 16px;border-top:1px solid var(--border);display:flex;gap:8px;align-items:flex-end">
        <input type="hidden" name="action" value="send_message_student">
        <input type="hidden" name="to_instructor_id" value="<?= $chatInstrId ?>">
        <input type="hidden" name="appointment_id" value="<?= $cInstr['appointment_id']??0 ?>">
        <textarea name="body" class="di" rows="2" placeholder="Type a message…" style="resize:none;flex:1" required></textarea>
        <button type="submit" class="be" style="height:46px;flex-shrink:0"><i class="fa-solid fa-paper-plane"></i></button>
    </form>
    <?php else: ?>
    <div style="flex:1;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:12px;color:var(--muted)">
        <i class="fa-solid fa-comments" style="font-size:36px;opacity:.3"></i>
        <div style="font-size:13px">Select an instructor to start a conversation</div>
    </div>
    <?php endif; ?>
    </div>
</div>
</div>

<!-- ══ PROFILE ════════════════════════════════════════════════ -->
<div id="section-profile" class="section <?= $activeSection==='profile'?'active':'' ?>">
<?php $hasCertNow=(int)q($conn,"SELECT COUNT(*) AS c FROM tdc_certificates WHERE student_id=$student_id")->fetch_assoc()['c'];
$idPhotoPath='../assets/uploads/student_ids/'; ?>
<div class="panel mb-3">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-user"></i> My Profile</div></div>
    <div style="padding:20px;display:flex;gap:20px;align-items:center;flex-wrap:wrap">
        <div style="width:70px;height:70px;border-radius:50%;background:var(--accent);display:flex;align-items:center;justify-content:center;font-size:26px;font-weight:700;color:#fff;flex-shrink:0"><?= strtoupper(substr($student['fullname'],0,1)) ?></div>
        <div style="flex:1;min-width:180px">
            <div style="font-size:18px;font-weight:700"><?= htmlspecialchars($student['fullname']) ?></div>
            <div style="font-size:12px;color:var(--muted)"><?= htmlspecialchars($student['email']) ?></div>
            <?php if(!empty($student['gender'])): ?><div style="font-size:11px;color:var(--muted);margin-top:2px"><?= htmlspecialchars($student['gender']) ?></div><?php endif; ?>
            <div style="font-size:11px;color:var(--muted);margin-top:2px">Member since <?= date('F Y',strtotime($student['created_at'])) ?></div>
            <div style="display:flex;gap:8px;margin-top:8px;flex-wrap:wrap">
                <?php if($hasCertNow): ?><span class="cert-badge"><i class="fa-solid fa-certificate"></i> TDC Cert</span><?php endif; ?>
            </div>
        </div>
    </div>
</div>
<div class="panel">
    <div class="panel-head"><div class="panel-title"><i class="fa-solid fa-pen"></i> Edit Profile</div></div>
    <div style="padding:20px">
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="update_profile">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px" class="profile-grid">
            <div><label class="form-label">Full Name *</label><input type="text" name="fullname" class="di" value="<?= htmlspecialchars($student['fullname']) ?>" required></div>
            <div><label class="form-label">Email *</label><input type="email" name="email" class="di" value="<?= htmlspecialchars($student['email']) ?>" required></div>
            <div><label class="form-label">Mobile Number</label><input type="text" name="mobile" class="di" value="<?= htmlspecialchars($student['mobile']??'') ?>" placeholder="+63 9XX XXX XXXX"></div>
            <div><label class="form-label">Address</label><input type="text" name="address" class="di" value="<?= htmlspecialchars($student['address']??'') ?>"></div>
            <div><label class="form-label">Gender</label>
                <div style="display:flex;gap:10px;margin-top:6px;flex-wrap:wrap">
                    <?php foreach(['Male','Female','Other'] as $g): ?><label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer"><input type="radio" name="gender" value="<?= $g ?>" style="accent-color:var(--accent)" <?= ($student['gender']??'')===$g?'checked':'' ?>><?= $g ?></label><?php endforeach; ?>
                </div>
            </div>
            <div><label class="form-label"><i class="fa-brands fa-facebook me-1" style="color:#60a5fa"></i>Facebook Link</label><input type="url" name="fb_link" class="di" value="<?= htmlspecialchars($student['fb_link']??'') ?>" placeholder="https://facebook.com/yourprofile"></div>
        </div>
        <div style="margin-bottom:16px;padding:16px;background:var(--bg3);border-radius:8px;border:1px solid var(--border)">
            <label class="form-label" style="margin-bottom:8px"><i class="fa-solid fa-id-card me-1" style="color:var(--amber)"></i>Valid Government ID Photo</label>
            <div style="font-size:11px;color:var(--muted);margin-bottom:10px">Upload JPG, PNG, or WEBP. Accepted: PhilSys, Driver's License, Passport, UMID.</div>
            <input type="file" name="id_photo" class="di" accept="image/*">
            <?php if(!empty($student['id_photo'])&&file_exists($idPhotoPath.$student['id_photo'])): ?>
            <div style="margin-top:8px;display:flex;align-items:center;gap:10px">
                <img src="<?= $idPhotoPath.htmlspecialchars($student['id_photo']) ?>" style="height:50px;border-radius:6px;border:1px solid var(--border)">
                <div style="display:flex;flex-direction:column;gap:4px">
                    <span style="font-size:11px;color:var(--green)"><i class="fa-solid fa-check me-1"></i>ID photo on file</span>
                    <a href="<?= 'view_image.php?type=student_ids&file='.urlencode($student['id_photo']).'&back='.urlencode($_SERVER['REQUEST_URI']) ?>" class="bds" style="font-size:11px;display:inline-flex;align-items:center;gap:4px;width:fit-content"><i class="fa-solid fa-eye me-1"></i>View</a> <a href="<?= htmlspecialchars($idPhotoPath.$student['id_photo']) ?>" download class="bds" style="font-size:11px;display:inline-flex;align-items:center;gap:4px;width:fit-content"><i class="fa-solid fa-download me-1"></i>Download</a>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <button type="submit" class="be"><i class="fa-solid fa-save"></i> Save Profile</button>
    </form>
    </div>
</div>
</div>

</div><!-- /main -->

<!-- Book Modal -->
<div id="bookModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:300;align-items:center;justify-content:center;padding:16px">
<div style="background:var(--bg2);border:1px solid var(--border);border-radius:12px;width:480px;max-width:100%;padding:24px">
    <div style="font-size:15px;font-weight:600;margin-bottom:16px"><i class="fa-solid fa-calendar-plus me-2" style="color:var(--accent)"></i>Book Appointment</div>
    <form method="POST" action="<?= $selfFile ?>?section=my_appointments">
    <input type="hidden" name="action" value="book_appointment">
    <input type="hidden" name="instructor_id" id="bm_instr_id">
    <input type="hidden" name="course_id" id="bm_course_id">
    <div id="bm_tdc_notice" style="display:none;margin-bottom:12px" class="flash flash-ok"><i class="fa-solid fa-calendar-week me-2"></i>TDC requires exactly <strong>15 hours</strong>. Start date must be a <strong>Saturday or Sunday</strong>.</div>
    <div style="margin-bottom:12px"><label class="form-label">Course</label><div id="bm_course_name" style="font-size:13px;font-weight:600"></div></div>
    <div style="margin-bottom:6px"><label class="form-label">Preferred Start Date * <span id="bm_day_hint" style="color:var(--amber);font-size:10px"></span></label><input type="date" name="start_date" id="bm_start_date" class="di" required min="<?= date('Y-m-d',strtotime('+1 day')) ?>"></div>
    <!-- Slot availability indicator -->
    <div id="bm_slot_info" style="display:none;margin-bottom:12px;padding:8px 12px;border-radius:6px;font-size:12px"></div>
    <div id="bm_hours_wrap" style="margin-bottom:12px"><label class="form-label">Number of Hours (PDC)</label><input type="number" name="total_hours" class="di" min="1" max="40" value="8"></div>
    <div style="margin-bottom:12px"><label class="form-label">Note to Instructor (optional)</label><textarea name="student_note" class="di" rows="2" placeholder="Any special requests…"></textarea></div>
    <div style="display:flex;gap:8px;justify-content:flex-end;flex-wrap:wrap">
        <button type="button" class="bds" onclick="closeBookModal()">Cancel</button>
        <button type="submit" class="be"><i class="fa-solid fa-paper-plane"></i> Send Request</button>
    </div>
    </form>
</div></div>

<!-- Rate Modal -->
<div id="rateModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:300;align-items:center;justify-content:center;padding:16px">
<div style="background:var(--bg2);border:1px solid var(--border);border-radius:12px;width:400px;max-width:100%;padding:24px">
    <div style="font-size:15px;font-weight:600;margin-bottom:4px"><i class="fa-solid fa-star me-2" style="color:var(--amber)"></i>Rate Your Instructor</div>
    <div id="rate_instr_name" style="font-size:12px;color:var(--muted);margin-bottom:16px"></div>
    <form method="POST">
    <input type="hidden" name="action" value="rate_instructor">
    <input type="hidden" name="appointment_id" id="rm_appt_id">
    <div style="margin-bottom:16px"><label class="form-label">Your Rating *</label>
        <div class="stars-input"><?php for($i=5;$i>=1;$i--): ?><input type="radio" name="rating" id="star<?= $i ?>" value="<?= $i ?>" <?= $i===5?'required':'' ?> style="display:none"><label for="star<?= $i ?>">★</label><?php endfor; ?></div>
    </div>
    <div style="margin-bottom:16px"><label class="form-label">Comment (optional)</label><textarea name="comment" class="di" rows="3" placeholder="How was your experience?"></textarea></div>
    <div style="display:flex;gap:8px;justify-content:flex-end">
        <button type="button" class="bds" onclick="closeRateModal()">Cancel</button>
        <button type="submit" class="be"><i class="fa-solid fa-paper-plane"></i> Submit</button>
    </div>
    </form>
</div></div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
var stuChartsOk=false;
function initStuCharts(){
    if(stuChartsOk||typeof Chart==='undefined')return;
    stuChartsOk=true;
    var cs=getComputedStyle(document.documentElement);
    var accent=cs.getPropertyValue('--accent').trim(); var green=cs.getPropertyValue('--green').trim();
    var amber=cs.getPropertyValue('--amber').trim(); var red=cs.getPropertyValue('--red').trim();
    var border=cs.getPropertyValue('--border').trim(); var bg=cs.getPropertyValue('--bg').trim();
    var muted=cs.getPropertyValue('--muted').trim();
    Chart.defaults.color=muted;Chart.defaults.font.family='Segoe UI,sans-serif';Chart.defaults.font.size=11;

    var c1=document.getElementById('stuChartHours');
    if(c1)new Chart(c1,{type:'bar',data:{labels:<?= json_encode($stuMonths) ?>,datasets:[{label:'TDC hrs',data:<?= json_encode($stuTDCHrsMonth) ?>,backgroundColor:amber+'cc',borderRadius:3,stack:'s'},{label:'PDC hrs',data:<?= json_encode($stuPDCHrsMonth) ?>,backgroundColor:green+'cc',borderRadius:3,stack:'s'}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom',labels:{boxWidth:10,padding:10}}},scales:{x:{grid:{color:border+'44'},stacked:true},y:{grid:{color:border+'44'},beginAtZero:true,stacked:true}}}});

    var c2=document.getElementById('stuChartProgress');
    if(c2)new Chart(c2,{type:'doughnut',data:{labels:['TDC Done','TDC Left','PDC'],datasets:[{data:[<?= $sbTDCHrsPre ?>,<?= max(0,15-$sbTDCHrsPre) ?>,<?= $sbPDCHrsPre ?>],backgroundColor:[amber+'cc',border,green+'cc'],borderColor:bg,borderWidth:3,hoverOffset:4}]},options:{responsive:true,maintainAspectRatio:false,cutout:'62%',plugins:{legend:{display:false}}}});

    var c3=document.getElementById('stuChartGrades');
    if(c3){var gData=<?= json_encode($stuGradeTrend) ?>;var gLabels=<?= json_encode($stuGradeLabels) ?>;var gcColors=gData.map(function(g){return g>=80?green:g>=60?amber:red;});new Chart(c3,{type:'line',data:{labels:gLabels,datasets:[{label:'Grade',data:gData,borderColor:accent,backgroundColor:accent+'22',fill:true,tension:0.3,pointBackgroundColor:gcColors,pointRadius:5,pointHoverRadius:7}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{x:{grid:{color:border+'44'}},y:{grid:{color:border+'44'},min:0,max:100,ticks:{callback:function(v){return v+'/100';}}}}}})}
}
(function(){if(document.querySelector('#section-dashboard.active'))setTimeout(initStuCharts,120);})();

const T={dashboard:'Dashboard',instructors:'Browse Instructors',view_instr:'Instructor Profile',my_appointments:'My Appointments',progress:'My Progress',messages:'Notifications',chat:'Messages',profile:'Profile'};

function openSidebar(){document.getElementById('sidebar').classList.add('open');document.getElementById('sidebarOverlay').classList.add('open');}
function closeSidebar(){document.getElementById('sidebar').classList.remove('open');document.getElementById('sidebarOverlay').classList.remove('open');}

function showSection(n){
    document.querySelectorAll('.section').forEach(s=>{s.classList.remove('active');s.style.display='none';});
    document.querySelectorAll('.nav-item').forEach(x=>x.classList.remove('active'));
    const el=document.getElementById('section-'+n);
    if(el){el.classList.add('active');el.style.display='block';}
    document.getElementById('page-title-text').textContent=T[n]||'Dashboard';
    document.querySelectorAll('.nav-item').forEach(x=>{if(x.getAttribute('onclick')&&x.getAttribute('onclick').includes("'"+n+"'"))x.classList.add('active');});
    if(n==='dashboard')setTimeout(initStuCharts,80);
    closeSidebar();
}

window.addEventListener('load',function(){
    document.querySelectorAll('.section').forEach(el=>{el.style.display=el.classList.contains('active')?'block':'none';});
    var mt=document.getElementById('msg-thread');if(mt)mt.scrollTop=mt.scrollHeight;
});

function viewInstructor(id){window.location.href='<?= $selfFile ?>?view_instr='+id+'&section=view_instr';}
function handleSearch(v){document.querySelectorAll('.instr-card').forEach(c=>{c.style.display=c.textContent.toLowerCase().includes(v.toLowerCase())?'':'none';});}

var _bmSlots={}, _bmLimit=0;
function openBookModal(instrId,courseId,courseType,pricePerHr,isTDC,limit,slots){
    _bmSlots=slots||{};
    _bmLimit=limit||0;
    document.getElementById('bm_instr_id').value=instrId;
    document.getElementById('bm_course_id').value=courseId;
    document.getElementById('bm_course_name').textContent=courseType+(_bmLimit?' (max '+_bmLimit+' students/date)':'');
    document.getElementById('bm_tdc_notice').style.display=isTDC?'block':'none';
    document.getElementById('bm_hours_wrap').style.display=isTDC?'none':'block';
    document.getElementById('bm_day_hint').textContent=isTDC?'(Saturday or Sunday only)':'';
    document.getElementById('bm_slot_info').style.display='none';
    const dateInput=document.getElementById('bm_start_date');
    dateInput.value='';
    dateInput.onchange=function(){
        if(isTDC){const d=new Date(this.value+'T00:00:00');if(d.getDay()!==0&&d.getDay()!==6){alert('TDC must start on Saturday or Sunday.');this.value='';return;}}
        checkSlot(this.value);
    };
    document.getElementById('bookModal').style.display='flex';
}
function checkSlot(date){
    const info=document.getElementById('bm_slot_info');
    const submitBtn=document.querySelector('#bookModal button[type="submit"]');
    if(!date){info.style.display='none';return;}
    if(_bmLimit===0){info.style.display='none';if(submitBtn)submitBtn.disabled=false;return;}
    const slot=_bmSlots[date];
    const booked=slot?slot.booked:0;
    const left=_bmLimit-booked;
    info.style.display='block';
    if(left<=0){
        info.style.background='rgba(229,85,85,.12)';info.style.border='1px solid rgba(229,85,85,.3)';info.style.color='var(--red)';
        info.innerHTML='<i class="fa-solid fa-ban me-1"></i><strong>This date is fully booked</strong> ('+booked+'/'+_bmLimit+' slots taken). Please choose a different date.';
        if(submitBtn)submitBtn.disabled=true;
    } else {
        info.style.background='rgba(59,185,122,.1)';info.style.border='1px solid rgba(59,185,122,.3)';info.style.color='var(--green)';
        info.innerHTML='<i class="fa-solid fa-circle-check me-1"></i><strong>'+left+' slot'+(left>1?'s':'')+' available</strong> on this date ('+booked+'/'+_bmLimit+' booked).';
        if(submitBtn)submitBtn.disabled=false;
    }
}
function closeBookModal(){document.getElementById('bookModal').style.display='none';}
function openRateModal(apptId,instrName){document.getElementById('rm_appt_id').value=apptId;document.getElementById('rate_instr_name').textContent='Instructor: '+instrName;document.getElementById('rateModal').style.display='flex';}
function closeRateModal(){document.getElementById('rateModal').style.display='none';}
['bookModal','rateModal'].forEach(id=>{document.getElementById(id).addEventListener('click',function(e){if(e.target===this)this.style.display='none';});});
</script>

</body>
</html>
