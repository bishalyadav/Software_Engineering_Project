<?php require '../config.php'; require '../includes/layout.php'; auth('student');
$rows=q("SELECT * FROM notices ORDER BY id DESC")->fetchAll();
top('Notices','notices',false); ?>
<h1>Notices & Announcements</h1><p class="sub">Updates from the hostel warden.</p>
<?php foreach($rows as $r) echo "<div class='card'><h2 style='margin:0 0 8px;font-size:18px'>".e($r['title'])."</h2><p>".nl2br(e($r['body']))."</p><small class='sub'>".$r['created_at']."</small></div>"; if(!$rows) echo "<div class='card'>No notices yet.</div>"; ?>
<?php bottom();