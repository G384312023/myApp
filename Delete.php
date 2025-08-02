<?php
    session_start();

    if(!isset($_SESSION['user'])) {
        header('location: Login.php');
        exit;
    }

    if(!isset($_GET['id'])) {
        header('location: index.php');
        exit;
    }

    require_once 'Database.php';
    require_once 'TaskRepository.php';

    $db = Database::getInstance();
    $repo = new TaskRepository($db);

    $taskId = (int)$_GET['id'];
    $userId = $_SESSION['user']['id'];

    $task = $repo->findTaskByTaskId($taskId);

    if($task && $task['user_id'] === $userId) {
        $repo->deleteTask($taskId);
    }

    header('location: index.php');
    exit;
?>