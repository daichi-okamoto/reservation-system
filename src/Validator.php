<?php
// src/Validator.php
namespace App;

class Validator {
    public static function validateRegistration(array $data): array {
        $errors = [];
        
        // 代表者氏名の検証
        if (empty($data['representative_name']) || strlen($data['representative_name']) > 100) {
            $errors['representative_name'] = '代表者氏名は必須です';
        }

        // チーム名の検証
        if (empty($data['team_name']) || strlen($data['team_name']) > 100) {
            $errors['team_name'] = 'チーム名は必須です';
        }
        
        // メールアドレスの検証
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = '有効なメールアドレスを入力してください。';
        }
        
        // パスワードの検証
        if (empty($data['password'])) {
            $errors['password'] = 'パスワードは必須です。';
        } elseif (strlen($data['password']) < 5) {
            $errors['password'] = 'パスワードは5文字以上で入力してください。';
        }

        // パスワード確認の検証
        if (empty($data['password_confirmation'])) {
            $errors['password_confirmation'] = 'パスワード確認は必須です。';
        } elseif ($data['password'] !== $data['password_confirmation']) {
            $errors['password_confirmation'] = 'パスワードが一致しません。';
        }
        
        return $errors;
    }
}