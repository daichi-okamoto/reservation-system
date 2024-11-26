<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', '/var/www/html/reservation/php_error.log');

error_log("アプリケーション開始: " . date('Y-m-d H:i:s'));

// 強制的にエラーを表示（開発環境のみ）
function exception_error_handler($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
}
set_error_handler("exception_error_handler");

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
    // トップページ
    case $route === '/':
        echo 'Welcome to Reservation System';
        break;

    // 登録フォームの表示（GET）
    case $route === '/register' && $request_method === 'GET':
        require_once __DIR__ . '/../views/auth/register.php';
        break;

    // 登録処理（POST）
    case $route === '/register' && $request_method === 'POST':
        header('Content-Type: application/json');
        try {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            error_log("Received data: " . print_r($data, true));

            require_once __DIR__ . '/../src/Validator.php';
            $errors = \App\Validator::validateRegistration($data);
            
            if (!empty($errors)) {
                http_response_code(400);
                echo json_encode(['errors' => $errors]);
                exit;
            }

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

    // ログインフォーム表示
    case $route === '/login' && $request_method === 'GET':
        require_once __DIR__ . '/../views/auth/login.php';
        break;

    // ログイン処理部分を修正
    case $route === '/login' && $request_method === 'POST':
    header('Content-Type: application/json');
    try {
        // 1. POSTデータの受信確認
        $rawData = file_get_contents('php://input');
        error_log("Raw POST data: " . $rawData);

        // 2. JSONデコード
        $data = json_decode($rawData, true);
        error_log("Decoded data: " . print_r($data, true));

        // 3. ファイルの存在確認
        $authFile = __DIR__ . '/../src/Auth/Authentication.php';
        error_log("Looking for Authentication.php at: " . $authFile);
        if (!file_exists($authFile)) {
            error_log("Authentication.php not found!");
            throw new \Exception('Authentication file not found');
        }

        // 4. クラス読み込み
        error_log("Loading Authentication class...");
        require_once $authFile;
        
        // 5. Authentication インスタンス作成
        error_log("Creating Authentication instance...");
        $auth = new \App\Auth\Authentication($db);
        
        // 6. ログイン処理
        error_log("Attempting login with email: " . $data['email']);
        $result = $auth->login(
            $data['email'],
            $data['password'],
            isset($data['remember_me']) ? $data['remember_me'] : false
        );
        
        // 7. 結果の確認
        error_log("Login result: " . print_r($result, true));

        if ($result) {
            echo json_encode([
                'message' => 'ログイン成功',
                'token' => $result['token'],
                'user' => $result['user']
            ]);
        } else {
            http_response_code(401);
            echo json_encode(['error' => 'メールアドレスまたはパスワードが正しくありません']);
        }
        
    } catch (\Exception $e) {
        error_log("Login error details: " . $e->getMessage());
        error_log("Error trace: " . $e->getTraceAsString());
        http_response_code(500);
        echo json_encode(['error' => 'ログイン処理中にエラーが発生しました：' . $e->getMessage()]);
    }
    break;

    // 404 Not Found
    default:
        http_response_code(404);
        echo json_encode(['error' => '404 Not Found']);
        break;
    // ダッシュボード画面を表示
    case $route === '/dashboard' && $request_method === 'GET':
        require_once __DIR__ . '/../views/dashboard.php';
        break;
    }