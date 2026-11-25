<?php require '../config.php'; require '../includes/layout.php'; auth('admin');
if(isset($_POST['add'])){ q('INSERT INTO notices(title,body) VALUES(?,?)',[$_POST['title'],$_POST['body']]); header('Location: notices.php'); exit; }
if(isset($_POST['del'])){ q('DELETE FROM notices WHERE id=?',[$_POST['del']]); header('Location: notices.php'); exit; }
$rows=q("SELECT * FROM notices ORDER BY id DESC")->fetchAll();
top('Notices','notices'); ?>
<h1>Notices & Announcements</h1><p class="sub">Post announcements for all students.</p>
<div class="card"><form method="post"><label>Title</label><input name="title" required><label>Message</label><textarea name="body" rows="3" required></textarea><button class="btn" name="add" value="1" style="margin-top:12px">Post Notice</button></form></div>
<?php foreach($rows as $r) echo "<div class='card'><div class='cardh'><h2>".e($r['title'])."</h2><form method='post'><button class='btn s d' name='del' value='".$r['id']."'>Delete</button></form></div><p>".nl2br(e($r['body']))."</p><small class='sub'>".$r['created_at']."</small></div>"; ?>
<?php bottom();
