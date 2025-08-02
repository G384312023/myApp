<?php
  session_start();

  if(!isset($_SESSION['user'])) {
    header('location: Login.php');
    exit;
  }

  require_once 'Database.php';
  require_once 'TaskRepository.php';

  $db = Database::getInstance();
  $repo = new TaskRepository($db);

  $error = '';
  $userId = $_SESSION['user']['id'];

  if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = $_POST['description'];

    if($title === '') {
      $error = "タスク名が未入力です。";
    }

    if(empty($error)) {
      $repo->createTask($userId, $title, $description);
      header('location: index.php');
      exit;
    }
  }

  $tasks = $repo->getTasksByUserId($userId);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Todo</title>
</head>
<body>
  <header>
    <p><a href = "Logout.php">ログアウト</a></p>
    <hr>
  </header>

  <main>
    <h2><?php echo htmlspecialchars($_SESSION['user']['username']); ?>さん！ こんにちは！</h2>

    <?php if(!empty($error)): ?>
      <p style = "color: red"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form action = "" method = "post">
      <input type = "text" name = "title" placeholder = "タスク名" value = "<?= htmlspecialchars($title ?? '')?>"><br>
      <input type = "text" name = "description" placeholder = "タスク詳細" value = "<?= htmlspecialchars($description ?? '')?>"><br>
      <button type = "submit">新規作成</button>
    </form>

    <?php if(!empty($tasks)): ?>
      <table>
        <tr>
          <th>タスク</th>
          <th>詳細</th>
          <th>完了状態</th>
          <th>登録日</th>
        </tr>
        <?php foreach($tasks as $task): ?>
          <tr>
            <?php $taskId = $task['id']; ?>
            <td><?= htmlspecialchars($task['title'])?></td>
            <td><?= htmlspecialchars($task['description'])?></td>
            <td><?= $task['is_done'] ? '完了' : '未完了'?></td>
            <td><?= htmlspecialchars($task['created_at'])?></td>
            <td><a href = "Edit.php?id=<?= $taskId?>">編集</a></td>
            <td><a href = "Delete.php?id=<?= $taskId?>" onclick="return confirm('本当に削除しますか？');">削除</a></td>
          </tr>
        <?php endforeach; ?>
        </table>
    <?php endif; ?>
  </main>
</body>
</html>