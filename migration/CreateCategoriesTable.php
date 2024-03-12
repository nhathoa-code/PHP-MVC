<?php

namespace App\Migration;

class CreateCategoriesTable {
    public function up($db) {
        $sql = "CREATE TABLE IF NOT EXISTS categories (
                id INT AUTO_INCREMENT PRIMARY KEY,
                cat_name VARCHAR(50) NOT NULL,
                cat_slug VARCHAR(50) NOT NULL,
                cat_image VARCHAR(100) NULL,
                parent_id INT NULL
            )
            ";
            $db->migrate($sql);
    }

    public function down($db) {
        $sql = "DROP TABLE IF EXISTS categories";
        $db->migrate($sql);
    }
}