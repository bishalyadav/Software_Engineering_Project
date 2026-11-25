<?php
require '../config.php';

if(!empty($_SESSION['u'])){
  header('Location: '.($_SESSION['u']['role']==='admin'?'../admin/dashboard.php':'index.php'));
  exit;
}

$err='';
$name=''; $email=''; $enrollment=''; $phone=''; $course=''; $department='';
$selectedRoom='';

if($_SERVER['REQUEST_METHOD']==='POST'){
  $name=trim($_POST['name']??'');
  $email=strtolower(trim($_POST['email']??''));
  $enrollment=strtoupper(trim($_POST['enrollment_no']??''));
  $phone=trim($_POST['phone']??'');
  $course=trim($_POST['course']??'');
  $department=trim($_POST['department']??'');
  $password=$_POST['password']??'';
  $confirm=$_POST['confirm_password']??'';
  $selectedRoom=(int)($_POST['room_id']??0);

  if($name==='' || $enrollment==='' || $phone==='' || $course==='' || $department===''){
    $err='Please complete all required fields.';
  } elseif(!filter_var($email,FILTER_VALIDATE_EMAIL)){
    $err='Please enter a valid university email address.';
  } elseif(strlen($password)<8){
    $err='Password must be at least 8 characters.';
  } elseif($password!==$confirm){
    $err='Passwords do not match.';
  } else {
    try {
      db()->beginTransaction();
      $roomId=null;
      if($selectedRoom){
        $room=q('SELECT id,capacity FROM rooms WHERE id=? FOR UPDATE',[$selectedRoom])->fetch();
        if(!$room){ throw new DomainException('That room is no longer available. Please choose again.'); }
        $occupied=(int)q('SELECT COUNT(*) FROM users WHERE room_id=?',[$selectedRoom])->fetchColumn();
        if($occupied >= (int)$room['capacity']){ throw new DomainException('That room is full. Please choose another room.'); }
        $roomId=$selectedRoom;
      }
      q("INSERT INTO users(name,email,password,role,room_id) VALUES(?,?,?,'student',?)",[$name,$email,password_hash($password,PASSWORD_DEFAULT),$roomId]);
      $userId=db()->lastInsertId();
      q('INSERT INTO student_profiles(user_id,enrollment_no,phone,course,department) VALUES(?,?,?,?,?)',[$userId,$enrollment,$phone,$course,$department]);
      db()->commit();
      session_regenerate_id(true);
      $_SESSION['u']=q('SELECT * FROM users WHERE id=?',[$userId])->fetch();
      header('Location: index.php');
      exit;
    } catch(DomainException $x){
      if(db()->inTransaction()) db()->rollBack();
      $err=$x->getMessage();
    } catch(PDOException $x){
      if(db()->inTransaction()) db()->rollBack();
      if((int)($x->errorInfo[1]??0)===1062){
        $err='This email or enrollment number is already registered.';
      } else {
        throw $x;
      }
    }
  }
}

$rooms=q("SELECT r.id,r.room_no,r.block,r.capacity,COUNT(u.id) occupied FROM rooms r LEFT JOIN users u ON u.room_id=r.id GROUP BY r.id,r.room_no,r.block,r.capacity HAVING COUNT(u.id)<r.capacity ORDER BY r.block,r.room_no")->fetchAll();
?><!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Create Student Account | HostelHub</title><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"><link rel="stylesheet" href="../assets/style.css"></head><body class="login">
<form method="post" class="card loginbox" style="max-width:620px"><h1 class="logo">HostelHub</h1><p class="sub">Create your student account</p>
<?php if($err) echo "<p class='err'>".e($err)."</p>"; ?>
<h2 class="form-section">Personal Information</h2>
<div class="register-grid">
  <div><label for="name">Full name</label><input id="name" name="name" required maxlength="100" autocomplete="name" value="<?=e($name)?>"></div>
  <div><label for="enrollment_no">Enrollment number</label><input id="enrollment_no" name="enrollment_no" required maxlength="40" value="<?=e($enrollment)?>"></div>
  <div><label for="email">University email</label><input id="email" type="email" name="email" required maxlength="120" autocomplete="email" value="<?=e($email)?>"></div>
  <div><label for="phone">Phone number</label><input id="phone" type="tel" name="phone" required maxlength="30" autocomplete="tel" value="<?=e($phone)?>"></div>
</div>
<h2 class="form-section">Academic Details</h2>
<div class="register-grid">
  <div><label for="course">Course</label><input id="course" name="course" required maxlength="100" value="<?=e($course)?>" placeholder="e.g. B.Tech"></div>
  <div><label for="department">Department</label><input id="department" name="department" required maxlength="100" value="<?=e($department)?>" placeholder="e.g. Computer Science"></div>
</div>
<h2 class="form-section">Hostel Allocation</h2>
<label for="room_id">Available block and room</label><select id="room_id" name="room_id"><option value="0">No room assigned yet</option>
<?php foreach($rooms as $r): ?><option value="<?= (int)$r['id'] ?>" <?= $selectedRoom===(int)$r['id']?'selected':'' ?>>Block <?=e($r['block'])?> · Room <?=e($r['room_no'])?> (<?= (int)$r['capacity']-(int)$r['occupied'] ?> bed(s) left)</option><?php endforeach; ?>
</select><p class="sub" style="font-size:12px;margin:6px 0 0">Room assignment is optional. You can register without a room.</p>
<div class="register-grid" style="margin-top:12px">
  <div><label for="password">Create password</label><input id="password" type="password" name="password" required minlength="8" autocomplete="new-password"></div>
  <div><label for="confirm_password">Confirm password</label><input id="confirm_password" type="password" name="confirm_password" required minlength="8" autocomplete="new-password"></div>
</div>
<button class="btn" style="width:100%;justify-content:center;margin-top:18px">Create account</button>
<p class="sub" style="margin:18px 0 0;font-size:13px;text-align:center">Already have an account? <a href="../login.php">Log in</a></p></form></body></html>
