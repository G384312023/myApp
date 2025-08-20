<?php

// アプリケーションの心臓部であるオートローダーを最初に読み込む
require_once __DIR__ . '/Autoload.php';

// リクエストを処理するコントローラーを呼び出す
require __DIR__ . '/src/controllers/IndexController.php';
