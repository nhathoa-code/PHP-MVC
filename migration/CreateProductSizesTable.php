<?php

namespace App\Migration;

class CreateProductSizesTable {
    public function up($db) {
        $sql = "CREATE TABLE IF NOT EXISTS product_sizes (
                id INT AUTO_INCREMENT PRIMARY KEY,
                p_id VARCHAR(50) NOT NULL,
                size VARCHAR(50) NOT NULL,
                price INT DEFAULT 0,
                stock INT DEFAULT 0,
                active ENUM('true','false') DEFAULT 'true',
                FOREIGN KEY (p_id) REFERENCES products(id) ON DELETE CASCADE ON UPDATE CASCADE
            )
            ";
            $db->migrate($sql);
    }

    public function down($db) {
        $sql = "DROP TABLE IF EXISTS product_sizes";
        $db->migrate($sql);
    }
}