<?php
require_once __DIR__ . '/../../../config/database.php';

// ヘッダー設定
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// リクエストから日付範囲を取得
$startDate = $_GET['start_date'] ?? null;
$endDate = $_GET['end_date'] ?? null;

if (!$startDate || !$endDate) {
    http_response_code(400);
    echo json_encode(['error' => 'start_date and end_date are required']);
    exit;
}

// データベース接続
try {
    $pdo = getDatabaseConnection();

    // 予約情報の取得
    $stmt = $pdo->prepare("
        SELECT user_id, pitch_type, reservation_date, start_time, end_time, status
        FROM reservations
        WHERE reservation_date BETWEEN :start_date AND :end_date
        ORDER BY reservation_date, start_time
    ");
    $stmt->execute([':start_date' => $startDate, ':end_date' => $endDate]);
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($reservations);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
