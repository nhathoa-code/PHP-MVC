<?php

namespace App\Migration;

class CreateUsersTable {
     public function up($db) {
        $sql = "CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(50) NOT NULL,
                login_key VARCHAR(50) UNIQUE NOT NULL,
                email VARCHAR(100) NOT NULL,
                email_verified_at TIMESTAMP NULL,
                password VARCHAR(250) NOT NULL,
                remember_token VARCHAR(255) NULL,
                role ENUM('user', 'admin', 'guest') DEFAULT 'user',
                created_at DATE DEFAULT CURRENT_TIMESTAMP
            )
            ";
            $db->migrate($sql);
    }

    public function down($db) {
        $sql = "DROP TABLE IF EXISTS users";
        $db->migrate($sql);
    }
}