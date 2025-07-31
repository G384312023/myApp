<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー新規登録</title>
</head>
<body>
    <h1>ユーザー新規登録</h1>

    <?php if(!empty($errors)): ?>
        <ul style = "color: red">
            <?php foreach($errors as $error): ?>
                <li><?= htmlspecialchars($error, ENT_QUOTES) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form action = "" method = "post">
        <input type = "text" name = "username" placeholder = "ユーザーネーム"><br>
        <input type = "email" name = "email" placeholder = "メールアドレス"><br>
        <input type = "password" name = "password" placeholder = "パスワード"><br>
        <input type = "password" name = "password_confirm" placeholder = "パスワード(確認用)"><br>
        <button type = submit>新規登録</button>
    </form>
</body>
</html>