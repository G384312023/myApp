<?php
    session_start();

    require_once 'Database.php';
    require_once 'TaskRepository.php';

    if(!isset($_SESSION['user'])) {
        header('location: Login.php');
        exit;
    }

    $userId = $_SESSION['user']['id'];

    if(!isset($_GET['id'])) {
        header('location: index.php');
        exit;
    } 

    $taskId = (int) $_GET['id'];

    $db = Database::getInstance();
    $repo = new TaskRepository($db);

    $task = $repo->findTaskByTaskId($taskId);
    
    if (!$task || $task['user_id'] !== $userId) {
        header('Location: index.php');
        exit;
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = trim($_POST['title'] ?? '');
        $description = $_POST['description'];

        if($title !== '') {
            $repo->updateTask($title, $description, $taskId);
            header('location: index.php');
            exit;
        }

        if(empty($error) && ($task && $task['user_id'] === $userId)) {
            $repo->updateTask($title, $description, $isDone, $taskId);
            header('location: index.php');
            exit;
        }

        $error = "タスク名が未入力です。";
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>
    <link rel = "stylesheet" href = "style.css">
</head>
<body>
    <head>
        <h2>編集中...</h2>
        <hr>
    </head>
    <main>
        <?php if(!empty($error)): ?>
            <p style = "color: red"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form action = "" method = "post">
        <input type = "text" name = "title" placeholder = "タスク名" value = "<?= htmlspecialchars($title ?? '')?>"><br>
        <textarea name = "description" placeholder = "タスク詳細" value = "<?= htmlspecialchars($description ?? '')?>"></textarea><br>
        <button type = "submit">編集</button>
        </form>
    </main>
    <footer>
        <hr>
        <a href = "index.php">戻る</a>
    </footer>
</body>
</html>