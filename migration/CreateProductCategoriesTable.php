<?php

namespace App\Migration;

class CreateProductCategoriesTable {
    public function up($db) {
        $sql = "CREATE TABLE IF NOT EXISTS product_categories (
                id INT AUTO_INCREMENT PRIMARY KEY,
                p_id VARCHAR(50) NOT NULL,
                cat_id INT NOT NULL,
                FOREIGN KEY (p_id) REFERENCES products(id) ON DELETE CASCADE ON UPDATE CASCADE,
                FOREIGN KEY (cat_id) REFERENCES categories(id) ON DELETE CASCADE ON UPDATE CASCADE
            )
            ";
            $db->migrate($sql);
    }

    public function down($db) {
        $sql = "DROP TABLE IF EXISTS product_categories";
        $db->migrate($sql);
    }
}