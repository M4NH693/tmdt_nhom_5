<?php
require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/config/database.php';
require_once __DIR__ . '/app/core/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    $db->exec("ALTER TABLE users 
               ADD COLUMN reset_token VARCHAR(64) DEFAULT NULL COMMENT 'Token dùng để đặt lại mật khẩu' AFTER is_active, 
               ADD COLUMN reset_expires_at DATETIME DEFAULT NULL COMMENT 'Thời hạn hiệu lực của token' AFTER reset_token");
    echo "Columns added successfully.\n";
} catch (PDOException $e) {
    if ($e->getCode() == '42S21') {
        echo "Columns already exist.\n";
    } else {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
