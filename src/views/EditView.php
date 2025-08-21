<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>
    <link rel="stylesheet" href="public/style.css">
</head>
<body>
    <header>
        <h2>編集中...</h2>
        <hr>
    </header>
    <main>
        <?php if (!empty($error)): ?>
            <p style="color: red"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form action="" method="post">
            <input type="text" name="title" placeholder="タスク名" value="<?= htmlspecialchars($_POST['title'] ?? $task['title'] ?? '') ?>"><br>
            <textarea name="description" placeholder="タスク詳細（Markdown記法対応）&#10;&#10;改行方法:&#10;- 普通に改行すると改行されます&#10;- 空行で段落分けできます&#10;&#10;その他の記法:&#10;# 見出し1 ## 見出し2 ### 見出し3&#10;**太字** *斜体*&#10;- リスト項目&#10;[リンクテキスト](URL)&#10;`コード`"><?= htmlspecialchars($_POST['description'] ?? $task['description'] ?? '') ?></textarea><br>
            <button type="submit">編集</button>
        </form>
    </main>
    <footer>
        <hr>
        <a href="index.php" class="back-button">戻る</a>
    </footer>
</body>
</html>
