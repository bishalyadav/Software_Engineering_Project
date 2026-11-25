<?php
session_start();
if(!empty($_SESSION['u']['role'])){
  $target=$_SESSION['u']['role']==='admin'?'admin/dashboard.php':'student/index.php';
  header('Location: '.$target);
  exit;
}
require __DIR__.'/Home/landing.php';
