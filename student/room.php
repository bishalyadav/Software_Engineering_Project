<?php require '../config.php'; require '../includes/layout.php'; auth('student'); $id=$_SESSION['u']['id'];
$r=q("SELECT r.*,(SELECT COUNT(*) FROM users x WHERE x.room_id=r.id) occ FROM users u LEFT JOIN rooms r ON r.id=u.room_id WHERE u.id=?",[$id])->fetch();
$mates=$r&&$r['id']?q("SELECT name,email FROM users WHERE room_id=? AND id<>?",[$r['id'],$id])->fetchAll():[];
top('My Room','room',false); ?>
<h1>My Room</h1><p class="sub">Your room details and roommates.</p>
<?php if(!$r||!$r['id']) echo "<div class='card'>Abhi aapko koi room allot nahi hua hai.</div>"; else { ?>
<div class="grid3" style="margin-bottom:24px"><div class="card stat"><div><small>Room No</small><span class="n"><?=e($r['room_no'])?></span></div><div class="ico"><span class="ms">meeting_room</span></div></div>
<div class="card stat"><div><small>Block</small><span class="n"><?=e($r['block'])?></span></div><div class="ico g"><span class="ms">apartment</span></div></div>
<div class="card stat"><div><small>Occupancy</small><span class="n"><?=$r['occ']?> <i>/ <?=$r['capacity']?></i></span></div><div class="ico gr"><span class="ms">bed</span></div></div></div>
<div class="card tw"><div class="cardh"><h2>Roommates</h2></div><table><tr><th>Name</th><th>Email</th></tr><?php foreach($mates as $m) echo "<tr><td class='m'>".e($m['name'])."</td><td>".e($m['email'])."</td></tr>"; if(!$mates) echo "<tr><td colspan=2>No roommates yet.</td></tr>"; ?></table></div>
<?php } bottom();