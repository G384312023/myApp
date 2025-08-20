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

        public function findUserById($id) {
            $sql = "SELECT * FROM users WHERE id = ?";
            return $this->db->selectOne($sql, [$id]);
        }

        public function createUser($username, $email, $hashPassword) {
            $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $this->db->execute($sql, [$username, $email, $hashPassword]);
            return $this->db->lastInsertId();
        }
    }
?>