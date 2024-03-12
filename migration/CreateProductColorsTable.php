<?php

namespace App\Migration;

class CreateProductColorsTable {
    public function up($db) {
        $sql = "CREATE TABLE IF NOT EXISTS product_colors (
                id INT AUTO_INCREMENT PRIMARY KEY,
                p_id VARCHAR(50) NOT NULL,
                color_image VARCHAR(100) NOT NULL,
                color_name VARCHAR(50) NOT NULL,
                price INT DEFAULT 0,
                stock INT DEFAULT 0,
                gallery_dir varchar(100) NOT NULL,
                active ENUM('true','false') DEFAULT 'true',
                FOREIGN KEY (p_id) REFERENCES products(id) ON DELETE CASCADE ON UPDATE CASCADE
            )
            ";
            $db->migrate($sql);
    }

    public function down($db) {
        $sql = "DROP TABLE IF EXISTS product_colors";
        $db->migrate($sql);
    }
}