<?php
require_once 'Database.php';

try {
    $db = Database::getInstance();
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
    
    $db->execute($sql);
    echo "tasks table created successfully.";
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>