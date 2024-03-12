<?php

namespace App\Migration;

class CreateWishlistTable {
     public function up($db) {
        $sql = "CREATE TABLE IF NOT EXISTS wish_list (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                p_id VARCHAR(50) NOT NULL,
                added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
                FOREIGN KEY (p_id) REFERENCES products(id) ON DELETE CASCADE ON UPDATE CASCADE
            )
            ";
            $db->migrate($sql);
    }

    public function down($db) {
        $sql = "DROP TABLE IF EXISTS wish_list";
        $db->migrate($sql);
    }
}