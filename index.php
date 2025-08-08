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
  <link rel = "stylesheet" href = "style.css">
</head>
<body>
  <header>
    <h1>ToDoリスト</h1>
    <p><a href = "Logout.php">ログアウト</a></p>
    <hr>
  </header>

  <main>
    <h2><?php echo htmlspecialchars($_SESSION['user']['username']); ?>さん、こんにちは！</h2>

    <?php if(!empty($error)): ?>
      <p style = "color: red"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form action = "" method = "post">
      <input type = "text" name = "title" placeholder = "タスク名" value = "<?= htmlspecialchars($title ?? '')?>"><br>
      <textarea name = "description" placeholder = "タスク詳細" value = "<?= htmlspecialchars($description ?? '')?>"></textarea><br>
      <button type = "submit">新規作成</button>
    </form>

    <?php if(!empty($tasks)): ?>
      <div id="task-list-container"> 
        <?php foreach($tasks as $task): ?>
          <?php $taskId = $task['id']; ?>
          <div class="task-item <?= $task['is_done'] ? 'completed' : '' ?>" data-task-id="<?= $taskId ?>">
            <div class="task-header">
              <input type="checkbox" class="task-checkbox" <?= $task['is_done'] ? 'checked' : '' ?>>
              <h3 class="task-title"><?= htmlspecialchars($task['title'])?></h3>
              <span class="task-date"><?= htmlspecialchars($task['created_at'])?></span>
            </div>
            <p class="task-description"><?= htmlspecialchars($task['description'])?></p>
            <div class="task-actions">
              <span class="status-text"><?= $task['is_done'] ? '完了' : '未完了'?></span>
              <a href="Edit.php?id=<?= $taskId?>" class="button edit-button">編集</a>
              <a href="Delete.php?id=<?= $taskId?>" class="button delete-button" onclick="return confirm('本当に削除しますか？');">削除</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p>まだタスクがありません。新しいタスクを作成しましょう！</p>
    <?php endif; ?>

    <script src="script.js"></script>
  </main>
</body>
</html>