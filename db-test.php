<?php
$host = '192.168.64.6';  // DBサーバーのIP
$dbname = 'reservation_system';
$username = 'okamotodaichi';
$password = 'Daok-1005';  // AWXで設定したパスワード

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname",
        $username,
        $password,
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );
    echo "データベース接続成功！\n";
    echo "MariaDBバージョン: " . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION);
} catch(PDOException $e) {
    echo "接続失敗: " . $e->getMessage();
}
?>