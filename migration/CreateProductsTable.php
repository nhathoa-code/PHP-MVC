<?php

namespace App\Migration;

class CreateProductsTable {
    public function up($db) {
        $sql = "CREATE TABLE IF NOT EXISTS products (
                id VARCHAR(50) PRIMARY KEY,
                p_name VARCHAR(100) NOT NULL,
                p_price INT NOT NULL,
                p_stock INT DEFAULT 0,
                p_discount INT DEFAULT 0,
                p_desc TEXT,
                dir VARCHAR(100) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
            ";
            $db->migrate($sql);
    }

    public function down($db) {
        $sql = "DROP TABLE IF EXISTS products";
        $db->migrate($sql);
    }
}