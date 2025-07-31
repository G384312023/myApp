<?php
    require_once 'UserRepository.php';
    require_once 'Database.php';

    $db = Database::getInstance();
    $repo = new UserRepository($db);

    $errors = [];
    $successMessage = '';

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        //バリデーション
        if($username === '') {
            $errors[] = 'ユーザネームを入力してください。';
        }

        if($email === '') {
            $errors[] = 'メールアドレスを入力してください。';
        }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'メールアドレスの形式が正しくありません。';
        }

        if($password === '') {
            $errors[] = 'パスワードを入力してください。';
        }

        if(empty($errors)) {
            $hashPassword = password_hash($password, PASSWORD_DEFAULT);
            $result = $repo->createUser($username, $email, $hashPassword);
            if($result) {
                $successMessage = 'ユーザを登録しました。';
            }else {
                $errors[] = '登録失敗しました。';
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザ登録テスト</title>
</head>
<body>
    <h1>ユーザ登録フォーム</h1>

    <?php if(!empty($errors)): ?>
        <ul style = 'color:red;'>
            <?php foreach($errors as $error): ?>
                <li><?= htmlspecialchars($error, ENT_QUOTES) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if($successMessage): ?>
        <p style="color:green;"><?= htmlspecialchars($successMessage, ENT_QUOTES) ?></p>
    <?php endif; ?>

    <form action = '' method = 'post'>
        <label>ユーザ名: <input type = "text" name = "username" value = "<?= htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES)?>"></label><br>
        <label>メールアドレス: <input type = "email" name = "email" value = "<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES)?>"></label><br>
        <label>パスワード: <input type = "password" name = "password"></label><br>
        <button type = "submit">登録</button>
    </form>
</body>
</html>