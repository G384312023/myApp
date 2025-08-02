<?php
    $dsn = 'mysql:dbname=test_db;host=localhost;port=8889';
    $user = 'root';
    $password = 'root';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    $sql = 'CREATE TABLE IF NOT EXISTS tasks'
            .'('
            .'id INT AUTO_INCREMENT PRIMARY KEY,'
            .'user_id INT NOT NULL,'
            .'title VARCHAR(255) NOT NULL,'
            .'description TEXT NOT NULL,'
            .'is_done BOOLEAN DEFAULT FALSE,'
            .'created_at DATETIME DEFAULT CURRENT_TIMESTAMP,'
            .'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,'
            .'FOREIGN KEY (user_id) REFERENCES users(id)'
            .')';
    
    $pdo -> exec($sql);
    echo "tasksが作成されました。";
?>