<?php
    require_once 'Database.php';
    require_once 'UserRepository.php';

    session_start();

    $db = Database::getInstance();
    $repo = new UserRepository($db);

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if($email === '' || $password === '') {
            echo "メールアドレスまたはパスワードが未入力です。";
            exit;
        }
        
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "メールアドレスの形式が正しくありません。";
            exit;
        }

        $user = $repo->findUserByEmail($email);

        if(password_verify($password, $user['password'])) {
            //ログイン成功
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            echo "ログイン成功";
            header('Location: index.php');
        }else {
            //ログイン失敗
            echo "パスワードが間違っています。";
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
    <form action = '' method = 'post'>
        <input type = 'email' name = 'email' placeholder = 'メールアドレス' value = "<?= htmlspecialchars($email ?? '') ?>"><br>
        <input type = 'password' name = 'password' placeholder = 'パスワード'><br>
        <button type = 'submit'>ログイン</button>
    </form>
</body>
</html>