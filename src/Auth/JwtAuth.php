<?php
namespace App\Auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtAuth {
    private $secret_key;
    private $algorithm;

    public function __construct() {
        // 環境変数を確認
        $this->secret_key = $_ENV['JWT_SECRET_KEY'] ?? null;

        if (!$this->secret_key) {
            throw new \Exception('JWT secret key is not set in environment');
        }

        $this->algorithm = 'HS256';

        // デバッグ用
        error_log('JWT_SECRET_KEY is set: ' . ($this->secret_key ? 'Yes' : 'No'));
    }

    /**
     * JWTトークンを生成
     *
     * @param array $userData ユーザー情報（user_id, email, remember_me）
     * @return string トークン
     */
    public function createToken(array $userData): string {
        $issuedAt = time();

        // リメンバーミー機能の有効期限
        $expire = isset($userData['remember_me']) && $userData['remember_me']
            ? $issuedAt + (3600 * 24 * 30) // 30日
            : $issuedAt + (3600 * 24);     // 24時間

        $payload = [
            'iat'  => $issuedAt,  // トークン発行時刻
            'exp'  => $expire,    // トークン有効期限
            'user' => [
                'id'    => $userData['user_id'],
                'email' => $userData['email']
            ]
        ];

        // トークンを生成
        return JWT::encode($payload, $this->secret_key, $this->algorithm);
    }

    /**
     * JWTトークンを検証
     *
     * @param string $token トークン
     * @return array|null デコードされたユーザー情報
     */
    public function validateToken(string $token): ?array {
        try {
            $decoded = JWT::decode($token, new Key($this->secret_key, $this->algorithm));
            return (array) $decoded->user;
        } catch (\Exception $e) {
            error_log('Token validation error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * トークンの有効期限切れを確認
     *
     * @param string $token トークン
     * @return bool 有効期限切れなら true
     */
    public function isTokenExpired(string $token): bool {
        try {
            $decoded = JWT::decode($token, new Key($this->secret_key, $this->algorithm));
            return time() >= $decoded->exp;
        } catch (\Exception $e) {
            return true; // エラーが発生した場合は期限切れとみなす
        }
    }

    /**
     * トークンからユーザー情報を取得
     *
     * @param string $token トークン
     * @return array|null ユーザー情報
     */
    public function getUserFromToken(string $token): ?array {
        try {
            $decoded = JWT::decode($token, new Key($this->secret_key, $this->algorithm));
            return (array) $decoded->user;
        } catch (\Exception $e) {
            error_log('Error getting user from token: ' . $e->getMessage());
            return null;
        }
    }
}
