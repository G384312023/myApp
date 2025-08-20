<?php

// セッション開始と認証チェック
session_start();
if (!isset($_SESSION['user'])) {
    header('location: Login.php');
    exit;
}

// タスクIDの確認
if (!isset($_GET['id'])) {
    header('location: index.php');
    exit;
}

$taskId = (int) $_GET['id'];
$userId = (int) $_SESSION['user']['id']; // 文字列を整数に変換

// データベースとリポジトリのインスタンス化
$db = Database::getInstance();
$repo = new TaskRepository($db);

// タスクの存在確認と権限チェック
$task = $repo->findTaskByTaskId($taskId);
if ($task && $task['user_id'] === $userId) {
    // タスクを削除
    $repo->deleteTask($taskId);
}

// メインページにリダイレクト
header('location: index.php');
exit;
