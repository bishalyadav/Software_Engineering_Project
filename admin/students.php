<?php require '../config.php'; require '../includes/layout.php'; auth('admin');
if(isset($_POST['add'])){ try{ q("INSERT INTO users(name,email,password,role,room_id) VALUES(?,?,?, 'student',?)",[$_POST['name'],$_POST['email'],password_hash($_POST['password'],PASSWORD_DEFAULT),$_POST['room_id']?:null]); }catch(Exception $x){} header('Location: students.php'); exit; }
if(isset($_POST['del'])){ q("DELETE FROM complaints WHERE student_id=?",[$_POST['del']]); q("DELETE FROM users WHERE id=? AND role='student'",[$_POST['del']]); header('Location: students.php'); exit; }
$s='%'.($_GET['s']??'').'%';
$rows=q("SELECT u.*,r.room_no FROM users u LEFT JOIN rooms r ON r.id=u.room_id WHERE u.role='student' AND (u.name LIKE ? OR u.email LIKE ?) ORDER BY u.name",[$s,$s])->fetchAll();
$rooms=q("SELECT * FROM rooms ORDER BY room_no")->fetchAll();
top('Students','students'); ?>
<h1>Students Management</h1><p class="sub">Add, view and remove students.</p>
<div class="card" id="add"><form method="post" class="f"><div><label>Name</label><input name="name" required></div><div><label>Email</label><input type="email" name="email" required></div><div><label>Password</label><input name="password" value="password" required></div>
<div><label>Room</label><select name="room_id"><option value="">Not allocated</option><?php foreach($rooms as $r) echo "<option value='".$r['id']."'>".e($r['room_no'])."</option>"; ?></select></div><button class="btn" name="add" value="1">Add Student</button></form></div>
<div class="card tw"><table><tr><th>Name</th><th>Email</th><th>Room</th><th></th></tr>
<?php foreach($rows as $r) echo "<tr><td class='m'>".e($r['name'])."</td><td>".e($r['email'])."</td><td>".e($r['room_no']??'-')."</td><td><form method='post' onsubmit=\"return confirm('Delete student?')\"><button class='btn s d' name='del' value='".$r['id']."'>Delete</button></form></td></tr>"; ?></table></div>
<?php bottom();
