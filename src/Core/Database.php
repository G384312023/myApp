<?php
    class Database {
        private $pdo;
        private static $instance;

        private function __construct() {
            // .envファイルを読み込む
            $envFile = __DIR__ . '/../../.env';
            if (file_exists($envFile)) {
                $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    if (strpos(trim($line), '#') === 0) continue;
                    list($key, $val) = explode('=', $line, 2);
                    $_ENV[trim($key)] = trim($val);
                }
            }

            // AWS環境では環境変数から取得、ローカルではデフォルト値を使用
            $host = $_ENV['RDS_HOSTNAME'] ?? 'localhost';
            $dbname = $_ENV['RDS_DB_NAME'] ?? 'todo_app';
            $username = $_ENV['RDS_USERNAME'] ?? '238431';
            $password = $_ENV['RDS_PASSWORD'] ?? 'ES9K#BZrfGrc';
            
            $dbn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";

            try {
                $this->pdo = new PDO($dbn, $username, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
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