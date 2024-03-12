<?php

namespace App\Migration;

class CreatePasswordResetsTable {
    public function up($db) {
        $sql = "CREATE TABLE IF NOT EXISTS password_resets (
                id INT AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(50) NOT NULL,
                INDEX email_index (email),
                token VARCHAR(255) NOT NULL,
                created_at TIMESTAMP NULL
            )
            ";
            $db->migrate($sql);
    }

    public function down($db) {
        $sql = "DROP TABLE IF EXISTS password_resets";
        $db->migrate($sql);
    }
}