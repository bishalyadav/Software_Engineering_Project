<?php require '../config.php'; require '../includes/layout.php'; auth('admin');
if(isset($_POST['add'])){ try{ q('INSERT INTO rooms(room_no,block,capacity) VALUES(?,?,?)',[$_POST['room_no'],$_POST['block'],(int)$_POST['capacity']]); }catch(Exception $x){} header('Location: rooms.php'); exit; }
if(isset($_POST['del'])){ q('UPDATE users SET room_id=NULL WHERE room_id=?',[$_POST['del']]); q('DELETE FROM rooms WHERE id=?',[$_POST['del']]); header('Location: rooms.php'); exit; }
$rooms=q("SELECT r.*,(SELECT COUNT(*) FROM users u WHERE u.room_id=r.id) occ FROM rooms r ORDER BY block,room_no")->fetchAll();
$tot=count($rooms); $occ=array_sum(array_column($rooms,'occ')); $av=array_sum(array_column($rooms,'capacity'))-$occ;
top('Room Management','rooms'); ?>
<div class="head"><div><h1 style="font-size:24px">Room Management</h1><p class="sub">Manage capacity, allocations, and room status across all blocks.</p></div>
<button class="btn" onclick="document.getElementById('add').style.display='block'"><span class="ms" style="font-size:16px">add</span>Add Room</button></div>
<div class="card" id="add" style="display:none"><form method="post" class="f"><div><label>Room No</label><input name="room_no" required placeholder="A-101"></div><div><label>Block</label><input name="block" required placeholder="A"></div><div><label>Capacity</label><input type="number" name="capacity" value="2" min="1" required></div><button class="btn" name="add" value="1">Save Room</button></form></div>
<div class="grid3" style="margin-bottom:24px">
<div class="card stat" style="align-items:center"><div style="display:flex;gap:14px;align-items:center"><div class="ico g"><span class="ms">meeting_room</span></div><div><small>Total Rooms</small><span class="n"><?=$tot?></span></div></div></div>
<div class="card stat" style="align-items:center"><div style="display:flex;gap:14px;align-items:center"><div class="ico"><span class="ms">person</span></div><div><small>Total Occupied</small><span class="n"><?=$occ?></span></div></div></div>
<div class="card stat" style="align-items:center"><div style="display:flex;gap:14px;align-items:center"><div class="ico gr"><span class="ms">bed</span></div><div><small>Available Beds</small><span class="n"><?=$av?></span></div></div></div></div>
<div class="card tw"><table><tr><th>Room</th><th>Block</th><th>Capacity</th><th>Occupied</th><th>Status</th><th></th></tr>
<?php foreach($rooms as $r){ $full=$r['occ']>=$r['capacity']; echo "<tr><td class='m'>".e($r['room_no'])."</td><td>".e($r['block'])."</td><td>".$r['capacity']."</td><td>".$r['occ']."</td><td>".($full?"<span class='badge pending'>FULL</span>":"<span class='badge resolved'>AVAILABLE</span>")."</td><td><form method='post' onsubmit=\"return confirm('Delete room?')\"><button class='btn s d' name='del' value='".$r['id']."'>Delete</button></form></td></tr>"; } ?></table></div>
<?php bottom();
