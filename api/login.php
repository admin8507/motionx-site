<?php
require __DIR__.'/db.php';
if($_SERVER['REQUEST_METHOD']!=='POST') out(['ok'=>false,'error'=>'Método no permitido'],405);
usleep(300000);
$c=strtolower(trim($_POST['correo']??'')); $pw=(string)($_POST['password']??'');
$s=db()->prepare('SELECT id,pass_hash FROM testers WHERE correo=?'); $s->execute([$c]); $t=$s->fetch();
if(!$t||!$t['pass_hash']||!password_verify($pw,$t['pass_hash'])) out(['ok'=>false,'error'=>'Correo o contraseña incorrectos.']);
sess(); session_regenerate_id(true); $_SESSION['tid']=(int)$t['id']; out(['ok'=>true]);
