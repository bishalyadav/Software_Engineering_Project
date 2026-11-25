<?php
require '../config.php'; require '../includes/layout.php'; auth('student');
$id=(int)$_SESSION['u']['id'];
if(empty($_SESSION['profile_csrf'])) $_SESSION['profile_csrf']=bin2hex(random_bytes(32));
$error=''; $saved=!empty($_SESSION['profile_saved']); unset($_SESSION['profile_saved']);
$profile=q('SELECT u.name,u.email,p.enrollment_no,p.phone,p.course,p.department,r.room_no,r.block FROM users u LEFT JOIN student_profiles p ON p.user_id=u.id LEFT JOIN rooms r ON r.id=u.room_id WHERE u.id=?',[$id])->fetch();
if(isset($_POST['save'])){
  if(!hash_equals($_SESSION['profile_csrf'],$_POST['csrf_token']??'')){
    $error='Your session expired. Please reload and try again.';
  } else {
    $name=trim($_POST['name']??'');
    $email=strtolower(trim($_POST['email']??''));
    $enrollment=strtoupper(trim($_POST['enrollment_no']??''));
    $phone=trim($_POST['phone']??'');
    $course=trim($_POST['course']??'');
    $department=trim($_POST['department']??'');
    $hasAcademic=($enrollment!=='' || $phone!=='' || $course!=='' || $department!=='');
    if($name==='' || !filter_var($email,FILTER_VALIDATE_EMAIL)){
      $error='Please enter a name and a valid email address.';
    } elseif($hasAcademic && ($enrollment==='' || $phone==='' || $course==='' || $department==='')){
      $error='Complete all academic and contact fields, or leave them all blank.';
    } else {
      try {
        db()->beginTransaction();
        q('UPDATE users SET name=?,email=? WHERE id=? AND role=\'student\'',[$name,$email,$id]);
        if($hasAcademic){
          q('INSERT INTO student_profiles(user_id,enrollment_no,phone,course,department) VALUES(?,?,?,?,?) ON DUPLICATE KEY UPDATE enrollment_no=VALUES(enrollment_no),phone=VALUES(phone),course=VALUES(course),department=VALUES(department)',[$id,$enrollment,$phone,$course,$department]);
        }
        db()->commit();
        $_SESSION['u']['name']=$name; $_SESSION['u']['email']=$email;
        $_SESSION['profile_csrf']=bin2hex(random_bytes(32));
        $_SESSION['profile_saved']=true;
        header('Location: profile.php'); exit;
      } catch(PDOException $x){
        if(db()->inTransaction()) db()->rollBack();
        if((int)($x->errorInfo[1]??0)===1062) $error='That email or enrollment number is already in use.';
        else throw $x;
      }
    }
  }
}
top('My Profile','profile',false); ?>
<h1>My Profile</h1><p class="sub">View and update your student account details.</p>
<?php if($saved): ?><div class="success-note">Profile saved.</div><?php endif; ?>
<?php if($error): ?><div class="err"><?=e($error)?></div><?php endif; ?>
<div class="card"><form method="post"><input type="hidden" name="csrf_token" value="<?=e($_SESSION['profile_csrf'])?>">
<h2 class="form-section">Personal Information</h2><div class="register-grid">
<div><label for="name">Full name</label><input id="name" name="name" required maxlength="100" value="<?=e($profile['name'])?>"></div>
<div><label for="email">Email address</label><input id="email" type="email" name="email" required maxlength="120" value="<?=e($profile['email'])?>"></div>
<div><label for="enrollment_no">Enrollment number</label><input id="enrollment_no" name="enrollment_no" maxlength="40" value="<?=e($profile['enrollment_no']??'')?>"></div>
<div><label for="phone">Phone number</label><input id="phone" type="tel" name="phone" maxlength="30" value="<?=e($profile['phone']??'')?>"></div>
<div><label for="course">Course</label><input id="course" name="course" maxlength="100" value="<?=e($profile['course']??'')?>"></div>
<div><label for="department">Department</label><input id="department" name="department" maxlength="100" value="<?=e($profile['department']??'')?>"></div>
<div><label>Assigned block</label><input value="<?=e($profile['block']??'Not assigned')?>" readonly></div>
<div><label>Room number</label><input value="<?=e($profile['room_no']??'Not assigned')?>" readonly></div>
</div><div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:18px"><button class="btn" type="submit" name="save" value="1">Save profile</button><a class="btn" href="change-password.php">Change password</a></div></form></div>
<?php bottom();
