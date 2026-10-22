<?php
require __DIR__.'/db.php';
if($_SERVER['REQUEST_METHOD']!=='POST') out(['ok'=>false,'error'=>'Método no permitido'],405);
if(!empty($_POST['website'])) out(['ok'=>false,'error'=>'Solicitud inválida'],400);
$n=trim($_POST['nombre']??''); $c=strtolower(trim($_POST['correo']??'')); $a=trim($_POST['asunto']??''); $m=trim($_POST['mensaje']??'');
$as=['Desarrollo de videojuego','Programa de testers','Prensa','Alianzas','Otro'];
if(mb_strlen($n)<2||mb_strlen($n)>120) out(['ok'=>false,'error'=>'Escribe tu nombre.']);
if(!filter_var($c,FILTER_VALIDATE_EMAIL)) out(['ok'=>false,'error'=>'Correo no válido.']);
if(!in_array($a,$as,true)) $a='Otro';
if(mb_strlen($m)<10||mb_strlen($m)>4000) out(['ok'=>false,'error'=>'El mensaje debe tener entre 10 y 4000 caracteres.']);
db()->prepare('INSERT INTO contactos(nombre,correo,asunto,mensaje,ip) VALUES(?,?,?,?,?)')->execute([$n,$c,$a,$m,$_SERVER['REMOTE_ADDR']??'']);
out(['ok'=>true]);
