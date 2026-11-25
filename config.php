<?php
// ---- DATABASE SETTINGS (apne hisaab se badlein) ----
const DB_HOST='localhost', DB_NAME='hostelhub', DB_USER='root', DB_PASS='';
session_start();
function db(){
  static $p;
  if(!$p){
    try {
      $p=new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4',DB_USER,DB_PASS,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
    } catch(PDOException $x) {
      http_response_code(500);
      $reason=$x->getCode()==='1049' ? 'Database `hostelhub` nahi mila.' : 'Database se connection nahi ho pa raha.';
      exit('<!doctype html><html lang="hi"><meta charset="utf-8"><title>HostelHub setup</title><body style="font:16px Arial;max-width:700px;margin:60px auto;padding:0 20px"><h1>HostelHub database setup zaroori hai</h1><p>'.htmlspecialchars($reason,ENT_QUOTES,'UTF-8').'</p><ol><li>Laragon mein MySQL start karein.</li><li>phpMyAdmin khol kar <code>database.sql</code> ko Import karein. File database khud bana kar tables aur demo data add karti hai.</li><li>Config mein MySQL username/password check karein.</li><li>Page refresh karein.</li></ol><p>Details: '.htmlspecialchars($x->getMessage(),ENT_QUOTES,'UTF-8').'</p></body></html>');
    }
  }
  return $p;
}
function q($sql,$a=[]){
  try { $s=db()->prepare($sql); $s->execute($a); return $s; }
  catch(PDOException $x){
    if($x->getCode()==='42S02' || (isset($x->errorInfo[1]) && (int)$x->errorInfo[1]===1146)){
      http_response_code(500);
      exit('<!doctype html><html lang="hi"><meta charset="utf-8"><title>HostelHub setup</title><body style="font:16px Arial;max-width:700px;margin:60px auto;padding:0 20px"><h1>HostelHub database setup zaroori hai</h1><p><code>hostelhub</code> database mein required tables nahi hain.</p><ol><li>Laragon mein MySQL start karein.</li><li>Naya setup ho toh <code>database.sql</code> import karein.</li><li>Pehle se chal rahe setup ko update kar rahe ho toh phpMyAdmin mein <code>database_update.sql</code> import karein; purana data safe rahega.</li><li>Page refresh karein.</li></ol><p>Config mein database name, username aur password bhi check karein.</p></body></html>');
    }
    throw $x;
  }
}
function e($v){ return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8'); }
function auth($role){ if(empty($_SESSION['u'])||$_SESSION['u']['role']!==$role){ header('Location: ../login.php'); exit; } }
function badge($s){ $c=['Pending'=>'pending','In Progress'=>'progress','Resolved'=>'resolved'][$s]??'pending'; return "<span class='badge $c'>".strtoupper(e($s))."</span>"; }
