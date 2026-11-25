<?php
// ---------- Page data ----------
if(session_status()!==PHP_SESSION_ACTIVE) session_start();
$scriptPath=str_replace('\\','/',$_SERVER['SCRIPT_NAME']??'');
if(substr($scriptPath,-strlen('/Home/landing.php'))==='/Home/landing.php'){ header('Location: ../index.php'); exit; }
$currentUser=$_SESSION['u']??null;
$dashboardLink=$currentUser?($currentUser['role']==='admin'?'admin/dashboard.php':'student/index.php'):'';
$siteName = "HostelHub";

$navLinks = [
    ["label" => "Home",         "href" => "#home"],
    ["label" => "Features",     "href" => "#features"],
    ["label" => "How It Works", "href" => "#how-it-works"],
    ["label" => "About",        "href" => "#about"],
    ["label" => "Contact",      "href" => "Home/ContactUs.php"],
];

$features = [
    ["icon" => "bed",        "color" => "blue",  "title" => "Room Management",
     "desc"  => "Easily allocate rooms, track availability in real-time, and manage transfers with a visual floor plan."],
    ["icon" => "group",      "color" => "blue",  "title" => "Student Database",
     "desc"  => "Maintain digital records of all residents, including emergency contacts, fee status, and attendance."],
    ["icon" => "warning",    "color" => "red",   "title" => "Complaint Resolution",
     "desc"  => "Streamline maintenance requests with automated ticketing, status tracking, and resolution updates."],
    ["icon" => "restaurant", "color" => "blue",  "title" => "Mess Management",
     "desc"  => "Publish weekly menus, track meal attendance, and manage vendor supplies and feedback."],
    ["icon" => "campaign",   "color" => "blue",  "title" => "Digital Notices",
     "desc"  => "Broadcast important announcements instantly to all students via email and in-app notifications."],
    ["icon" => "forum",      "color" => "gray",  "title" => "Seamless Communication",
     "desc"  => "Facilitate two-way communication between administration and residents for a transparent environment."],
];

$steps = [
    ["title" => "Register & Setup",
     "desc"  => "Create your institution profile, define room structures, and import student data securely."],
    ["title" => "Manage Operations",
     "desc"  => "Handle daily tasks like attendance, complaints, and mess scheduling from the central dashboard."],
    ["title" => "Stay Connected",
     "desc"  => "Keep students informed with real-time notices and monitor overall hostel health through reports."],
];

$activeStep = 2; // highlighted step (blue circle)
function e($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($siteName) ?> - Smart Hostel Management, Made Simple</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <style>
        :root {
            --blue: #0057c2;
            --blue-light: #dbe5fb;
            --red-light: #fcd9d9;
            --red: #c62828;
            --gray-light: #e3e5e8;
            --bg: #f8f9fc;
            --bg-alt: #f3f4f6;
            --text: #0b0b0f;
            --muted: #4b4f58;
            --border: #d9dbe0;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); }
        a { text-decoration: none; color: inherit; }
        .material-symbols-outlined { font-size: 22px; }

        /* ---------- Navbar ---------- */
        header {
            position: sticky; top: 0; z-index: 10;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 24px; height: 64px;
            background: var(--bg); border-bottom: 1px solid var(--border);
        }
        .logo { font-size: 18px; font-weight: 700; }
        nav { display: flex; gap: 28px; }
        nav a { font-size: 13px; font-weight: 500; color: #2a2d34; }
        nav a.active, nav a:hover { color: var(--blue); }
        .nav-actions { display: flex; align-items: center; gap: 24px; }
        .nav-actions .login { font-size: 13px; font-weight: 500; }
        .btn-dark {
            background: #000; color: #fff; font-size: 13px; font-weight: 600;
            padding: 10px 16px; border-radius: 6px;
        }

        /* ---------- Hero ---------- */
        .hero {
            display: grid; grid-template-columns: 1fr 1.2fr; gap: 40px;
            align-items: center; padding: 140px 0 90px 5px;
            min-height: 760px; border-bottom: 1px solid var(--border);
        }
        .hero h1 { font-size: 52px; line-height: 1.08; font-weight: 700; letter-spacing: -1px; }
        .hero h1 span { color: var(--blue); }
        .hero p { margin-top: 28px; font-size: 16px; line-height: 1.5; color: var(--muted); max-width: 500px; }
        .hero-buttons { display: flex; gap: 16px; margin-top: 32px; }
        .btn-primary {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--blue); color: #fff; font-size: 13px; font-weight: 600;
            padding: 14px 24px; border-radius: 6px;
        }
        .btn-primary .material-symbols-outlined { font-size: 18px; }
        .btn-outline {
            display: inline-flex; align-items: center;
            background: #fff; border: 1px solid var(--border);
            font-size: 13px; font-weight: 600; padding: 14px 24px; border-radius: 6px;
        }
        .hero-img { height: 502px; }
        .hero-img img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .hero-art { height: 440px; border-radius: 22px; padding: 18px; overflow: hidden; background: radial-gradient(circle at 85% 18%, #dbe5fb 0 12%, transparent 33%), linear-gradient(135deg,#eaf0ff,#f8f9fc 55%,#d7e5ff); box-shadow: 0 24px 70px rgba(24,55,116,.13); }
        .art-window { height: 42px; display:flex; align-items:center; gap:7px; padding:0 12px; background:rgba(255,255,255,.82); border:1px solid rgba(0,87,194,.12); border-radius:12px; color:#4b4f58; font-size:12px; }
        .art-window b { margin-left:auto; font-weight:600; }
        .art-dot { width:8px;height:8px;border-radius:50%;background:#9cb6e8; }
        .art-dot:first-child { background:#557dcc; }
        .art-content { max-width:490px; margin:42px auto 0; }
        .art-heading { font-size:32px;line-height:1.14;color:#232a39;font-weight:500;margin-bottom:26px; }
        .art-heading strong { color:var(--blue); }
        .art-cards { display:grid;grid-template-columns:repeat(3,1fr);gap:12px; }
        .art-cards div { min-height:142px;padding:16px 13px;background:rgba(255,255,255,.9);border:1px solid #dbe3f2;border-radius:14px;display:flex;flex-direction:column;gap:9px;box-shadow:0 8px 24px rgba(31,50,90,.06); }
        .art-cards .material-symbols-outlined { color:var(--blue);font-size:24px; }
        .art-cards b { font-size:13px; }
        .art-cards small { color:var(--muted);line-height:1.35; }
        .about-grid { display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:start; }
        .values { display:grid;grid-template-columns:1fr 1fr;gap:14px; }
        .values .card { min-height:0;padding:22px; }

        /* ---------- Sections ---------- */
        section.block { padding: 90px 24px; }
        .section-head { text-align: center; max-width: 720px; margin: 0 auto 64px; }
        .section-head h2 { font-size: 36px; font-weight: 700; letter-spacing: -0.5px; }
        .section-head p { margin-top: 16px; font-size: 16px; line-height: 1.5; color: var(--muted); }

        /* ---------- Features ---------- */
        #features { background: var(--bg-alt); border-bottom: 1px solid var(--border); padding-bottom: 96px; }
        .feature-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
        .card {
            background: #fff; border: 1px solid var(--border); border-radius: 10px;
            padding: 32px; min-height: 230px;
        }
        .icon-box {
            width: 48px; height: 48px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center; margin-bottom: 24px;
        }
        .icon-box.blue { background: var(--blue-light); color: var(--blue); }
        .icon-box.red  { background: var(--red-light);  color: var(--red); }
        .icon-box.gray { background: var(--gray-light); color: #1f2229; }
        .card h3 { font-size: 18px; font-weight: 600; margin-bottom: 10px; }
        .card p { font-size: 14px; line-height: 1.45; color: var(--muted); }

        /* ---------- How it works ---------- */
        .steps { display: grid; grid-template-columns: repeat(3, 1fr); position: relative; max-width: 1280px; margin: 0 auto; }
        .steps::before {
            content: ""; position: absolute; top: 44px; left: 16%; right: 16%;
            height: 1px; background: var(--border);
        }
        .step { text-align: center; position: relative; padding: 0 24px; }
        .circle {
            width: 88px; height: 88px; border-radius: 50%; margin: 0 auto 28px;
            background: #fff; display: flex; align-items: center; justify-content: center;
            font-size: 36px; font-weight: 700; box-shadow: 0 4px 14px rgba(0,0,0,.08);
            position: relative; z-index: 1;
        }
        .circle.active { background: var(--blue); color: #fff; }
        .step h3 { font-size: 18px; font-weight: 600; margin-bottom: 10px; }
        .step p { font-size: 14px; line-height: 1.45; color: var(--muted); }

        /* ---------- Footer ---------- */
        footer { background: #0b0b0f; color: #c9ccd2; padding: 40px 24px; text-align: center; font-size: 14px; }
        footer strong { color: #fff; }

        /* ---------- Responsive ---------- */
        @media (max-width: 900px) {
            nav { display: none; }
            .hero { grid-template-columns: 1fr; padding: 60px 24px; min-height: auto; }
            .hero h1 { font-size: 38px; }
            .hero-img { height: 320px; }
            .hero-art { height: 350px; }
            .feature-grid, .steps { grid-template-columns: 1fr; gap: 40px; }
            .steps::before { display: none; }
        }
    </style>
</head>
<body>

<!-- ========== NAVBAR ========== -->
<header>
    <a href="#home" class="logo"><?= e($siteName) ?></a>
    <nav>
        <?php foreach ($navLinks as $i => $link): ?>
            <a href="<?= e($link['href']) ?>" class="<?= $i === 0 ? 'active' : '' ?>"><?= e($link['label']) ?></a>
        <?php endforeach; ?>
    </nav>
    <div class="nav-actions">
        <?php if($currentUser): ?>
            <a href="<?=e($dashboardLink)?>" class="login">Dashboard</a>
            <a href="logout.php" class="btn-dark">Logout</a>
        <?php else: ?>
            <a href="login.php" class="login">Login</a>
            <a href="student/register.php" class="btn-dark">Get Started</a>
        <?php endif; ?>
    </div>
</header>

<!-- ========== HERO ========== -->
<section class="hero" id="home">
    <div>
        <h1>Smart Hostel Management, <span>Made Simple.</span></h1>
        <p>Manage students, rooms, complaints, mess services, and hostel communication from one powerful, intuitive platform designed for operational excellence.</p>
        <div class="hero-buttons">
            <a href="<?=$currentUser?e($dashboardLink):'student/register.php'?>" class="btn-primary"><?=$currentUser?'Open Dashboard':'Get Started Free'?> <span class="material-symbols-outlined">arrow_forward</span></a>
            <a href="#features" class="btn-outline">Explore Features</a>
        </div>
    </div>
    <div class="hero-img hero-art" role="img" aria-label="HostelHub room and service overview">
        <div class="art-window"><span class="art-dot"></span><span class="art-dot"></span><span class="art-dot"></span><b>HostelHub overview</b></div>
        <div class="art-content"><div class="art-heading">Your hostel,<br><strong>in one place.</strong></div><div class="art-cards"><div><span class="material-symbols-outlined">bed</span><b>Rooms</b><small>Allocation made simple</small></div><div><span class="material-symbols-outlined">task_alt</span><b>Complaints</b><small>Track every request</small></div><div><span class="material-symbols-outlined">restaurant</span><b>Mess menu</b><small>Weekly schedule</small></div></div></div>
    </div>
</section>

<!-- ========== FEATURES ========== -->
<section class="block" id="features">
    <div class="section-head">
        <h2>Everything You Need</h2>
        <p>A comprehensive suite of tools to automate daily tasks and improve the living experience for our students.</p>
    </div>
    <div class="feature-grid">
        <?php foreach ($features as $f): ?>
            <div class="card">
                <div class="icon-box <?= e($f['color']) ?>">
                    <span class="material-symbols-outlined"><?= e($f['icon']) ?></span>
                </div>
                <h3><?= e($f['title']) ?></h3>
                <p><?= e($f['desc']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- ========== HOW IT WORKS ========== -->
<section class="block" id="how-it-works">
    <div class="section-head">
        <h2>How It Works</h2>
        <p>Get your hostel up and running on our platform in three simple steps.</p>
    </div>
    <div class="steps">
        <?php foreach ($steps as $i => $s): $n = $i + 1; ?>
            <div class="step">
                <div class="circle <?= $n === $activeStep ? 'active' : '' ?>"><?= $n ?></div>
                <h3><?= e($s['title']) ?></h3>
                <p><?= e($s['desc']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- ========== ABOUT ========== -->
<section class="block" id="about" style="background:#f3f4f6">
    <div class="section-head"><h2>About HostelHub</h2><p>We make hostel operations easier for managers and everyday life clearer for students.</p></div>
    <div class="about-grid">
        <div><h3 style="font-size:22px;margin-bottom:12px">Our mission</h3><p style="color:var(--muted);line-height:1.7">HostelHub brings room management, student services, complaints, meal schedules, and announcements together in one straightforward place. This gives hostel teams more time to support residents and helps students find the information they need.</p></div>
        <div class="values"><div class="card"><h3>Efficiency</h3><p>Reduce repeated work across everyday hostel tasks.</p></div><div class="card"><h3>Transparency</h3><p>Keep requests and updates easy to follow.</p></div><div class="card"><h3>Community</h3><p>Help residents and staff stay connected.</p></div><div class="card"><h3>Continuous improvement</h3><p>Make services better through clear feedback.</p></div></div>
    </div>
</section>

<!-- ========== FOOTER ========== -->
<footer id="contact">
    <p><strong><?= e($siteName) ?></strong> &copy; <?= date('Y') ?>. All rights reserved.</p>
    <p style="margin-top:12px">Need help? <a href="Home/ContactUs.php" style="color:#fff;text-decoration:underline">Contact HostelHub support</a></p>
</footer>

</body>
</html>
