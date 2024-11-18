# サッカーグラウンド予約システム ER図

```mermaid
erDiagram
    users ||--o{ reservations : "makes"
    reservations }o--|| prices : "refers_to"

    users {
        int id PK
        string name "ユーザー名"
        string email "メールアドレス"
        string password "パスワード"
        string phone "電話番号"
        boolean is_admin "管理者フラグ"
        datetime created_at "作成日時"
        datetime updated_at "更新日時"
    }

    prices {
        int id PK
        enum pitch_type "コート種別"
        enum time_type "時間帯"
        int price "料金"
        datetime created_at "作成日時"
        datetime updated_at "更新日時"
    }

    reservations {
        int id PK
        int user_id FK "ユーザーID"
        enum pitch_type "コート種別"
        date reservation_date "予約日"
        time start_time "開始時間"
        time end_time "終了時間"
        int normal_hours "通常時間数"
        int night_hours "ナイター時間数"
        int total_price "合計金額"
        enum status "予約状態"
        datetime created_at "作成日時"
        datetime updated_at "更新日時"
    }