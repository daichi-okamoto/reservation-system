```mermaid
sequenceDiagram
    actor Customer as ユーザー
    participant UI as 画面
    participant API as サーバー
    participant DB as データベース

    Customer->>UI: 1. 予約カレンダーを開く
    UI->>API: 2. 空き状況リクエスト
    API->>DB: 3. 予約データ取得
    DB-->>API: 4. 予約データ返却
    API-->>UI: 5. 空き状況返却
    UI-->>Customer: 6. カレンダー表示

    Customer->>UI: 7. 日時・コート種別選択
    UI->>API: 8. 予約可能確認
    API->>DB: 9. 重複チェック
    DB-->>API: 10. チェック結果

    alt 予約枠が空いている
        API-->>UI: 11a. 予約可能通知
        UI->>UI: 12a. 料金計算
        UI-->>Customer: 13a. 確認画面表示
        
        Customer->>UI: 14a. 予約確定
        UI->>API: 15a. 予約リクエスト
        API->>DB: 16a. 予約データ保存
        DB-->>API: 17a. 保存完了
        API-->>UI: 18a. 予約完了通知
        UI-->>Customer: 19a. 完了画面表示
    else 予約枠が埋まっている
        API-->>UI: 11b. 予約不可通知
        UI-->>Customer: 12b. エラーメッセージ表示
    end