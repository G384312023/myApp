<?php

// セッション開始と認証チェック
session_start();
if (!isset($_SESSION['user'])) {
    header('location: Login.php');
    exit;
}



// データベースとリポジトリのインスタンス化
$db = Database::getInstance();
$repo = new TaskRepository($db);

$error = '';
$userId = $_SESSION['user']['id'];

// 新規タスク作成処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
    $title = trim($_POST['title'] ?? '');
    $description = $_POST['description'] ?? '';

    if ($title === '') {
        $error = "タスク名が未入力です。";
    } else {
        $repo->createTask($userId, $title, $description);
        header('location: index.php');
        exit;
    }
}

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

// ビューを読み込んで表示
require 'src/views/IndexView.php';
