<?php

namespace App\Migration;

class CreateProductColorsSizesTable {
    public function up($db) {
        $sql = "CREATE TABLE IF NOT EXISTS product_colors_sizes (
                id INT AUTO_INCREMENT PRIMARY KEY,
                p_id VARCHAR(50) NOT NULL,
                color_id INT NOT NULL,
                size VARCHAR(50) NOT NULL,
                price INT DEFAULT 0,
                stock INT DEFAULT 0,
                active ENUM ('true','false') DEFAULT 'true',
                FOREIGN KEY (p_id) REFERENCES products(id) ON DELETE CASCADE ON UPDATE CASCADE,
                FOREIGN KEY (color_id) REFERENCES product_colors(id) ON DELETE CASCADE ON UPDATE CASCADE
            )
            ";
            $db->migrate($sql);
    }

    public function down($db) {
        $sql = "DROP TABLE IF EXISTS product_colors_sizes";
        $db->migrate($sql);
    }
}