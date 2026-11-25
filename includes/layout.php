<?php
function top($title,$active,$admin=true){
  $nav=$admin?[['dashboard','dashboard','Dashboard'],['students','group','Students Management'],['rooms','bed','Rooms Management'],['complaints','warning','Complaints Management'],['mess','restaurant','Mess Management'],['notices','campaign','Notices & Announcements'],['messages','mail','Contact Messages']]
             :[['index','dashboard','Dashboard'],['profile','account_circle','My Profile'],['room','bed','My Room'],['complaints','warning','My Complaints'],['mess','restaurant','Mess Menu'],['notices','campaign','Notices & Announcements']];
  $u=$_SESSION['u']; ?>
<!DOCTYPE html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?=e($title)?> - HostelHub</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:FILL@0..1" rel="stylesheet">
<link rel="stylesheet" href="../assets/style.css"></head><body>
<div class="overlay" onclick="document.body.classList.remove('open')"></div>
<aside class="side"><div class="logo">HostelHub</div><nav>
<?php foreach($nav as [$f,$i,$l]) echo "<a href='$f.php' class='".($f==$active?'active':'')."'><span class='ms'>$i</span>$l</a>"; ?>
</nav><a href="../logout.php" class="logout"><span class="ms">logout</span>Logout</a></aside>
<main><header class="topbar"><button class="menu ms" onclick="document.body.classList.toggle('open')">menu</button>
<form class="search" action="<?=$admin?'students.php':'#'?>"><span class="ms">search</span><input name="s" placeholder="Search..." value="<?=e($_GET['s']??'')?>"></form>
<span class="ms bell">notifications</span><div class="who"><b><?=e($u['name'])?></b><small><?=$admin?'Hostel Manager':'Student'?></small></div></header>
<section class="content">
<?php }
function bottom(){ echo "</section></main></body></html>"; }
