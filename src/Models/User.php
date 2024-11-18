<?php
namespace App\Models;

class User {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function create(array $userData): bool {
        // パスワードのハッシュ化
        $hashedPassword = password_hash($userData['password'], PASSWORD_BCRYPT);
        
        $sql = "INSERT INTO users (name, team_name, email, password) 
                VALUES (:name, :team_name, :email, :password)";
                
        try {
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                ':name' => $userData['representative_name'],
                ':team_name' => $userData['team_name'],
                ':email' => $userData['email'],
                ':password' => $hashedPassword
            ]);
            
            if (!$result) {
                error_log("Database error: " . print_r($stmt->errorInfo(), true));
                throw new \Exception('データベースエラーが発生しました。');
            }
            
            return true;
        } catch (\PDOException $e) {
            error_log("PDO error: " . $e->getMessage());
            if ($e->getCode() == 23000) { // Duplicate entry
                throw new \Exception('このメールアドレスは既に登録されています。');
            }
            throw new \Exception('データベースエラーが発生しました。');
        }
    }
}