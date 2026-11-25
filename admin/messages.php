<?php
require '../config.php'; require '../includes/layout.php'; auth('admin');
if(empty($_SESSION['message_csrf'])) $_SESSION['message_csrf']=bin2hex(random_bytes(32));
if(isset($_POST['id'])){
  if(!hash_equals($_SESSION['message_csrf'],$_POST['csrf_token']??'')){ http_response_code(400); exit('Invalid request. Please go back and retry.'); }
  $status=$_POST['status']??'';
  if(in_array($status,['New','Read','Closed'],true)) q('UPDATE contact_messages SET status=? WHERE id=?',[$status,(int)$_POST['id']]);
  header('Location: messages.php'); exit;
}
$rows=q('SELECT * FROM contact_messages ORDER BY id DESC')->fetchAll();
top('Contact Messages','messages'); ?>
<h1>Contact Messages</h1><p class="sub">Messages submitted through the public contact form.</p>
<div class="card tw"><table><tr><th>Received</th><th>Name</th><th>Email</th><th>Subject</th><th>Message</th><th>Status</th><th>Update</th></tr>
<?php foreach($rows as $r): ?><tr><td><?=e($r['created_at'])?></td><td class="m"><?=e($r['full_name'])?></td><td><a href="mailto:<?=e($r['email'])?>"><?=e($r['email'])?></a></td><td><?=e($r['subject'])?></td><td style="min-width:260px;white-space:pre-wrap"><?=e($r['message'])?></td><td><?=badge($r['status']==='New'?'Pending':($r['status']==='Closed'?'Resolved':'In Progress'))?></td><td><form method="post" style="display:flex;gap:6px;align-items:center"><input type="hidden" name="csrf_token" value="<?=e($_SESSION['message_csrf'])?>"><input type="hidden" name="id" value="<?=(int)$r['id']?>"><select name="status" aria-label="Message status"><?php foreach(['New','Read','Closed'] as $s): ?><option value="<?=e($s)?>" <?=$s===$r['status']?'selected':''?>><?=e($s)?></option><?php endforeach; ?></select><button class="btn s">Save</button></form></td></tr><?php endforeach; ?>
<?php if(!$rows) echo '<tr><td colspan="7">No contact messages yet.</td></tr>'; ?></table></div>
<?php bottom();
