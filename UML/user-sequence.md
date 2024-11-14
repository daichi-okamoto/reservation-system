```mermaid
sequenceDiagram
    actor Customer as ユーザー
    participant UI as 画面
    participant API as サーバー
    participant DB as データベース

    Customer->>UI: 1. 会員登録ページを開く
    UI-->>Customer: 2. 登録フォームを表示

    Customer->>UI: 3. 必要情報を入力
    Note right of Customer: 名前<br/>メールアドレス<br/>パスワード<br/>電話番号

    UI->>UI: 4. 入力チェック
    Note right of UI: ・必須項目の確認<br/>・メールアドレスの形式<br/>・パスワードの強度

    UI->>API: 5. 登録リクエスト送信

    API->>DB: 6. メールアドレス重複チェック
    DB-->>API: 7. チェック結果

    alt メールアドレスが既に登録済み
        API-->>UI: 8a. エラー返却
        UI-->>Customer: 9a. エラーメッセージ表示
    else 登録可能
        API->>API: 8b. パスワードの暗号化
        API->>DB: 9b. ユーザー情報保存
        DB-->>API: 10b. 保存完了

        API-->>UI: 11b. 登録完了通知
        UI-->>Customer: 12b. 完了画面表示
    end