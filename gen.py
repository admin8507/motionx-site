# Genera las páginas estáticas con encabezado y pie comunes
import re,os
NAV='<a href="/juegos/">Juegos</a><a href="/servicios/">Servicios</a><a href="/estudio/">Estudio</a><a href="/contacto/">Contacto</a><a href="/login/">Entrar</a><a href="/registro/" class="btn sm">Ser tester</a>'
def head(t,desc='Motion X, estudio de videojuegos de Grupo Garga.'):
    return f'''<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>{t}</title><meta name="description" content="{desc}"><link rel="icon" href="/assets/logo.jpg"><link rel="stylesheet" href="/style.css"></head><body>
<header class="nav"><a href="/" class="brand"><img src="/assets/logo.jpg" alt="Motion X"><span>MOTION<b>X</b></span></a><button class="burger" aria-label="Menú" onclick="document.body.classList.toggle('menu')">☰</button><nav>{NAV}</nav></header>'''
FOOT='''<footer><div class="fcols"><div><b>MOTION<span class="c">X</span></b><br>Estudio de videojuegos de Grupo Garga</div><div><a href="/juegos/">Juegos</a><br><a href="/servicios/">Servicios</a><br><a href="/estudio/">Estudio</a></div><div><a href="/registro/">Programa de testers</a><br><a href="/login/">Acceso testers</a><br><a href="/pro/">Área Pro</a></div><div><a href="/contacto/">Contacto</a><br><a href="/terminos/">Términos</a><br><a href="/privacidad/">Aviso de privacidad</a></div></div><p>© 2026 Motion X Studio · Grupo Empresarial Garga de México SAS</p></footer></body></html>'''
NOTE='<p class="note">*El pago depende de cada juego, de completar las pruebas asignadas y de que tus reportes sean aprobados. Consulta los <a href="/terminos/">Términos del programa</a>.</p>'
GAMES=[('hiena','El juego de la Hiena','Acción · Mundo abierto 3D','hiena.jpg','http://108.181.203.225:10060/',
 'Recorre las calles del barrio en una aventura urbana en 3D llena de neón. Cumple misiones, habla con personajes con voz propia y gánate el respeto de la ciudad.',
 ['Mundo abierto urbano en 3D en tiempo real','Personajes con voz en español','Misiones de historia y secundarias','Ambientación nocturna con luces de neón'],
 'Navegador de escritorio (Chrome, Edge o Firefox) y celulares Android recientes.','Beta abierta para testers'),
('yamileth','¡Ya Compónte, Yamileth!','Narrativo · Comedia','yamileth.jpg','http://108.181.203.225:10056/',
 'Una historia interactiva con humor: acompaña a Yamileth en una noche por la ciudad y decide con tus elecciones cómo termina todo.',
 ['Historia ramificada con varios finales','Decisiones que cambian a los personajes','Humor y diálogos mexicanos','Partidas cortas, ideal para celular'],
 'Navegador de escritorio o celular.','Beta abierta para testers')]
def gamecard(g):
    k,t,tag,img,url,desc,*_=g
    return f'<article class="card"><a href="/juegos/{k}/"><img src="/assets/{img}" alt="{t}"></a><div class="pad"><span class="pill">{tag}</span><h3>{t}</h3><p>{desc}</p><div class="cta"><a class="btn" href="{url}" target="_blank" rel="noopener">Jugar</a><a class="btn ghost" href="/juegos/{k}/">Ver ficha</a></div></div></article>'
SERV=[('🎮','Desarrollo de videojuegos','Juegos 3D y 2D para web, Android e iOS, desde el prototipo hasta el lanzamiento, con builds de prueba en cada etapa.'),
('🧠','Diseño de juego y narrativa','Mecánicas, guion, personajes, progresión y balance, documentados en un GDD claro para todo el equipo.'),
('🎨','Arte, 3D y animación','Modelado, texturizado, rigging, ambientación, efectos visuales e interfaces con identidad propia.'),
('🔊','Voz y audio','Doblaje en español con voces naturales, música original y efectos de sonido.'),
('📱','Publicación y operación','Publicación en Google Play, App Store y web, con analítica, actualizaciones y monetización.'),
('🧪','QA y pruebas con jugadores','Nuestra comunidad de testers prueba tu juego en dispositivos reales y entrega reportes de bugs priorizados.'),
('🏫','Juegos serios y gamificación','Experiencias jugables para capacitación, educación, museos y campañas.')]
pages={}
idx=head('Motion X Studio | Creación de videojuegos')+f'''
<section class="hero tester"><div class="glow"></div><div class="hero-in">
<p class="tag">Programa de testers · Motion X</p>
<h1>Hazte tester de videojuegos <span class="grad">Motion X</span></h1>
<p class="big">Gana hasta <b class="money">$5,000 MXN</b> por cada juego que pruebes</p>
<div class="cta"><a href="/registro/" class="btn xl">Quiero ser tester</a><a href="/juegos/" class="btn ghost">Ver juegos</a></div>{NOTE}</div>
<img class="hero-logo" src="/assets/logo.jpg" alt="Logo Motion X"></section>
<section class="sec strip"><h2>¿Cómo funciona?</h2><div class="grid3">
<div class="svc"><i>📝</i><h3>1. Regístrate</h3><p>Crea tu cuenta de tester en un minuto. Solo mayores de 18.</p></div>
<div class="svc"><i>🎮</i><h3>2. Prueba juegos</h3><p>Juega las betas y completa las misiones de prueba en el Área Pro.</p></div>
<div class="svc"><i>💸</i><h3>3. Cobra</h3><p>Con tus reportes aprobados recibes tu pago según el juego.</p></div></div>
<p class="center" style="margin-top:26px"><a class="btn" href="/registro/">Crear mi cuenta</a> <a class="btn ghost" href="/login/">Ya tengo cuenta</a></p></section>
<section class="sec alt"><h2>Nuestros juegos</h2><div class="grid2">{''.join(gamecard(g) for g in GAMES)}</div></section>
<section class="sec"><h2>Servicios</h2><div class="grid3">{''.join(f'<div class="svc"><i>{i}</i><h3>{t}</h3><p>{d}</p></div>' for i,t,d in SERV[:3])}</div><p class="center" style="margin-top:26px"><a class="btn ghost" href="/servicios/">Ver todos los servicios</a></p></section>
<section class="sec alt split"><img src="/assets/calle.jpg" alt="Escenario Motion X"><div><h2>El estudio</h2><p>Motion X es la división de videojuegos de <b>Grupo Garga</b>. Combinamos arte, código e inteligencia artificial para contar historias jugables con sabor mexicano.</p><p style="margin-top:18px"><a class="btn ghost" href="/estudio/">Conoce el estudio</a> <a class="btn" href="/contacto/">Contáctanos</a></p></div></section>'''+FOOT
pages['index.html']=idx
pages['juegos/index.html']=head('Juegos | Motion X')+f'<section class="sec"><h2>Juegos</h2><p class="center">Prueba nuestras betas y cuéntanos qué encuentras. Los testers registrados reportan bugs desde el <a href="/pro/">Área Pro</a>.</p><div class="grid2" style="margin-top:30px">{"".join(gamecard(g) for g in GAMES)}</div></section>'+FOOT
for k,t,tag,img,url,desc,feats,plat,estado in GAMES:
    pages[f'juegos/{k}/index.html']=head(f'{t} | Motion X',desc)+f'''<section class="sec split"><img src="/assets/{img}" alt="{t}"><div><span class="pill">{tag}</span><h2 style="text-align:left">{t}</h2><p>{desc}</p>
<h3 class="c" style="margin-top:20px">Características</h3><ul class="list">{"".join(f"<li>{f}</li>" for f in feats)}</ul>
<p><b>Plataformas:</b> {plat}<br><b>Estado:</b> {estado}<br><b>Estudio:</b> Motion X · Grupo Garga</p>
<div class="cta"><a class="btn xl" href="{url}" target="_blank" rel="noopener">Jugar</a><a class="btn ghost" href="/registro/">Hazte tester</a></div>
<p class="note">¿Encontraste un bug? Repórtalo desde el <a href="/pro/">Área Pro</a>. <a href="/juegos/">← Todos los juegos</a></p></div></section>'''+FOOT
pages['servicios/index.html']=head('Servicios | Motion X')+'<section class="sec"><h2>Servicios</h2><p class="center">Creamos tu videojuego de principio a fin o nos sumamos a tu equipo en la etapa que necesites.</p><div class="grid3" style="margin-top:30px">'+''.join(f'<div class="svc"><i>{i}</i><h3>{t}</h3><p>{d}</p></div>' for i,t,d in SERV)+'''</div></section>
<section class="sec alt"><h2>Cómo trabajamos</h2><div class="grid3"><div class="svc"><h3>1. Idea y propuesta</h3><p>Escuchamos tu idea y te entregamos alcance, tiempos y cotización.</p></div><div class="svc"><h3>2. Prototipo</h3><p>Un prototipo jugable para validar la diversión antes de invertir más.</p></div><div class="svc"><h3>3. Producción</h3><p>Entregas cada dos semanas con builds que puedes probar.</p></div><div class="svc"><h3>4. Pruebas</h3><p>Nuestra comunidad de testers revisa el juego en dispositivos reales.</p></div><div class="svc"><h3>5. Lanzamiento</h3><p>Publicamos en tiendas y web, con analítica desde el primer día.</p></div><div class="svc"><h3>6. Operación</h3><p>Actualizaciones, contenido nuevo y soporte.</p></div></div>
<p class="center" style="margin-top:26px"><a class="btn xl" href="/contacto/">Cotiza tu proyecto</a></p></section>'''+FOOT
pages['estudio/index.html']=head('El estudio | Motion X')+'''<section class="sec split"><img src="/assets/calle.jpg" alt="Motion X"><div><h2 style="text-align:left">El estudio</h2><p>Motion X es la división de videojuegos de <b>Grupo Empresarial Garga de México SAS</b>. Somos un equipo creativo en México que combina arte, programación e inteligencia artificial para crear juegos con identidad local y calidad internacional.</p>
<ul class="stats"><li><b>3D</b>Motores en tiempo real</li><li><b>IA</b>Voces y personajes</li><li><b>MX</b>Hecho en México</li></ul></div></section>
<section class="sec alt"><h2>Lo que nos mueve</h2><div class="grid3"><div class="svc"><h3>Misión</h3><p>Crear videojuegos que diviertan y cuenten historias nuestras, con tecnología de punta.</p></div><div class="svc"><h3>Visión</h3><p>Ser el estudio mexicano referente en juegos web y móviles con narrativa propia.</p></div><div class="svc"><h3>Comunidad</h3><p>Los jugadores prueban, opinan y mejoran cada juego a través de nuestro programa de testers.</p></div></div>
<p class="center" style="margin-top:26px"><a class="btn" href="/juegos/">Ver juegos</a> <a class="btn ghost" href="/contacto/">Trabaja con nosotros</a></p></section>'''+FOOT
pages['contacto/index.html']=head('Contacto | Motion X')+'''<section class="sec"><div class="box"><h2>Contacto</h2><p class="center">¿Tienes un proyecto de videojuego o una duda? Escríbenos.</p>
<form id="cf" class="form"><input name="nombre" placeholder="Nombre" required maxlength="120"><input name="correo" type="email" placeholder="Correo" required maxlength="160">
<select name="asunto" required><option value="">Asunto</option><option>Desarrollo de videojuego</option><option>Programa de testers</option><option>Prensa</option><option>Alianzas</option><option>Otro</option></select>
<textarea name="mensaje" rows="6" placeholder="Tu mensaje" required minlength="10" maxlength="4000"></textarea><input type="text" name="website" class="hp" tabindex="-1" autocomplete="off">
<button class="btn">Enviar mensaje</button><p id="cmsg" class="center"></p></form>
<p class="note">Al enviar aceptas el <a href="/privacidad/">Aviso de privacidad</a>.</p></div></section>
<script>document.getElementById('cf').addEventListener('submit',async e=>{e.preventDefault();const m=document.getElementById('cmsg');m.textContent='Enviando…';try{const j=await (await fetch('/api/contacto.php',{method:'POST',body:new FormData(e.target)})).json();if(j.ok){e.target.reset();m.textContent='¡Gracias! Recibimos tu mensaje y te responderemos pronto.'}else m.textContent=j.error}catch(x){m.textContent='Error de conexión'}})</script>'''+FOOT
pages['login/index.html']=head('Acceso testers | Motion X')+'''<section class="sec"><div class="box"><h2>Acceso testers</h2>
<form id="lf" class="form"><input name="correo" type="email" placeholder="Correo" required><input name="password" type="password" placeholder="Contraseña" required><button class="btn xl">Entrar</button><p id="lmsg" class="center"></p></form>
<p class="center small">¿Aún no tienes cuenta? <a class="c" href="/registro/">Regístrate como tester</a></p></div></section>
<script>document.getElementById('lf').addEventListener('submit',async e=>{e.preventDefault();const m=document.getElementById('lmsg');m.textContent='Entrando…';try{const j=await (await fetch('/api/login.php',{method:'POST',body:new FormData(e.target),credentials:'same-origin'})).json();if(j.ok)location.href='/pro/';else m.textContent=j.error}catch(x){m.textContent='Error de conexión'}})</script>'''+FOOT
# registro: reutiliza el cuerpo existente con nuevo head/foot y campo contraseña
r=open('registro/index.html').read()
body=r[r.index('<section'):r.index('<footer>')]
body=body.replace('<input name="edad"','<input name="password" type="password" placeholder="Crea una contraseña (mín. 8 caracteres)" required minlength="8" maxlength="72" autocomplete="new-password">\n<input name="edad"',1) if 'name="password"' not in body else body
body=body.replace('<p class="center">Te contactaremos por correo o WhatsApp cuando haya un juego para ti.</p>','<p class="center">Tu cuenta está lista. Entra al <a class="c" href="/pro/">Área Pro</a>: se desbloquea completa cuando 2 personas se registren con tu link.</p><p class="center" style="margin-top:12px"><a class="btn" href="/pro/">Ir al Área Pro</a></p>')
body=body.replace("<p class=\"center\">Gana hasta $5,000 MXN por cada juego que pruebes.*</p>","<p class=\"center\">Gana hasta $5,000 MXN por cada juego que pruebes.*</p><p class=\"center small\">¿Ya tienes cuenta? <a class=\"c\" href=\"/login/\">Inicia sesión</a></p>")
pages['registro/index.html']=head('Registro de testers | Motion X')+body+FOOT
for p in ['terminos/index.html','privacidad/index.html']:
    r=open(p).read(); t=re.search('<title>(.*?)</title>',r).group(1)
    pages[p]=head(t)+r[r.index('<section'):r.index('<footer>')]+FOOT
for p,c in pages.items():
    os.makedirs(os.path.dirname(p) or '.',exist_ok=True); open(p,'w').write(c)
print(len(pages))
