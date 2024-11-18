<?php
// 必要なファイルを読み込む
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Models\User;
use App\Validator;

// JSONを返すことを宣言
header('Content-Type: application/json');

// ①②POSTメソッドのみ受け付ける
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

try {
    // ③送られてきたデータを取得
    $data = json_decode(file_get_contents('php://input'), true);
    
    // ④入力内容をチェック
    $errors = Validator::validateRegistration($data);
    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode(['errors' => $errors]);
        exit;
    }
    
    // ⑤ユーザーを登録
    $userModel = new User($db);
    $userModel->create($data);
    
    // ⑥成功結果を返す
    http_response_code(201);  // 201 = Created
    echo json_encode(['message' => '登録が完了しました']);
    
} catch (\Exception $e) {
    // エラーが起きた場合の処理
    http_response_code(500);  // 500 = Server Error
    echo json_encode(['error' => $e->getMessage()]);
}