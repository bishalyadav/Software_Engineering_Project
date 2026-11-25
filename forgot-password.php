<?php
require 'config.php';
$error=''; $done=false;
if(empty($_SESSION['forgot_csrf'])) $_SESSION['forgot_csrf']=bin2hex(random_bytes(32));
if($_SERVER['REQUEST_METHOD']==='POST'){
  if(!hash_equals($_SESSION['forgot_csrf'],$_POST['csrf_token']??'')){
    $error='Your session expired. Please reload and try again.';
  } else {
    $email=strtolower(trim($_POST['email']??''));
    $enrollment=strtoupper(trim($_POST['enrollment_no']??''));
    $phone=trim($_POST['phone']??'');
    $password=$_POST['password']??''; $confirm=$_POST['confirm_password']??'';
    $u=q('SELECT u.id FROM users u JOIN student_profiles p ON p.user_id=u.id WHERE u.role=\'student\' AND LOWER(TRIM(u.email))=? AND UPPER(TRIM(p.enrollment_no))=? AND TRIM(p.phone)=?',[$email,$enrollment,$phone])->fetch();
    if(!$u){ $error='We could not verify those details. Check your email, enrollment number, and phone number.'; }
    elseif(strlen($password)<8){ $error='Choose a password with at least 8 characters.'; }
    elseif($password!==$confirm){ $error='The passwords do not match.'; }
    else { q('UPDATE users SET password=? WHERE id=?',[password_hash($password,PASSWORD_DEFAULT),$u['id']]); $done=true; $_SESSION['forgot_csrf']=bin2hex(random_bytes(32)); }
  }
}
?><!DOCTYPE html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Forgot Password - HostelHub</title><link rel="stylesheet" href="assets/style.css"></head><body class="login">
<main class="card loginbox"><h1 class="logo">Reset password</h1><p class="sub">Verify your student account and choose a new password.</p>
<?php if($done): ?><div class="success-note">Your password has been updated. <a href="login.php">Sign in</a></div><?php else: ?>
<?php if($error): ?><div class="err"><?=e($error)?></div><?php endif; ?>
<form method="post"><input type="hidden" name="csrf_token" value="<?=e($_SESSION['forgot_csrf'])?>">
<label for="email">Account email</label><input id="email" type="email" name="email" required autocomplete="email" value="<?=e($_POST['email']??'')?>">
<label for="enrollment_no">Enrollment number</label><input id="enrollment_no" name="enrollment_no" required value="<?=e($_POST['enrollment_no']??'')?>">
<label for="phone">Phone number on your profile</label><input id="phone" type="tel" name="phone" required value="<?=e($_POST['phone']??'')?>">
<label for="password">New password</label><input id="password" type="password" name="password" required minlength="8" autocomplete="new-password">
<label for="confirm_password">Confirm new password</label><input id="confirm_password" type="password" name="confirm_password" required minlength="8" autocomplete="new-password">
<button class="btn" style="width:100%;margin-top:16px">Reset password</button></form>
<?php endif; ?><p class="sub" style="margin-top:18px;font-size:13px;text-align:center"><a href="login.php">Back to sign in</a></p></main></body></html>
