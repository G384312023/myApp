<?php
session_start();

// オートローダーを読み込む
require_once __DIR__ . '/../../Autoload.php';

if (!isset($_SESSION['user'])) {
    header('Content-Type: application/json');
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'User not authenticated']);
    exit;
}

// データベースとリポジトリのインスタンス化 (オートローダーがクラスを解決)
$db = Database::getInstance();
$repo = new TaskRepository($db);

$userId = $_SESSION['user']['id'];

// 並べ替え順序の決定
$sortOrderInput = $_GET['sort'] ?? 'desc';
$sortOrder = strtoupper($sortOrderInput) === 'ASC' ? 'ASC' : 'DESC';

// タスクの取得
$tasks = $repo->getTasksByUserId($userId, $sortOrder);

// 未完了タスクと完了済みタスクに振り分ける
$incompleteTasks = [];
$completedTasks = [];
foreach ($tasks as $task) {
    if ($task['is_done']) {
        $completedTasks[] = $task;
    } else {
        $incompleteTasks[] = $task;
    }
}

// 部分ビュー（タスクリスト）を読み込んでHTMLを生成・出力
require __DIR__ . '/../views/RenderTasks.php';
