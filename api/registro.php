<?php
require __DIR__.'/db.php';
if($_SERVER['REQUEST_METHOD']!=='POST') out(['ok'=>false,'error'=>'Método no permitido'],405);
if(!empty($_POST['website'])) out(['ok'=>false,'error'=>'Solicitud inválida'],400);
$pw=(string)($_POST['password']??'');
$n=trim($_POST['nombre']??''); $c=strtolower(trim($_POST['correo']??'')); $w=preg_replace('/[^0-9+]/','',$_POST['whatsapp']??'');
$e=(int)($_POST['edad']??0); $d=trim($_POST['dispositivo']??''); $g=$_POST['generos']??[]; $r=strtoupper(preg_replace('/[^A-Za-z0-9]/','',$_POST['ref']??''));
$disp=['PC','PlayStation','Xbox','Nintendo Switch','Android','iPhone / iPad','Otro'];
$gen=['Acción','Aventura','Shooter','Deportes','Carreras','RPG','Estrategia','Terror','Narrativo','Casual','Pelea','Mundo abierto'];
if(mb_strlen($n)<3||mb_strlen($n)>120) out(['ok'=>false,'error'=>'Escribe tu nombre completo.']);
if(!filter_var($c,FILTER_VALIDATE_EMAIL)) out(['ok'=>false,'error'=>'Correo no válido.']);
if(strlen($w)<10||strlen($w)>15) out(['ok'=>false,'error'=>'WhatsApp no válido.']);
if($e<18||$e>99) out(['ok'=>false,'error'=>'El programa es solo para mayores de 18 años.']);
if(!in_array($d,$disp,true)) out(['ok'=>false,'error'=>'Elige tu consola o dispositivo.']);
$g=array_values(array_intersect((array)$g,$gen));
if(strlen($pw)<8||strlen($pw)>72) out(['ok'=>false,'error'=>'La contraseña debe tener al menos 8 caracteres.']);
if(empty($_POST['privacidad'])) out(['ok'=>false,'error'=>'Debes aceptar el aviso de privacidad.']);
try{
  $db=db();
  $s=$db->prepare('SELECT codigo FROM testers WHERE correo=?'); $s->execute([$c]);
  if($s->fetchColumn()) out(['ok'=>false,'error'=>'Ese correo ya está registrado. Inicia sesión.']);
  $ref=null; if($r!==''){ $s=$db->prepare('SELECT codigo FROM testers WHERE codigo=?'); $s->execute([$r]); $ref=$s->fetchColumn()?:null; }
  $abc='ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
  do{ $code=''; for($i=0;$i<8;$i++) $code.=$abc[random_int(0,31)]; $s=$db->prepare('SELECT 1 FROM testers WHERE codigo=?'); $s->execute([$code]); } while($s->fetchColumn());
  $db->beginTransaction();
  $db->prepare('INSERT INTO testers(nombre,correo,whatsapp,edad,dispositivo,generos,codigo,ref_por,acepto_privacidad,ip,pass_hash) VALUES(?,?,?,?,?,?,?,?,1,?,?)')
     ->execute([$n,$c,$w,$e,$d,implode(', ',$g),$code,$ref,$_SERVER['REMOTE_ADDR']??'',password_hash($pw,PASSWORD_DEFAULT)]);
  if($ref) $db->prepare('UPDATE testers SET recomendados=recomendados+1 WHERE codigo=?')->execute([$ref]);
  $db->commit();
  sess(); session_regenerate_id(true); $_SESSION['tid']=(int)$db->lastInsertId()?:0;
  if(!$_SESSION['tid']){ $s=$db->prepare('SELECT id FROM testers WHERE codigo=?'); $s->execute([$code]); $_SESSION['tid']=(int)$s->fetchColumn(); }
  out(['ok'=>true,'codigo'=>$code]);
}catch(Throwable $x){ error_log('motionx registro: '.$x->getMessage()); out(['ok'=>false,'error'=>'Error del servidor, intenta más tarde.'],500); }
