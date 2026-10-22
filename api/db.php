<?php
// Credenciales fuera del docroot: /home2/irvinjos/motionx_config.php (no versionado)
$cfg = require dirname(__DIR__, 3) . '/motionx_config.php';
function db(){ global $cfg; static $p; if(!$p){
  $p = new PDO("mysql:host={$cfg['db_host']};dbname={$cfg['db_name']};charset=utf8mb4",$cfg['db_user'],$cfg['db_pass'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
  $p->exec("CREATE TABLE IF NOT EXISTS testers(
    id INT AUTO_INCREMENT PRIMARY KEY, nombre VARCHAR(120) NOT NULL, correo VARCHAR(160) NOT NULL UNIQUE,
    whatsapp VARCHAR(20) NOT NULL, edad TINYINT UNSIGNED NOT NULL, dispositivo VARCHAR(40) NOT NULL, generos VARCHAR(400),
    codigo CHAR(8) NOT NULL UNIQUE, ref_por CHAR(8) NULL, recomendados INT NOT NULL DEFAULT 0,
    acepto_privacidad TINYINT(1) NOT NULL, ip VARCHAR(45), creado DATETIME DEFAULT CURRENT_TIMESTAMP, INDEX(ref_por)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
 } return $p; }
