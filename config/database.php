<?php
// database.php
$config = require __DIR__ . '/config.php';

try {
    // デバッグ情報を出力
    error_log("接続情報: " . print_r([
        'host' => $config['database']['host'],
        'dbname' => $config['database']['name'],
        'user' => $config['database']['user']
    ], true));

    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4',
        $config['database']['host'],
        $config['database']['name']
    );
    
    $db = new PDO(
        $dsn,
        $config['database']['user'],
        $config['database']['pass'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'
        ]
    );

    // 接続成功のログ
    error_log("データベース接続成功");

} catch (PDOException $e) {
    // 詳細なエラー情報をログに記録
    error_log("データベース接続エラー: " . $e->getMessage());
    error_log("DSN: " . $dsn);
    exit('データベース接続に失敗しました。');
}