<?php require '../config.php'; require '../includes/layout.php'; auth('admin');
if(isset($_POST['id'])){ q('UPDATE complaints SET status=? WHERE id=?',[$_POST['status'],$_POST['id']]); header('Location: complaints.php'); exit; }
$rows=q("SELECT c.*,u.name,r.room_no FROM complaints c JOIN users u ON u.id=c.student_id LEFT JOIN rooms r ON r.id=u.room_id ORDER BY c.id DESC")->fetchAll();
top('Complaints','complaints'); ?>
<h1>Complaints Management</h1><p class="sub">Track and update complaint status.</p>
<div class="card tw"><table><tr><th>Complaint</th><th>Student</th><th>Room</th><th>Category</th><th>Status</th><th>Update</th></tr>
<?php foreach($rows as $r){ echo "<tr><td class='m'>".e($r['title'])."</td><td>".e($r['name'])."</td><td>".e($r['room_no']??'-')."</td><td>".e($r['category'])."</td><td>".badge($r['status'])."</td><td><form method='post' style='display:flex;gap:6px'><input type='hidden' name='id' value='".$r['id']."'><select name='status' style='width:130px'>"; foreach(['Pending','In Progress','Resolved'] as $s) echo "<option".($s==$r['status']?' selected':'').">$s</option>"; echo "</select><button class='btn s'>Save</button></form></td></tr>"; } ?></table></div>
<?php bottom();
