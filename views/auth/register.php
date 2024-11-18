<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー登録</title>
    <link rel="stylesheet" href="/reservation/public/css/style.css">
</head>
<body>
    <div class="container">
        <h1>ユーザー登録</h1>
        <form id="registerForm" class="auth-form">
            <div class="form-group">
                <label for="representative_name">代表者氏名</label>
                <input type="text" id="representative_name" name="representative_name" required>
                <span class="error" id="representativeNameError"></span>
            </div>

            <div class="form-group">
                <label for="team_name">チーム名</label>
                <input type="text" id="team_name" name="team_name" required>
                <span class="error" id="teamNameError"></span>
            </div>
            
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

            <div class="form-group">
                <label for="password_confirmation">パスワード確認</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
                <span class="error" id="passwordConfirmationError"></span>
            </div>
            
            <button type="submit">登録</button>
        </form>
        <p>既にアカウントをお持ちの方は<a href="/reservation/public/login">こちら</a></p>
    </div>

    <script>
    // DOMが完全に読み込まれたことを確認
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM読み込み完了'); // デバッグ用

        const form = document.getElementById('registerForm');
        console.log('フォーム要素:', form); // デバッグ用

        if (form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                console.log('フォーム送信イベント発生'); // デバッグ用

                // フォームデータの収集
                const formData = {
                    representative_name: document.getElementById('representative_name').value,
                    team_name: document.getElementById('team_name').value,
                    email: document.getElementById('email').value,
                    password: document.getElementById('password').value,
                    password_confirmation: document.getElementById('password_confirmation').value
                };

                console.log('送信データ:', formData); // デバッグ用

                try {
                    const response = await fetch('/reservation/public/register', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(formData)
                    });

                    console.log('レスポンスステータス:', response.status); // デバッグ用
                    const data = await response.json();
                    console.log('レスポンスデータ:', data); // デバッグ用

                    if (!response.ok) {
                        if (data.errors) {
                            Object.keys(data.errors).forEach(key => {
                                const errorElement = document.getElementById(`${key}Error`);
                                if (errorElement) {
                                    errorElement.textContent = data.errors[key];
                                }
                            });
                        } else {
                            alert(data.error || 'エラーが発生しました');
                        }
                        return;
                    }

                    alert('登録が完了しました');
                    window.location.href = '/reservation/public/login';

                } catch (error) {
                    console.error('エラー詳細:', error);
                    alert('エラーが発生しました');
                }
            });
        } else {
            console.error('フォーム要素が見つかりません');
        }
    });
</script>
</body>
</html>