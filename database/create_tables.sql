CREATE DATABASE IF NOT EXISTS reservation_system 
CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE reservation_system;

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'ユーザーID',
    name VARCHAR(100) NOT NULL COMMENT '代表者氏名',
    team_name VARCHAR(100) NOT NULL COMMENT 'チーム名',
    email VARCHAR(255) NOT NULL UNIQUE COMMENT 'メールアドレス',
    password VARCHAR(255) NOT NULL COMMENT 'パスワード',
    is_admin BOOLEAN DEFAULT FALSE COMMENT '管理者フラグ',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '作成日時',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日時',
    last_login TIMESTAMP NULL DEFAULT NULL COMMENT '最終ログイン日時',
    INDEX idx_email (email)
) ENGINE=InnoDB COMMENT='ユーザー情報';

-- 予約テーブル
CREATE TABLE reservations (
    id INT PRIMARY KEY AUTO_INCREMENT COMMENT '予約ID',
    user_id INT NOT NULL COMMENT 'ユーザーID',
    pitch_type ENUM('full', 'half_north', 'half_south') NOT NULL COMMENT 'グラウンド種別',
    reservation_date DATE NOT NULL COMMENT '予約日',
    start_time TIME NOT NULL COMMENT '開始時間',
    end_time TIME NOT NULL COMMENT '終了時間',
    normal_hours INT NOT NULL DEFAULT 0 COMMENT '通常時間数',
    night_hours INT NOT NULL DEFAULT 0 COMMENT 'ナイター時間数',
    total_price INT NOT NULL COMMENT '合計金額',
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending' COMMENT '予約状態',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '作成日時',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日時',
    FOREIGN KEY (user_id) REFERENCES users(id),
    INDEX idx_date_time (reservation_date, start_time, end_time),
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB COMMENT='予約情報';

-- 料金テーブル
CREATE TABLE prices (
    id INT PRIMARY KEY AUTO_INCREMENT COMMENT '料金ID',
    pitch_type ENUM('full', 'half_north', 'half_south') NOT NULL COMMENT 'グラウンド種別',
    time_type ENUM('normal', 'night') NOT NULL COMMENT '時間帯区分',
    price INT NOT NULL COMMENT '1時間あたりの料金',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '作成日時',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日時',
    UNIQUE KEY idx_pitch_time (pitch_type, time_type)
) ENGINE=InnoDB COMMENT='料金テーブル';

-- 料金の初期データ投入
INSERT INTO prices (pitch_type, time_type, price) VALUES 
-- フルコートの料金
('full', 'normal', 2000),      -- フルコート・通常時間：2000円/時間
('full', 'night', 4000),       -- フルコート・ナイター：4000円/時間
-- 北側ハーフコートの料金
('half_north', 'normal', 1000), -- 北側ハーフ・通常時間：1000円/時間
('half_north', 'night', 2000),  -- 北側ハーフ・ナイター：2000円/時間
-- 南側ハーフコートの料金
('half_south', 'normal', 1000), -- 南側ハーフ・通常時間：1000円/時間
('half_south', 'night', 2000);  -- 南側ハーフ・ナイター：2000円/時間