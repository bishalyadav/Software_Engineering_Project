<?php require '../config.php'; require '../includes/layout.php'; auth('admin');
$st=q("SELECT COUNT(*) FROM users WHERE role='student'")->fetchColumn();
$tr=q("SELECT COUNT(*) FROM rooms")->fetchColumn();
$or=q("SELECT COUNT(DISTINCT room_id) FROM users WHERE room_id IS NOT NULL")->fetchColumn();
$pc=q("SELECT COUNT(*) FROM complaints WHERE status='Pending'")->fetchColumn();
$cap=q("SELECT COALESCE(SUM(capacity),0) FROM rooms")->fetchColumn();
$av=max(0,$cap-$st); $pct=$cap?round($st/$cap*100,1):0; $rp=$tr?round($or/$tr*100):0;
$rows=q("SELECT c.title,c.category,c.status,u.name,r.room_no FROM complaints c JOIN users u ON u.id=c.student_id LEFT JOIN rooms r ON r.id=u.room_id ORDER BY c.id DESC LIMIT 4")->fetchAll();
top('Dashboard','dashboard'); ?>
<h1>Good Morning, Admin</h1><p class="sub">Here is the current status of HostelHub today.</p>
<div class="grid4" style="margin-bottom:24px">
<div class="card stat"><div><small>Total Students</small><span class="n"><?=$st?></span></div><div class="ico"><span class="ms">group</span></div></div>
<div class="card stat"><div style="flex:1;margin-right:12px"><small>Occupied Rooms</small><span class="n"><?=$or?> <i>/ <?=$tr?></i></span><div class="bar"><b style="width:<?=$rp?>%"></b></div></div><div class="ico"><span class="ms">bed</span></div></div>
<div class="card stat"><div><small>Pending Complaints</small><span class="n red"><?=$pc?></span></div><div class="ico r"><span class="ms">warning</span></div></div>
<div class="card stat"><div><small>Available Beds</small><span class="n blue"><?=$av?></span></div><div class="ico g"><span class="ms">single_bed</span></div></div></div>
<div class="two"><div class="card"><div class="cardh"><h2>Occupancy Overview</h2><a href="rooms.php">View Details</a></div>
<div class="big" style="background:linear-gradient(90deg,#0058bc <?=$pct?>%,#e0e3e5 <?=$pct?>%)"></div>
<span class="leg"><i class="dot" style="background:#0058bc"></i>Occupied (<?=$pct?>%)</span><span class="leg"><i class="dot" style="background:#d5d8de"></i>Available (<?=100-$pct?>%)</span></div>
<div class="card"><div class="cardh"><h2>Quick Actions</h2></div><div class="qa">
<a href="students.php#add"><span class="ms">person_add</span>Add Student</a><a href="rooms.php#add"><span class="ms">add_box</span>Add Room</a>
<a href="complaints.php"><span class="ms">assignment</span>Manage Complaints</a><a href="mess.php"><span class="ms">restaurant_menu</span>Update Mess Menu</a></div></div></div>
<div class="card tw"><div class="cardh"><h2>Recent Complaints</h2><a href="complaints.php">View All →</a></div>
<table><tr><th>Complaint</th><th>Student</th><th>Room</th><th>Category</th><th>Status</th></tr>
<?php foreach($rows as $r) echo "<tr><td class='m'>".e($r['title'])."</td><td>".e($r['name'])."</td><td>".e($r['room_no']??'-')."</td><td>".e($r['category'])."</td><td>".badge($r['status'])."</td></tr>"; ?></table></div>
<?php bottom();
