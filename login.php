<?php
    require_once 'Database.php';
    require_once 'UserRepository.php';

    session_start();

    $db = Database::getInstance();
    $repo = new UserRepository($db);

    $error = '';
    $email = '';

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if($email === '' || $password === '') {
            $error =  "メールアドレスまたはパスワードが未入力です。";
        }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "メールアドレスの形式が正しくありません。";
        }else {
            $user = $repo->findUserByEmail($email);

            if(!$user || !password_verify($password, $user['password'])) {
                $error = "メールアドレスまたは、パスワードが間違っています。";
            }else {
                //ログイン成功
                session_regenerate_id(true); // ←セッションID再生成(セキュリティ対策)
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header('Location: index.php');
                exit;
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
</head>
<body>
    <?php if($error): ?>
        <p style = "color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form action = "" method = "post">
        <input type = "email" name = "email" placeholder = "メールアドレス" value = "<?= htmlspecialchars($email ?? '') ?>"><br>
        <input type = "password" name = "password" placeholder = "パスワード"><br>
        <button type = "submit">ログイン</button>
    </form>
</body>
</html>