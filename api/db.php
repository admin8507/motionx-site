<?php
// Credenciales fuera del docroot: /home2/irvinjos/motionx_config.php (no versionado)
$cfg = require dirname(__DIR__, 3) . '/motionx_config.php';
function db(){ global $cfg; static $p; if(!$p){
  $p = new PDO("mysql:host={$cfg['db_host']};dbname={$cfg['db_name']};charset=utf8mb4",$cfg['db_user'],$cfg['db_pass'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
  $p->exec("CREATE TABLE IF NOT EXISTS testers(
    id INT AUTO_INCREMENT PRIMARY KEY, nombre VARCHAR(120) NOT NULL, correo VARCHAR(160) NOT NULL UNIQUE,
    whatsapp VARCHAR(20) NOT NULL, edad TINYINT UNSIGNED NOT NULL, dispositivo VARCHAR(40) NOT NULL, generos VARCHAR(400),
    codigo CHAR(8) NOT NULL UNIQUE, ref_por CHAR(8) NULL, recomendados INT NOT NULL DEFAULT 0,
    acepto_privacidad TINYINT(1) NOT NULL, ip VARCHAR(45), creado DATETIME DEFAULT CURRENT_TIMESTAMP, INDEX(ref_por)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
  $c=$p->query("SHOW COLUMNS FROM testers LIKE 'pass_hash'")->fetch(); if(!$c) $p->exec("ALTER TABLE testers ADD pass_hash VARCHAR(255) NULL");
  $p->exec("CREATE TABLE IF NOT EXISTS contactos(id INT AUTO_INCREMENT PRIMARY KEY, nombre VARCHAR(120), correo VARCHAR(160), asunto VARCHAR(80), mensaje TEXT, ip VARCHAR(45), creado DATETIME DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
  $p->exec("CREATE TABLE IF NOT EXISTS reportes(id INT AUTO_INCREMENT PRIMARY KEY, tester_id INT NOT NULL, juego VARCHAR(60), mision VARCHAR(120), severidad VARCHAR(20), dispositivo VARCHAR(60), descripcion TEXT, pasos TEXT, estado VARCHAR(20) NOT NULL DEFAULT 'pendiente', creado DATETIME DEFAULT CURRENT_TIMESTAMP, INDEX(tester_id)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
 } return $p; }
function out($a,$c=200){ http_response_code($c); header('Content-Type: application/json; charset=utf-8'); echo json_encode($a,JSON_UNESCAPED_UNICODE); exit; }
function sess(){ if(session_status()!==PHP_SESSION_ACTIVE){ ini_set('session.cookie_httponly',1); ini_set('session.cookie_secure',1); ini_set('session.use_strict_mode',1); session_start(); } }
function h($s){return htmlspecialchars((string)$s,ENT_QUOTES,'UTF-8');}
