<?php
require_once 'Database.php';

try {
    $db = Database::getInstance();
    $sql = 'CREATE TABLE IF NOT EXISTS users'
            .'('
            .'id INT AUTO_INCREMENT PRIMARY KEY,'
            .'username VARCHAR(30) NOT NULL UNIQUE,'
            .'email VARCHAR(100) NOT NULL UNIQUE,'
            .'password VARCHAR(255) NOT NULL,'
            .'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP'
            .')';
    
    $db->execute($sql);
    echo "users table created successfully.";
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>