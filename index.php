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
  <h2><?php echo htmlspecialchars($_SESSION['user']['username']); ?>さん！</h2>
  <p><a href = "Logout.php">ログアウト</a></p>
</body>
</html>