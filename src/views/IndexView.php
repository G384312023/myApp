<?php
// このファイルは IndexController.php から読み込まれます。
// $error, $title, $description, $sortOrder, $incompleteTasks, $completedTasks などの変数は、
// IndexController.php で定義されていることを前提としています。
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Todo</title>
  <link rel = "stylesheet" href = "public/style.css">
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
      <textarea name = "description" placeholder = "タスク詳細（Markdown記法対応）&#10;&#10;改行方法:&#10;- 普通に改行すると改行されます&#10;- 空行で段落分けできます&#10;&#10;その他の記法:&#10;# 見出し1 ## 見出し2 ### 見出し3&#10;**太字** *斜体*&#10;- リスト項目&#10;[リンクテキスト](URL)&#10;`コード`"><?= htmlspecialchars($description ?? '') ?></textarea><br>
      <button type = "submit">新規作成</button>
    </form>

    <hr>

    <div class="sort-options">
      <form action="index.php" method="GET" name="sortForm">
          <label for="sort-select">並べ替え:</label>
          <select name="sort" id="sort-select">
              <option value="desc" <?= ($sortOrder === 'DESC') ? 'selected' : '' ?>>新しい順</option>
              <option value="asc" <?= ($sortOrder === 'ASC') ? 'selected' : '' ?>>古い順</option>
          </select>
      </form>
    </div>

    <div id="task-list-wrapper">
      <?php include __DIR__ . '/RenderTasks.php'; ?>
    </div>

    <script src="public/script.js?v=<?= time() ?>"></script>
  </main>
</body>
</html>
