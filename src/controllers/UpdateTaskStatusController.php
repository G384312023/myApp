<?php
    session_start();

    if(!isset($_SESSION['user'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'ログインが必要です。']);
        exit();
    }

    //ブラウザにjson形式で応答することを伝える。
    header('Content-Type: application/json');

    //POSTされたJSONデータを受け取る。
    $input = json_decode(file_get_contents('php://input'), true);

    $taskId = $input['id'] ?? '';
    $isDone = $input['is_done'] ?? '';
    $userId = (int) $_SESSION['user']['id']; // 文字列を整数に変換

    if($taskId === '' || $isDone === '') {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => '必要なデータが不足しています。']);
        exit;
    }

    $db = Database::getInstance();
    $repo = new TaskRepository($db);

    $task = $repo->findTaskByTaskId($taskId);
    if(!$task || $task['user_id'] !== $userId) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'このタスクを更新する権限がありません。']);
        exit;
    }

    $result = $repo->updateTaskStatus($taskId, $isDone);

    if($result) {
        echo json_encode(['success' => true]);
    }else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'データベースの更新に失敗しました。']);
    }
?>
