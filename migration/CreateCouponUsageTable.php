<?php

namespace App\Migration;

class CreateCouponUsageTable {
     public function up($db) {
        $sql = "CREATE TABLE IF NOT EXISTS coupon_usage (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                coupon_id INT NOT NULL,
                order_id VARCHAR(50) NOT NULL,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
                FOREIGN KEY (coupon_id) REFERENCES coupons(id) ON DELETE CASCADE ON UPDATE CASCADE,
                FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE ON UPDATE CASCADE
            )
            ";
            $db->migrate($sql);
    }

    public function down($db) {
        $sql = "DROP TABLE IF EXISTS couponusage";
        $db->migrate($sql);
    }
}