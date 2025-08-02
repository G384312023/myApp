<?php
  session_start();

  if(!isset($_SESSION['user'])) {
    header('location: Login.php');
    exit;
  }
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

    <form action = "" method = "post">
      <input type = "text" name = "title" placeholder = "タスク名"><br>
      <input type = "text" name = "description" placeholder = "タスク詳細"><br>
      <button type = "submit">新規作成</button>
    </form>
  </main>
</body>
</html>