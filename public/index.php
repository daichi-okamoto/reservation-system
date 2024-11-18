<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// データベース接続
require_once __DIR__ . '/../config/database.php';

// リクエストの情報を取得
$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

// デバッグ用
error_log("Request URI: " . $request_uri);
error_log("Request Method: " . $request_method);

// ベースパスを除去してルーティングパスを取得
$base_path = '/reservation/public';
$route = str_replace($base_path, '', $request_uri);

// ルーティング処理
switch (true) {
    // 登録フォームの表示（GET）
    case $route === '/register' && $request_method === 'GET':
        require_once __DIR__ . '/../views/auth/register.php';
        break;

    // 登録処理（POST）
    case $route === '/register' && $request_method === 'POST':
        header('Content-Type: application/json');
        
        try {
            // POSTデータの取得
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            error_log("Received data: " . print_r($data, true));  // デバッグ用

            // ここでバリデーションと登録処理
            require_once __DIR__ . '/../src/Validator.php';
            $errors = \App\Validator::validateRegistration($data);
            
            if (!empty($errors)) {
                http_response_code(400);
                echo json_encode(['errors' => $errors]);
                exit;
            }

            // ユーザー登録処理
            require_once __DIR__ . '/../src/Models/User.php';
            $userModel = new \App\Models\User($db);
            $userModel->create($data);

            http_response_code(201);
            echo json_encode(['message' => '登録が完了しました']);

        } catch (\Exception $e) {
            error_log("Error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => '404 Not Found']);
        break;
}