<?php

spl_autoload_register(function ($className) {
    // クラス名からファイルパスを生成するための対応表
    // 今後、新しいディレクトリが増えたら、ここに追加していく
    $directoryMap = [
        'Core',
        'Models',
        'Lib',
        'controllers',
    ];

    // srcディレクトリ内のファイルを検索
    foreach ($directoryMap as $dir) {
        $file = __DIR__ . "/src/{$dir}/{$className}.php";
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }

    // プロジェクトルート直下のファイルも検索（移行期間中のため）
    $file = __DIR__ . "/{$className}.php";
    if (file_exists($file)) {
        require_once $file;
        return;
    }
});
