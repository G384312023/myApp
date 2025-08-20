<?php

// アプリケーションの心臓部であるオートローダーを最初に読み込む
require_once __DIR__ . '/Autoload.php';

// タスクステータス更新処理を行うコントローラーを呼び出す
require __DIR__ . '/src/controllers/UpdateTaskStatusController.php';
