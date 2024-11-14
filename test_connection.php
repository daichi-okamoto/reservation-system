<?php
require_once __DIR__ . '/config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    echo "データベース接続成功！\n";
    
    // usersテーブルの存在確認
    $query = "SELECT 1 FROM users LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->execute();
    echo "usersテーブルへのアクセス成功！\n";
    
    // pricesテーブルの料金確認
    $query = "SELECT * FROM prices LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch();
    echo "料金テーブルへのアクセス成功！\n";
    echo "サンプル料金: " . $result['price'] . "円\n";
    
} catch(Exception $e) {
    echo "テストエラー: " . $e->getMessage() . "\n";
}