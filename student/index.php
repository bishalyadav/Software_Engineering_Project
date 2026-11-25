<?php require '../config.php'; require '../includes/layout.php'; auth('student');
$id=$_SESSION['u']['id'];
if(isset($_POST['title'])){ q('INSERT INTO complaints(student_id,title,category) VALUES(?,?,?)',[$id,$_POST['title'],$_POST['category']]); header('Location: index.php'); exit; }
$room=q("SELECT r.room_no,r.block FROM users u LEFT JOIN rooms r ON r.id=u.room_id WHERE u.id=?",[$id])->fetch();
$mine=q("SELECT * FROM complaints WHERE student_id=? ORDER BY id DESC",[$id])->fetchAll();
$menu=q("SELECT * FROM mess_menu ORDER BY FIELD(day,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')")->fetchAll();
$notes=q("SELECT * FROM notices ORDER BY id DESC LIMIT 3")->fetchAll();
top('Dashboard','index',false); ?>
<h1>Welcome, <?=e($_SESSION['u']['name'])?></h1><p class="sub">Your room: <b><?=e($room['room_no']??'Not allocated')?></b></p>
<div class="two"><div class="card"><div class="cardh"><h2>Raise a Complaint</h2></div><form method="post" class="f"><div><label>Issue</label><input name="title" required></div><div><label>Category</label><select name="category"><option>Plumbing</option><option>Electrical</option><option>Carpentry</option><option>IT/Network</option><option>Other</option></select></div><button class="btn">Submit</button></form></div>
<div class="card"><div class="cardh"><h2>Notices</h2></div><?php foreach($notes as $n) echo "<p><b>".e($n['title'])."</b><br>".e($n['body'])."</p>"; ?></div></div>
<div class="card tw"><div class="cardh"><h2>My Complaints</h2></div><table><tr><th>Complaint</th><th>Category</th><th>Status</th></tr><?php foreach($mine as $r) echo "<tr><td class='m'>".e($r['title'])."</td><td>".e($r['category'])."</td><td>".badge($r['status'])."</td></tr>"; ?></table></div>
<div class="card tw"><div class="cardh"><h2>Mess Menu</h2></div><table><tr><th>Day</th><th>Breakfast</th><th>Lunch</th><th>Dinner</th></tr><?php foreach($menu as $r) echo "<tr><td class='m'>".e($r['day'])."</td><td>".e($r['breakfast'])."</td><td>".e($r['lunch'])."</td><td>".e($r['dinner'])."</td></tr>"; ?></table></div>
<?php bottom();
