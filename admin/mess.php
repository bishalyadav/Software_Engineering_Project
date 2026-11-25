<?php require '../config.php'; require '../includes/layout.php'; auth('admin');
if(isset($_POST['day'])){ q('UPDATE mess_menu SET breakfast=?,lunch=?,dinner=? WHERE day=?',[$_POST['b'],$_POST['l'],$_POST['d'],$_POST['day']]); header('Location: mess.php'); exit; }
$rows=q("SELECT * FROM mess_menu ORDER BY FIELD(day,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')")->fetchAll();
top('Mess','mess'); ?>
<h1>Mess Management</h1><p class="sub">Update the weekly mess menu.</p>
<div class="card tw"><table><tr><th>Day</th><th>Breakfast</th><th>Lunch</th><th>Dinner</th><th></th></tr>
<?php foreach($rows as $r) echo "<tr><form method='post'><td class='m'>".e($r['day'])."<input type='hidden' name='day' value='".e($r['day'])."'></td><td><input name='b' value='".e($r['breakfast'])."'></td><td><input name='l' value='".e($r['lunch'])."'></td><td><input name='d' value='".e($r['dinner'])."'></td><td><button class='btn s'>Save</button></td></form></tr>"; ?></table></div>
<?php bottom();
