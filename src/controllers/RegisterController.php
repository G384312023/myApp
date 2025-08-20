<?php
    session_start();

    $db = Database::getInstance();
    $repo = new UserRepository($db);

    $errors = [];

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        if($username === '') {
            $errors [] = "ユーザネームが未入力です。";
        }

        if($email === '') {
            $errors [] = "メールアドレスが未入力です。";
        }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors [] = "メールアドレスの形式が正しくありません。";
        }else if($repo->findUserByEmail($email)) {
            $errors [] = "このメールアドレスはすでに登録されています。";
        }

        if($password === '') {
            $errors [] = "パスワードが未入力です。";
        }elseif(!preg_match('/^[\x21-\x7E]{8,12}$/', $password)) {
            $errors [] = "パスワードは8文字以上12文字以内で入力してください。";
        }

        if($password_confirm === '') {
            $errors [] = "確認用パスワードが未入力です。";
        }elseif($password !== $password_confirm) {
            $errors [] = "パスワードと確認用パスワードが一致しません。";
        }

        if(empty($errors)) {
            $hashPassword = password_hash($password, PASSWORD_DEFAULT);
            $userId = $repo->createUser($username, $email, $hashPassword);
            session_regenerate_id(true); // ←セッションID再生成(セキュリティ対策)
            $_SESSION['user'] = [
                'id' => $userId,
                'username' => $username
            ];
            header('location: index.php');
            exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー新規登録</title>
    <link rel = "stylesheet" href = "public/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">
</head>
<body class="auth-page">
    <div class="auth-container">
        <h1>ユーザー新規登録</h1>

        <?php if(!empty($errors)): ?>
            <ul>
                <?php foreach($errors as $error): ?>
                    <li><?= htmlspecialchars($error, ENT_QUOTES) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form action = "" method = "post">
            <input type = "text" name = "username" placeholder = "ユーザーネーム" value = "<?= htmlspecialchars($username ?? '')?>">
            <input type = "email" name = "email" placeholder = "メールアドレス" value = "<?= htmlspecialchars($email ?? '')?>">
            <input type = "password" name = "password" placeholder = "パスワード(8~12字)">
            <input type = "password" name = "password_confirm" placeholder = "パスワード(確認用)">
            <button type = "submit">新規登録</button>
        </form>
        <div class="auth-links">
            <a href="Login.php">すでにアカウントをお持ちの方はこちら</a>
        </div>
    </div>
</body>
</html>