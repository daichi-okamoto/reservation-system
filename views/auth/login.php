<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
    <link rel="stylesheet" href="/reservation/public/css/style.css">
</head>
<body>
    <div class="container">
        <h1>ログイン</h1>
        <form id="loginForm" class="auth-form">
            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input type="email" id="email" name="email" required>
                <span class="error" id="emailError"></span>
            </div>
            
            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password" required>
                <span class="error" id="passwordError"></span>
            </div>

            <div class="form-group checkbox-group">
                <label class="checkbox-label">
                    <input type="checkbox" id="remember_me" name="remember_me">
                    ログイン状態を保持する
                </label>
            </div>
            
            <button type="submit">ログイン</button>
        </form>
        <p>アカウントをお持ちでない方は<a href="/reservation/public/register">こちら</a></p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                // エラーメッセージをクリア
                document.querySelectorAll('.error').forEach(el => el.textContent = '');
                
                const formData = {
                    email: document.getElementById('email').value,
                    password: document.getElementById('password').value,
                    remember_me: document.getElementById('remember_me').checked
                };
                
                try {
                    console.log('送信データ:', formData);
                    const response = await fetch('/reservation/public/login', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(formData)
                    });
                    
                    console.log('レスポンスステータス:', response.status);
                    const responseText = await response.text();
                    console.log('レスポンス本文:', responseText);
                    
                    const data = JSON.parse(responseText);
                    console.log('パース後のデータ:', data);
                    
                    if (!response.ok) {
                        if (data.errors) {
                            Object.keys(data.errors).forEach(key => {
                                const errorElement = document.getElementById(`${key}Error`);
                                if (errorElement) {
                                    errorElement.textContent = data.errors[key];
                                }
                            });
                        } else {
                            alert(data.error || 'ログインに失敗しました');
                        }
                        return;
                    }
                    
                    // ログイン成功時の処理
                    if (data.token) {
                        // JWTトークンをローカルストレージに保存
                        localStorage.setItem('jwt_token', data.token);
                        
                        alert('ログインしました');
                        // ダッシュボードへリダイレクト
                        window.location.href = '/reservation/public/dashboard';
                    }
                    
                } catch (error) {
                    console.error('エラー:', error);
                    alert('エラーが発生しました');
                }
            });
        });
    </script>
</body>
</html>