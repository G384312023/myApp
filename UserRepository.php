<?php
    class UserRepository {
        private $db;

        public function __construct(Database $db) {
            $this->db = $db;
        }

        public function findUserByEmail($email) {
            $sql = "SELECT * FROM users WHERE email = ?";
            return $this->db->selectOne($sql, [$email]);
        }

        public function createUser($username, $email, $hashPassword) {
            $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            return $this->db->execute($sql, [$username, $email, $hashPassword]);
        }
    }
?>