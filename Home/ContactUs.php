<?php
require '../config.php';

if(empty($_SESSION['contact_csrf'])) $_SESSION['contact_csrf']=bin2hex(random_bytes(32));
$name=''; $email=''; $subject=''; $message=''; $error=''; $sent=!empty($_SESSION['contact_sent']); unset($_SESSION['contact_sent']);
$currentUser=$_SESSION['u']??null;
$dashboardLink=$currentUser?($currentUser['role']==='admin'?'../admin/dashboard.php':'../student/index.php'):'';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $name=trim($_POST['name']??'');
  $email=strtolower(trim($_POST['email']??''));
  $subject=trim($_POST['subject']??'');
  $message=trim($_POST['message']??'');
  if(!hash_equals($_SESSION['contact_csrf'],$_POST['csrf_token']??'')){
    $error='Your session expired. Please submit the form again.';
  } elseif($name==='' || strlen($name)>100){
    $error='Please enter your name (up to 100 characters).';
  } elseif(!filter_var($email,FILTER_VALIDATE_EMAIL) || strlen($email)>120){
    $error='Please enter a valid email address.';
  } elseif($subject==='' || strlen($subject)>200){
    $error='Please enter a subject (up to 200 characters).';
  } elseif(strlen($message)<10 || strlen($message)>10000){
    $error='Message must be between 10 and 10,000 characters.';
  } else {
    q('INSERT INTO contact_messages(full_name,email,subject,message) VALUES(?,?,?,?)',[$name,$email,$subject,$message]);
    $_SESSION['contact_sent']=true;
    $_SESSION['contact_csrf']=bin2hex(random_bytes(32));
    header('Location: ContactUs.php'); exit;
  }
}
?><!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Contact HostelHub</title><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"><link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"><link rel="stylesheet" href="../assets/style.css"><style>
.contact-page{min-height:100vh;background:#f8f9fb}.contact-nav{display:flex;align-items:center;justify-content:space-between;padding:18px max(24px,calc((100% - 1100px)/2));background:#fff;border-bottom:1px solid #d5d8de}.contact-nav .logo{padding:0}.contact-nav .links{display:flex;gap:18px;align-items:center}.contact-wrap{max-width:1100px;margin:0 auto;padding:54px 24px}.contact-title{text-align:center;margin-bottom:34px}.contact-title p{color:#5d626a;font-size:16px;margin-top:10px}.contact-grid{display:grid;grid-template-columns:1.3fr .7fr;gap:22px}.contact-card{background:#fff;border:1px solid #d5d8de;border-radius:14px;padding:26px}.contact-card h2{font-size:19px;margin:0 0 18px}.contact-card .field{margin-bottom:14px}.contact-card label{margin:0 0 6px}.contact-card textarea{min-height:145px;resize:vertical}.contact-info p{color:#505660;line-height:1.55;margin:0 0 18px}.contact-info .info-item{display:flex;gap:12px;margin:20px 0}.contact-info .material-symbols-outlined{color:#0058bc}.success{background:#e9fbf0;border:1px solid #bdeccd;color:#0a7a3f;padding:12px 14px;border-radius:8px;margin-bottom:18px}.contact-footer{text-align:center;padding:22px;color:#60656d}@media(max-width:760px){.contact-grid{grid-template-columns:1fr}.contact-wrap{padding:36px 16px}.contact-nav{padding:16px}.contact-nav .links{gap:10px}}
</style></head><body class="contact-page">
<header class="contact-nav"><a href="../index.php" class="logo">HostelHub</a><div class="links"><a href="../index.php">Home</a><?php if($currentUser): ?><a href="<?=e($dashboardLink)?>">Dashboard</a><a class="btn" href="../logout.php">Logout</a><?php else: ?><a href="../login.php">Login</a><a class="btn" href="../student/register.php">Get Started</a><?php endif; ?></div></header>
<main class="contact-wrap"><div class="contact-title"><h1>Get in touch</h1><p>We are here to help with HostelHub questions, feedback, and support.</p></div>
<div class="contact-grid"><section class="contact-card"><h2>Send us a message</h2>
<?php if($sent): ?><div class="success" role="status">Thanks for contacting us. Your message has been sent to the hostel team.</div><?php endif; ?>
<?php if($error): ?><div class="err" role="alert"><?=e($error)?></div><?php endif; ?>
<form method="post"><input type="hidden" name="csrf_token" value="<?=e($_SESSION['contact_csrf'])?>">
<div class="field"><label for="name">Full name</label><input id="name" name="name" required maxlength="100" autocomplete="name" value="<?=e($name)?>"></div>
<div class="field"><label for="email">Email address</label><input id="email" type="email" name="email" required maxlength="120" autocomplete="email" value="<?=e($email)?>"></div>
<div class="field"><label for="subject">Subject</label><input id="subject" name="subject" required maxlength="200" value="<?=e($subject)?>"></div>
<div class="field"><label for="message">How can we help?</label><textarea id="message" name="message" required minlength="10" maxlength="10000" placeholder="Tell us a little about your question or issue."><?=e($message)?></textarea></div>
<button class="btn" type="submit">Send message</button></form></section>
<aside class="contact-card contact-info"><h2>Contact information</h2><p>Messages submitted here are delivered to the HostelHub administrator inbox.</p><div class="info-item"><span class="material-symbols-outlined">location_on</span><div><b>HostelHub</b><br><span>Hostel administration support</span></div></div><div class="info-item"><span class="material-symbols-outlined">schedule</span><div><b>Support hours</b><br><span>Monday-Friday, 9:00 AM-6:00 PM</span></div></div><div class="info-item"><span class="material-symbols-outlined">mail</span><div><b>Online support</b><br><span>Use the form to reach the team</span></div></div></aside></div></main>
<footer class="contact-footer">&copy; <?=date('Y')?> HostelHub</footer></body></html>
