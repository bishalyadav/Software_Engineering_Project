<?php require '../config.php'; require '../includes/layout.php'; auth('student');
$rows=q("SELECT * FROM mess_menu ORDER BY FIELD(day,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')")->fetchAll();
top('Mess Menu','mess',false); ?>
<h1>Mess Menu</h1><p class="sub">Weekly mess menu.</p>
<div class="card tw"><table><tr><th>Day</th><th>Breakfast</th><th>Lunch</th><th>Dinner</th></tr>
<?php foreach($rows as $r) echo "<tr><td class='m'>".e($r['day'])."</td><td>".e($r['breakfast'])."</td><td>".e($r['lunch'])."</td><td>".e($r['dinner'])."</td></tr>"; ?></table></div>
<?php bottom();