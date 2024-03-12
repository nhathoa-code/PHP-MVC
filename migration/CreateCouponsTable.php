<?php

namespace App\Migration;

class CreateCouponsTable {
    public function up($db) {
        $sql = "CREATE TABLE IF NOT EXISTS coupons (
                id INT AUTO_INCREMENT PRIMARY KEY,
                code VARCHAR(10) NOT NULL UNIQUE,
                amount INT NOT NULL,
                minimum_spend INT NOT NULL,
                start_time DATETIME NOT NULL,
                end_time DATETIME NOT NULL,
                coupon_usage INT NOT NULL,
                coupon_used INT DEFAULT 0,
                per_user TINYINT NOT NULL,
                CHECK (start_time < end_time)
            )
            ";
            $db->migrate($sql);
    }

    public function down($db) {
        $sql = "DROP TABLE IF EXISTS coupons";
        $db->migrate($sql);
    }
}