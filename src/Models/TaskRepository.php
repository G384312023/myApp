<?php
    class TaskRepository {
        private $db;

        public function __construct(Database $db) {
            $this->db = $db;
        }

        public function getTasksByUserId($userId, string $sortOrder = 'DESC') {
            // SQLインジェクションを防ぐため、ソート順の値をホワイトリストで検証
            $order = strtoupper($sortOrder) === 'ASC' ? 'ASC' : 'DESC';

            $sql = "SELECT * FROM tasks WHERE user_id = ? ORDER BY updated_at " . $order;
            return $this->db->select($sql, [$userId]);
        } 

        public function findTaskByTaskId($taskId) {
            $sql = "SELECT * FROM tasks WHERE id = ?";
            return $this->db->selectOne($sql, [$taskId]);
        }

        public function createTask($userId, $title, $description) {
            $sql = "INSERT INTO tasks (user_id, title, description) VALUES (?, ?, ?)";
            return $this->db->execute($sql, [$userId, $title, $description]);
        }

        public function updateTask($title, $description, $taskId) {
            $sql = "UPDATE tasks SET title = ?, description = ? WHERE id = ?";
            return $this->db->execute($sql, [$title, $description, $taskId]);
        }

        public function updateTaskStatus($taskId, $isDone) {
            $sql = 'UPDATE tasks SET is_done = ? WHERE id = ?';
            return $this->db->execute($sql, [$isDone, $taskId]);
        }

        public function deleteTask($taskId) {
            $sql = "DELETE FROM tasks WHERE id = ?";
            return $this->db->execute($sql, [$taskId]);
        }
    }
?>