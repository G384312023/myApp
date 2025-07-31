<?php
    class Database {
        private $pdo;
        private static $instance;

        private function __construct() {
            $dbn = 'mysql:dbname=test_db;host=localhost;port=8889';
            $user = 'root';
            $password = 'root';

            try {
                $this->pdo = new PDO($dbn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
            } catch(PDOException $e) {
                die('DB接続エラー : ' . $e->getMessage());
            }
        }

        public static function getInstance() {
            if(!self::$instance) {
                self::$instance = new Database();
            }
            return self::$instance;
        }

        public function getPdo() {
            return $this->pdo;
        }

        public function select($sql, $params = []) {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        }

        public function selectOne($sql, $params = []) {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch();
        }

        public function execute($sql, $params = []) {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        }

        public function lastInsertId() {
            return $this->pdo->lastInsertId();
        }
    }
?>