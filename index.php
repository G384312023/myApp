<?php
  session_start();

  if(!isset($_SESSION['user'])) {
    header('location: Login.php');
    exit;
  }

  require_once 'Database.php';
  require_once 'TaskRepository.php';
  require_once 'DateFormatter.php';

  $db = Database::getInstance();
  $repo = new TaskRepository($db);

  $error = '';
  $userId = $_SESSION['user']['id'];

  // 新規タスク作成処理
  if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
    $title = trim($_POST['title'] ?? '');
    $description = $_POST['description'] ?? '';

    if($title === '') {
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

  $tasks = $repo->getTasksByUserId($userId, $sortOrder);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Todo</title>
  <link rel = "stylesheet" href = "style.css">
  <!-- Markdown parser library -->
  <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
  <script>
    // marked.jsの読み込み確認
    window.addEventListener('load', function() {
      console.log('marked library loaded:', typeof marked !== 'undefined');
      if (typeof marked !== 'undefined') {
        console.log('marked version:', marked.version || 'version unknown');
      }
    });
  </script>
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
      <textarea name = "description" placeholder = "タスク詳細（Markdown記法対応）&#10;# 見出し1 ## 見出し2 ### 見出し3&#10;**太字** *斜体*&#10;- リスト項目&#10;[リンクテキスト](URL)&#10;`コード`"><?= htmlspecialchars($description ?? '') ?></textarea><br>
      <button type = "submit">新規作成</button>
    </form>

    <hr>

    <?php if(!empty($tasks)): ?>
      <div class="sort-options">
        <form action="index.php" method="GET" name="sortForm">
            <label for="sort-select">並べ替え:</label>
            <select name="sort" id="sort-select" onchange="this.form.submit()">
                <option value="desc" <?= ($sortOrder === 'DESC') ? 'selected' : '' ?>>新しい順</option>
                <option value="asc" <?= ($sortOrder === 'ASC') ? 'selected' : '' ?>>古い順</option>
            </select>
        </form>
      </div>
      <div id="task-list-container"> 
        <?php foreach($tasks as $task): ?>
          <?php $taskId = $task['id']; ?>
          <div class="task-item <?= $task['is_done'] ? 'completed' : '' ?>" data-task-id="<?= $taskId ?>">
            <div class="task-header">
              <input type="checkbox" class="task-checkbox" <?= $task['is_done'] ? 'checked' : '' ?>>
              <h3 class="task-title"><?= htmlspecialchars($task['title'])?></h3>
              <span class="task-date">
                <?php
                    $createdTs = strtotime($task['created_at']);
                    $updatedTs = !empty($task['updated_at']) ? strtotime($task['updated_at']) : 0;
                    $isUpdated = ($updatedTs && ($updatedTs - $createdTs > 1));
                    $displayDateString = $isUpdated ? $task['updated_at'] : $task['created_at'];
                    $label = $isUpdated ? '(更新)' : '(作成)';
                    $formattedDate = DateFormatter::format($displayDateString);
                    echo htmlspecialchars($formattedDate . ' ' . $label);
                ?>
              </span>
            </div>
            <div class="task-description markdown-content" data-markdown="<?= htmlspecialchars($task['description'])?>">
              <noscript><?= htmlspecialchars($task['description'])?></noscript>
              <div class="markdown-fallback"><?= htmlspecialchars($task['description'])?></div>
            </div>
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

    <script src="script.js?v=<?= time() ?>"></script>
  </main>
</body>
</html>