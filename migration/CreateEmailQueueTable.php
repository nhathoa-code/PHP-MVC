<?php

namespace App\Migration;

class CreateEmailQueueTable {
     public function up($db) {
        $sql = "CREATE TABLE IF NOT EXISTS email_queue (
                id INT AUTO_INCREMENT PRIMARY KEY,
                to_email VARCHAR(255),
                subject VARCHAR(255),
                message TEXT,
                sent_at TIMESTAMP NULL DEFAULT NULL
            )
            ";
            $db->migrate($sql);
    }

    public function down($db) {
        $sql = "DROP TABLE IF EXISTS email_queue";
        $db->migrate($sql);
    }
}