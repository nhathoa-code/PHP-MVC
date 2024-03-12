<?php

namespace App\Migration;

class CreateUserMetaTable {
    public function up($db) {
        $sql = "CREATE TABLE IF NOT EXISTS user_meta (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                meta_key VARCHAR(50) NOT NULL,
                meta_value TEXT NOT NULL,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
            )
            ";
            $db->migrate($sql);
    }

    public function down($db) {
        $sql = "DROP TABLE IF EXISTS user_meta";
        $db->migrate($sql);
    }
}