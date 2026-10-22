<?php
ini_set('session.cookie_httponly',1); session_start();
require dirname(__DIR__).'/api/db.php';
$err='';
if(!empty($_SESSION['mxadmin'])&&isset($_POST['rid'],$_POST['estado'],$_POST['csrf'])&&hash_equals($_SESSION['csrf']??'',$_POST['csrf'])&&in_array($_POST['estado'],['aprobado','rechazado','pendiente'],true)){ db()->prepare('UPDATE reportes SET estado=? WHERE id=?')->execute([$_POST['estado'],(int)$_POST['rid']]); header('Location: ./#reportes'); exit; }
if(isset($_GET['salir'])){ session_destroy(); header('Location: ./'); exit; }
if($_SERVER['REQUEST_METHOD']==='POST'&&isset($_POST['u'])){
  usleep(400000);
  if(hash_equals($cfg['admin_user'],$_POST['u']??'') && hash_equals($cfg['admin_sha256'],hash('sha256',$_POST['p']??''))){ session_regenerate_id(true); $_SESSION['mxadmin']=1; header('Location: ./'); exit; }
  $err='Usuario o contraseña incorrectos.';
}
if(empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(16));
?><!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex"><title>Admin | Motion X</title><link rel="stylesheet" href="/style.css"></head><body>
<header class="nav"><a href="/" class="brand"><img src="/assets/logo.jpg" alt=""><span>MOTION<b>X</b> admin</span></a><?php if(!empty($_SESSION['mxadmin'])):?><nav><a href="?salir=1">Salir</a></nav><?php endif;?></header>
<section class="sec">
<?php if(empty($_SESSION['mxadmin'])): ?>
<div class="box"><h2>Acceso</h2><form method="post" class="form"><input name="u" placeholder="Usuario" required><input name="p" type="password" placeholder="Contraseña" required><button class="btn">Entrar</button><p class="center"><?=h($err)?></p></form></div>
<?php else: $db=db();
$tot=$db->query('SELECT COUNT(*) FROM testers')->fetchColumn(); $conref=$db->query('SELECT COUNT(*) FROM testers WHERE ref_por IS NOT NULL')->fetchColumn();
$rows=$db->query('SELECT * FROM testers ORDER BY id DESC LIMIT 1000')->fetchAll(PDO::FETCH_ASSOC);
$top=$db->query('SELECT nombre,codigo,recomendados FROM testers WHERE recomendados>0 ORDER BY recomendados DESC LIMIT 50')->fetchAll(PDO::FETCH_ASSOC); ?>
<h2>Testers</h2><p class="center">Total: <b><?=$tot?></b> · Llegaron por recomendación: <b><?=$conref?></b></p>
<h3>Top recomendadores</h3><div class="wrap"><table><tr><th>Nombre</th><th>Código</th><th>Recomendados</th></tr><?php foreach($top as $t):?><tr><td><?=h($t['nombre'])?></td><td><?=h($t['codigo'])?></td><td><?=h($t['recomendados'])?></td></tr><?php endforeach;?></table></div>
<h3 style="margin-top:30px">Registros</h3><div class="wrap"><table><tr><th>#</th><th>Fecha</th><th>Nombre</th><th>Correo</th><th>WhatsApp</th><th>Edad</th><th>Dispositivo</th><th>Géneros</th><th>Código</th><th>Recomendado por</th><th>Recom.</th></tr>
<?php foreach($rows as $t):?><tr><td><?=h($t['id'])?></td><td><?=h($t['creado'])?></td><td><?=h($t['nombre'])?></td><td><?=h($t['correo'])?></td><td><?=h($t['whatsapp'])?></td><td><?=h($t['edad'])?></td><td><?=h($t['dispositivo'])?></td><td><?=h($t['generos'])?></td><td><?=h($t['codigo'])?></td><td><?=h($t['ref_por'])?></td><td><?=h($t['recomendados'])?></td></tr><?php endforeach;?></table></div>
<h3 id="reportes" style="margin-top:30px">Reportes de bugs</h3><div class="wrap"><table><tr><th>#</th><th>Fecha</th><th>Tester</th><th>Juego</th><th>Misión</th><th>Sev.</th><th>Dispositivo</th><th>Descripción</th><th>Pasos</th><th>Estado</th></tr>
<?php foreach($db->query('SELECT r.*,t.nombre,t.correo FROM reportes r LEFT JOIN testers t ON t.id=r.tester_id ORDER BY r.id DESC LIMIT 500') as $r):?><tr><td><?=h($r['id'])?></td><td><?=h($r['creado'])?></td><td><?=h($r['nombre'])?><br><small><?=h($r['correo'])?></small></td><td><?=h($r['juego'])?></td><td><?=h($r['mision'])?></td><td><?=h($r['severidad'])?></td><td><?=h($r['dispositivo'])?></td><td><?=nl2br(h($r['descripcion']))?></td><td><?=nl2br(h($r['pasos']))?></td><td><form method="post"><input type="hidden" name="csrf" value="<?=h($_SESSION['csrf'])?>"><input type="hidden" name="rid" value="<?=h($r['id'])?>"><select name="estado" onchange="this.form.submit()"><?php foreach(['pendiente','aprobado','rechazado'] as $e):?><option<?=$e===$r['estado']?' selected':''?>><?=$e?></option><?php endforeach;?></select></form></td></tr><?php endforeach;?></table></div>
<h3 style="margin-top:30px">Mensajes de contacto</h3><div class="wrap"><table><tr><th>#</th><th>Fecha</th><th>Nombre</th><th>Correo</th><th>Asunto</th><th>Mensaje</th></tr>
<?php foreach($db->query('SELECT * FROM contactos ORDER BY id DESC LIMIT 500') as $c):?><tr><td><?=h($c['id'])?></td><td><?=h($c['creado'])?></td><td><?=h($c['nombre'])?></td><td><?=h($c['correo'])?></td><td><?=h($c['asunto'])?></td><td><?=nl2br(h($c['mensaje']))?></td></tr><?php endforeach;?></table></div>
<?php endif;?></section></body></html>
