<?php require __DIR__.'/db.php'; sess(); $_SESSION=[]; session_destroy(); header('Location: /login/'); exit;
