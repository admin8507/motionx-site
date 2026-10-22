<?php
require __DIR__.'/db.php'; sess();
if($_SERVER['REQUEST_METHOD']!=='POST') out(['ok'=>false,'error'=>'Método no permitido'],405);
if(empty($_SESSION['tid'])) out(['ok'=>false,'error'=>'Inicia sesión.'],401);
$s=db()->prepare('SELECT recomendados FROM testers WHERE id=?'); $s->execute([$_SESSION['tid']]); $r=$s->fetchColumn();
if($r===false) out(['ok'=>false,'error'=>'Inicia sesión.'],401);
if((int)$r<2) out(['ok'=>false,'error'=>'Desbloquea el Área Pro para enviar reportes.'],403);
$j=$_POST['juego']??''; if(!in_array($j,['El juego de la Hiena','¡Ya Compónte, Yamileth!'],true)) out(['ok'=>false,'error'=>'Elige un juego.']);
$sev=$_POST['severidad']??''; if(!in_array($sev,['Crítico','Alto','Medio','Bajo'],true)) out(['ok'=>false,'error'=>'Elige la severidad.']);
$m=mb_substr(trim($_POST['mision']??''),0,120); $d=mb_substr(trim($_POST['dispositivo']??''),0,60);
$de=trim($_POST['descripcion']??''); $pa=trim($_POST['pasos']??'');
if(mb_strlen($de)<15) out(['ok'=>false,'error'=>'Describe el bug con al menos 15 caracteres.']);
db()->prepare('INSERT INTO reportes(tester_id,juego,mision,severidad,dispositivo,descripcion,pasos) VALUES(?,?,?,?,?,?,?)')->execute([$_SESSION['tid'],$j,$m,$sev,$d,mb_substr($de,0,4000),mb_substr($pa,0,4000)]);
out(['ok'=>true]);
