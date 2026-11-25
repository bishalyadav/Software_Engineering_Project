<?php session_start(); $in=$_SESSION['u']??null; $dash=$in?($in['role']=='admin'?'admin/dashboard.php':'student/index.php'):'login.php'; ?>
<!DOCTYPE html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>HostelHub - Smart Hostel Management</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:FILL@0..1" rel="stylesheet">
<link rel="stylesheet" href="assets/style.css"></head><body>
<header class="lnav"><span class="logo" style="padding:0">HostelHub</span><a class="btn" href="<?=$dash?>"><?=$in?'Go to Dashboard':'Login'?></a></header>
<section class="hero"><h1>Hostel management,<br><span class="blue">made simple.</span></h1>
<p>Rooms, students, complaints and mess menu - sab kuch ek hi jagah. Warden ke liye aasan control, students ke liye fast service.</p>
<a class="btn big-btn" href="<?=$dash?>"><?=$in?'Open Dashboard':'Login to HostelHub'?></a></section>
<section class="feat">
<div class="card"><div class="ico"><span class="ms">bed</span></div><h3>Room Management</h3><p>Room capacity aur allocation ek nazar mein.</p></div>
<div class="card"><div class="ico"><span class="ms">group</span></div><h3>Student Records</h3><p>Students ko add karein aur rooms assign karein.</p></div>
<div class="card"><div class="ico r"><span class="ms">warning</span></div><h3>Complaints</h3><p>Students complaint raise karein, warden status update kare.</p></div>
<div class="card"><div class="ico gr"><span class="ms">restaurant</span></div><h3>Mess Menu</h3><p>Weekly mess menu aur notices sabke liye available.</p></div>
</section><footer class="lfoot">&copy; <?=date('Y')?> HostelHub</footer></body></html>