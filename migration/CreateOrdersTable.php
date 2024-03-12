<?php

namespace App\Migration;

class CreateOrdersTable {
    public function up($db) {
        $sql = "CREATE TABLE IF NOT EXISTS orders (
                id VARCHAR(50) PRIMARY KEY,
                user_id INT NULL,
                total INT NOT NULL,
                payment_method VARCHAR(10) NOT NULL,
                shipping_fee INT DEFAULT 0,
                coupon INT DEFAULT 0 ,
                name VARCHAR(50) NOT NULL,
                email VARCHAR(100) NOT NULL,
                phone VARCHAR(10) NOT NULL,
                address TEXT NOT NULL,
                note VARCHAR(255) DEFAULT '',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                status VARCHAR(50) NOT NULL,
                paid_status VARCHAR(50) DEFAULT 'Chưa thanh toán',
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
            )
            ";
            $db->migrate($sql);
    }

    public function down($db) {
        $sql = "DROP TABLE IF EXISTS orders";
        $db->migrate($sql);
    }
}