<?php
namespace App\Auth;
use Dotenv\Dotenv;

class Authentication {
    private $db;
    private $jwtAuth;

    public function __construct($db) {
        $this->db = $db;

        // オートローダーを読み込む
        require_once __DIR__ . '/../../vendor/autoload.php';

        // .env ファイルを読み込む
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        // 環境変数が設定されているか確認
        if (!isset($_ENV['JWT_SECRET_KEY'])) {
            throw new \Exception('JWT secret key is not set in environment');
        }

        // JwtAuth クラスを初期化
        require_once __DIR__ . '/JwtAuth.php';
        $this->jwtAuth = new JwtAuth();
    }

    public function login(string $email, string $password, bool $remember_me = false): ?array {
        try {
            error_log("Starting login process for email: " . $email);
            // 1. ユーザーの検索
            $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            error_log("User found: " . ($user ? 'Yes' : 'No'));

            // 2. ユーザーが存在しない場合
            if (!$user) {
                error_log('User not found: ' . $email);
                return null;
            }

            // 3. パスワードの確認
            if (!password_verify($password, $user['password'])) {
                error_log('Invalid password for user: ' . $email);
                return null;
            }

            // 4. パスワードを除外（セキュリティのため）
            unset($user['password']);

            // 5. 最終ログイン時間の更新
            $this->updateLastLogin($user['id']);

            // 6. JWTトークンの生成（Remember Me対応）
            $token = $this->jwtAuth->createToken([
                'user_id' => $user['id'],
                'email' => $user['email'],
                'remember_me' => $remember_me
            ]);

            // 7. 結果を返す
            return [
                'user' => $user,
                'token' => $token
            ];

        } catch (\Exception $e) {
            error_log('Login error: ' . $e->getMessage());
            return null;
        }
    }

    private function updateLastLogin(int $userId): void {
        try {
            $sql = "UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $userId]);
        } catch (\Exception $e) {
            error_log('Error updating last login: ' . $e->getMessage());
        }
    }

    // ログアウトメソッド
    public function logout(): bool {
        try {
            // JWTは明示的な無効化は不要（クライアント側でトークンを削除）
            return true;
        } catch (\Exception $e) {
            error_log('Logout error: ' . $e->getMessage());
            return false;
        }
    }

    // ユーザーの最終ログイン時間を取得するメソッド（オプション）
    public function getLastLogin(int $userId): ?string {
        try {
            $sql = "SELECT last_login FROM users WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $userId]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $result ? $result['last_login'] : null;
        } catch (\Exception $e) {
            error_log('Error getting last login: ' . $e->getMessage());
            return null;
        }
    }
}