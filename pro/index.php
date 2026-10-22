<?php
require dirname(__DIR__).'/api/db.php'; sess();
if(empty($_SESSION['tid'])){ header('Location: /login/'); exit; }
$db=db(); $s=$db->prepare('SELECT * FROM testers WHERE id=?'); $s->execute([$_SESSION['tid']]); $t=$s->fetch();
if(!$t){ $_SESSION=[]; header('Location: /login/'); exit; }
$rec=(int)$t['recomendados']; $ok=$rec>=2; $link='https://motionx.pro/registro/?ref='.$t['codigo'];
$msg='¡Hazte tester de videojuegos Motion X y gana hasta $5,000 MXN por juego! Regístrate aquí: '.$link;
$r=$db->prepare("SELECT estado,COUNT(*) n FROM reportes WHERE tester_id=? GROUP BY estado"); $r->execute([$t['id']]); $rs=['pendiente'=>0,'aprobado'=>0,'rechazado'=>0]; foreach($r as $x) $rs[$x['estado']]=(int)$x['n'];
$mis=$db->prepare('SELECT juego,mision,severidad,estado,creado FROM reportes WHERE tester_id=? ORDER BY id DESC LIMIT 20'); $mis->execute([$t['id']]); $mis=$mis->fetchAll();
$rank=$db->query('SELECT nombre,recomendados FROM testers WHERE recomendados>0 ORDER BY recomendados DESC, id ASC LIMIT 10')->fetchAll();
function corto($n){ $p=preg_split('/\s+/',trim($n)); return $p[0].(isset($p[1])?' '.mb_substr($p[1],0,1).'.':''); }
$missions=[
 ['El juego de la Hiena','Misión 1: Recorrido completo del barrio','Camina por todo el mapa y reporta paredes atravesables, caídas fuera del mapa o texturas faltantes.'],
 ['El juego de la Hiena','Misión 2: Diálogos y voces','Habla con todos los personajes y reporta audios cortados, desfasados o textos con errores.'],
 ['El juego de la Hiena','Misión 3: Rendimiento','Juega 15 minutos y reporta tirones, caídas de FPS o calentamiento del dispositivo.'],
 ['¡Ya Compónte, Yamileth!','Misión 1: Todos los finales','Llega a cada final y reporta decisiones que no lleven a ningún lado o finales repetidos.'],
 ['¡Ya Compónte, Yamileth!','Misión 2: Ortografía y textos','Reporta errores de ortografía, textos que se salen de pantalla o que no se leen bien.'],
 ['¡Ya Compónte, Yamileth!','Misión 3: Celular','Juega en tu celular vertical y horizontal y reporta botones que no respondan.'],
];
?><!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Área Pro | Motion X</title><meta name="description" content="Motion X, estudio de videojuegos de Grupo Garga."><link rel="icon" href="/assets/logo.jpg"><link rel="stylesheet" href="/style.css"></head><body>
<header class="nav"><a href="/" class="brand"><img src="/assets/logo.jpg" alt="Motion X"><span>MOTION<b>X</b></span></a><button class="burger" aria-label="Menú" onclick="document.body.classList.toggle('menu')">☰</button><nav><a href="/juegos/">Juegos</a><a href="/servicios/">Servicios</a><a href="/estudio/">Estudio</a><a href="/contacto/">Contacto</a><a href="/login/">Entrar</a><a href="/registro/" class="btn sm">Ser tester</a></nav></header>
<section class="sec"><div class="pro-sec"><p class="tag">Área Pro</p><h2 style="text-align:left">Hola, <?=h(corto($t['nombre']))?></h2>
<?php if(!$ok): ?>
<div class="box" style="max-width:none"><h3>Desbloquea el Área Pro completa</h3><p>Se desbloquea cuando <b>2 personas</b> se registran como testers con tu link personal.</p>
<p class="big">Avance: <b class="money"><?=$rec?>/2</b></p><div class="prog"><div style="width:<?=min(100,$rec*50)?>%"></div></div>
<p><b>Gana $2,599 MXN por cada amigo o familiar que se registre como tester con tu link.</b>*</p>
<div class="reflink"><input id="link" readonly value="<?=h($link)?>"><div class="cta center-row"><button class="btn" onclick="var l=document.getElementById('link');l.select();navigator.clipboard&&navigator.clipboard.writeText(l.value);this.textContent='¡Copiado!'">Copiar link</button><a class="btn wa" target="_blank" rel="noopener" href="https://wa.me/?text=<?=rawurlencode($msg)?>">Compartir por WhatsApp</a><a class="btn fb" target="_blank" rel="noopener" href="https://www.facebook.com/sharer/sharer.php?u=<?=rawurlencode($link)?>">Compartir en Facebook</a></div></div>
<p class="note">Comparte tu link tú mismo; nunca te pediremos datos de otras personas. *Bono sujeto a validación según los <a href="/terminos/">Términos</a>.</p></div>
<h3 style="margin:30px 0 12px">Vista previa del contenido Pro</h3><div class="lock"><div class="kpis"><div class="kpi"><b>2</b>Betas</div><div class="kpi"><b>6</b>Misiones</div><div class="kpi"><b>$</b>Ganancias</div><div class="kpi"><b>#</b>Ranking</div></div></div>
<?php else: ?>
<h3>Tablero de ganancias y recomendados</h3><div class="kpis">
<div class="kpi"><b><?=$rec?></b>Recomendados registrados</div>
<div class="kpi"><b>$<?=number_format($rec*2599)?></b>Bono por recomendados (por validar)*</div>
<div class="kpi"><b><?=$rs['aprobado']?></b>Reportes aprobados</div>
<div class="kpi"><b><?=$rs['pendiente']?></b>Reportes en revisión</div></div>
<p class="note">*Los montos están sujetos a validación según los <a href="/terminos/">Términos</a>. El pago por juego depende de completar las pruebas y de reportes aprobados. Tu link: <span class="c"><?=h($link)?></span></p>

<div class="pro-sec"><h3>Betas para probar</h3><div class="grid2">
<article class="card"><img src="/assets/hiena.jpg" alt="El juego de la Hiena"><div class="pad"><span class="pill">Acción · Mundo abierto 3D</span><h3>El juego de la Hiena</h3><p>Recorre las calles del barrio en una aventura urbana en 3D llena de neón. Cumple misiones, habla con personajes con voz propia y gánate el respeto de la ciudad.</p><div class="cta"><a class="btn" href="http://108.181.203.225:10060/" target="_blank" rel="noopener">Jugar beta</a><a class="btn ghost" href="/juegos/hiena/">Ficha</a></div></div></article><article class="card"><img src="/assets/yamileth.jpg" alt="¡Ya Compónte, Yamileth!"><div class="pad"><span class="pill">Narrativo · Comedia</span><h3>¡Ya Compónte, Yamileth!</h3><p>Una historia interactiva con humor: acompaña a Yamileth en una noche por la ciudad y decide con tus elecciones cómo termina todo.</p><div class="cta"><a class="btn" href="http://108.181.203.225:10056/" target="_blank" rel="noopener">Jugar beta</a><a class="btn ghost" href="/juegos/yamileth/">Ficha</a></div></div></article></div></div>
<div class="pro-sec"><h3>Misiones de prueba</h3><div class="grid3"><?php foreach($missions as $m): ?><div class="svc"><span class="pill"><?=h($m[0])?></span><h3><?=h($m[1])?></h3><p><?=h($m[2])?></p></div><?php endforeach; ?></div></div>
<div class="pro-sec"><div class="box" style="max-width:none"><h3>Reportar un bug</h3><form id="rf" class="form" style="max-width:none">
<select name="juego" required><option value="">Juego</option><option>El juego de la Hiena</option><option>¡Ya Compónte, Yamileth!</option></select>
<select name="mision"><option value="">Misión (opcional)</option><?php foreach($missions as $m): ?><option><?=h($m[0].' — '.$m[1])?></option><?php endforeach; ?></select>
<select name="severidad" required><option value="">Severidad</option><option>Crítico</option><option>Alto</option><option>Medio</option><option>Bajo</option></select>
<input name="dispositivo" placeholder="Dispositivo y navegador (ej. Android 14, Chrome)" maxlength="60" value="<?=h($t['dispositivo'])?>">
<textarea name="descripcion" rows="4" placeholder="¿Qué pasó? (mínimo 15 caracteres)" required minlength="15"></textarea>
<textarea name="pasos" rows="3" placeholder="Pasos para reproducirlo"></textarea><button class="btn">Enviar reporte</button><p id="rmsg" class="center"></p></form></div></div>
<div class="pro-sec grid2"><div><h3>Mis reportes</h3><div class="wrap"><table><tr><th>Juego</th><th>Severidad</th><th>Estado</th><th>Fecha</th></tr><?php foreach($mis as $m): ?><tr><td><?=h($m['juego'])?></td><td><?=h($m['severidad'])?></td><td><?=h($m['estado'])?></td><td><?=h(substr($m['creado'],0,10))?></td></tr><?php endforeach; if(!$mis) echo '<tr><td colspan="4">Aún no envías reportes.</td></tr>'; ?></table></div></div>
<div><h3>Ranking de recomendadores</h3><div class="wrap"><table><tr><th>#</th><th>Tester</th><th>Recomendados</th></tr><?php foreach($rank as $i=>$x): ?><tr><td><?=$i+1?></td><td><?=h(corto($x['nombre']))?></td><td><?=(int)$x['recomendados']?></td></tr><?php endforeach; ?></table></div></div></div>
<script>document.getElementById('rf').addEventListener('submit',async e=>{e.preventDefault();const m=document.getElementById('rmsg');m.textContent='Enviando…';try{const j=await (await fetch('/api/reporte.php',{method:'POST',body:new FormData(e.target),credentials:'same-origin'})).json();if(j.ok){e.target.reset();m.textContent='¡Reporte enviado! Lo revisaremos pronto.';setTimeout(()=>location.reload(),1200)}else m.textContent=j.error}catch(x){m.textContent='Error de conexión'}})</script>
<?php endif; ?>
<p class="center" style="margin-top:20px"><a class="btn ghost" href="/api/logout.php">Cerrar sesión</a></p></div></section><footer><div class="fcols"><div><b>MOTION<span class="c">X</span></b><br>Estudio de videojuegos de Grupo Garga</div><div><a href="/juegos/">Juegos</a><br><a href="/servicios/">Servicios</a><br><a href="/estudio/">Estudio</a></div><div><a href="/registro/">Programa de testers</a><br><a href="/login/">Acceso testers</a><br><a href="/pro/">Área Pro</a></div><div><a href="/contacto/">Contacto</a><br><a href="/terminos/">Términos</a><br><a href="/privacidad/">Aviso de privacidad</a></div></div><p>© 2026 Motion X Studio · Grupo Empresarial Garga de México SAS</p></footer></body></html>