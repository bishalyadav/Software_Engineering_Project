<?php
require '../config.php'; require '../includes/layout.php'; auth('student');
$id=(int)$_SESSION['u']['id'];
if(empty($_SESSION['password_csrf'])) $_SESSION['password_csrf']=bin2hex(random_bytes(32));
$error=''; $saved=!empty($_SESSION['password_saved']); unset($_SESSION['password_saved']);
if($_SERVER['REQUEST_METHOD']==='POST'){
  if(!hash_equals($_SESSION['password_csrf'],$_POST['csrf_token']??'')){
    $error='Your session expired. Please reload and try again.';
  } else {
    $user=q('SELECT password FROM users WHERE id=? AND role=\'student\'',[$id])->fetch();
    $current=$_POST['current_password']??''; $new=$_POST['new_password']??''; $confirm=$_POST['confirm_password']??'';
    if(!$user || !password_verify($current,$user['password'])) $error='Your current password is incorrect.';
    elseif(strlen($new)<8) $error='Choose a password with at least 8 characters.';
    elseif($new!==$confirm) $error='The new passwords do not match.';
    else {
      q('UPDATE users SET password=? WHERE id=?',[password_hash($new,PASSWORD_DEFAULT),$id]);
      $_SESSION['password_csrf']=bin2hex(random_bytes(32)); $_SESSION['password_saved']=true;
      header('Location: change-password.php'); exit;
    }
  }
}
top('Change Password','profile',false); ?>
<h1>Change Password</h1><p class="sub">Choose a new password for your account.</p>
<?php if($saved): ?><div class="success-note">Password changed successfully.</div><?php endif; ?>
<?php if($error): ?><div class="err"><?=e($error)?></div><?php endif; ?>
<div class="card" style="max-width:560px"><form method="post"><input type="hidden" name="csrf_token" value="<?=e($_SESSION['password_csrf'])?>">
<label for="current_password">Current password</label><input id="current_password" type="password" name="current_password" required autocomplete="current-password">
<label for="new_password">New password</label><input id="new_password" type="password" name="new_password" required minlength="8" autocomplete="new-password">
<label for="confirm_password">Confirm new password</label><input id="confirm_password" type="password" name="confirm_password" required minlength="8" autocomplete="new-password">
<div style="display:flex;gap:10px;margin-top:18px"><button class="btn" type="submit">Update password</button><a class="btn" href="profile.php">Back to profile</a></div></form></div>
<?php bottom();
