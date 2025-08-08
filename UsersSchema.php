<?php
    $dbn = 'mysql:host=localhost;dbname=todo_app;charset=utf8mb4';
    $user = '238431';
    $password = 'ES9K#BZrfGrc';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    $sql = 'CREATE TABLE IF NOT EXISTS users'
            .'('
            .'id INT AUTO_INCREMENT PRIMARY KEY,'
            .'username VARCHAR(30) NOT NULL UNIQUE,'
            .'email VARCHAR(100) NOT NULL UNIQUE,'
            .'password VARCHAR(255) NOT NULL,'
            .'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP'
            .')';
    
    $pdo -> exec($sql);
    echo "テーブルが作成されました。";
?>