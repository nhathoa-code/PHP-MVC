<?php

namespace App\Migration;

class CreateOrderMetaTable {
    public function up($db) {
        $sql = "CREATE TABLE IF NOT EXISTS order_meta (
                id INT AUTO_INCREMENT PRIMARY KEY,
                order_id VARCHAR(50) NULL,
                meta_key VARCHAR(50) NOT NULL,
                meta_value TEXT NOT NULL,
                FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE ON UPDATE CASCADE
            )
            ";
            $db->migrate($sql);
    }

    public function down($db) {
        $sql = "DROP TABLE IF EXISTS order_meta";
        $db->migrate($sql);
    }
}