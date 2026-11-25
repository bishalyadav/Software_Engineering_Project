<?php require '../config.php'; require '../includes/layout.php'; auth('student'); $id=$_SESSION['u']['id'];
if(isset($_POST['title'])){ q('INSERT INTO complaints(student_id,title,category) VALUES(?,?,?)',[$id,$_POST['title'],$_POST['category']]); header('Location: complaints.php'); exit; }
$rows=q("SELECT * FROM complaints WHERE student_id=? ORDER BY id DESC",[$id])->fetchAll();
top('My Complaints','complaints',false); ?>
<h1>My Complaints</h1><p class="sub">Raise a new complaint and track its status.</p>
<div class="card"><form method="post" class="f"><div><label>Issue</label><input name="title" required></div><div><label>Category</label><select name="category"><option>Plumbing</option><option>Electrical</option><option>Carpentry</option><option>IT/Network</option><option>Other</option></select></div><button class="btn">Submit Complaint</button></form></div>
<div class="card tw"><table><tr><th>Complaint</th><th>Category</th><th>Date</th><th>Status</th></tr>
<?php foreach($rows as $r) echo "<tr><td class='m'>".e($r['title'])."</td><td>".e($r['category'])."</td><td>".substr($r['created_at'],0,10)."</td><td>".badge($r['status'])."</td></tr>"; ?></table></div>
<?php bottom();