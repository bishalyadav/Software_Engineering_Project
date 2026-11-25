<?php require 'config.php';
$current=$_SESSION['u']??null;
if($current && in_array($current['role'],['admin','student'],true)){
  header('Location: '.($current['role']==='admin'?'admin/dashboard.php':'student/index.php'));
  exit;
}
$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $identity=trim($_POST['identity']??'');
  $u=q('SELECT * FROM users WHERE LOWER(TRIM(email))=?',[strtolower($identity)])->fetch();
  if(!$u && $identity!=='') $u=q("SELECT u.* FROM users u JOIN student_profiles p ON p.user_id=u.id WHERE UPPER(TRIM(p.enrollment_no))=? AND u.role='student'",[strtoupper($identity)])->fetch();
  if($u && password_verify($_POST['password'],$u['password'])){ session_regenerate_id(true); $_SESSION['u']=$u; header('Location: '.($u['role']=='admin'?'admin/dashboard.php':'student/index.php')); exit; }
  $err='Invalid email or password';
}
?><!DOCTYPE html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>HostelHub Login</title>
<link rel="stylesheet" href="assets/style.css"></head><body class="login">
<form method="post" class="card loginbox"><h1 class="logo">HostelHub</h1><p class="sub">Sign in to continue</p>
<?php if($err) echo "<p class='err'>".e($err)."</p>"; ?>
<label>Email or enrollment number</label><input type="text" name="identity" required autocomplete="username" value="<?=e($_POST['identity']??'')?>" placeholder="student@university.edu or ENR-2026-001">
<label>Password</label><input type="password" name="password" required autocomplete="current-password">
<button class="btn" style="width:100%;margin-top:14px">Login</button>
<p class="sub" style="margin-top:12px;font-size:13px;text-align:center"><a href="forgot-password.php">Forgot password?</a></p>
<p class="sub" style="margin-top:18px;font-size:13px;text-align:center">New student? <a href="student/register.php">Create an account</a></p>
<!-- <p class="sub" style="margin-top:14px;font-size:12px">Admin: admin@hostelhub.com · Student: john@student.com · Password: password</p></form></body></html> -->
