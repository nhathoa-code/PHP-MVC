<?php

namespace App\Migration;

class CreatePointHistoryTable {
     public function up($db) {
        $sql = "CREATE TABLE IF NOT EXISTS point_history (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                order_id VARCHAR(50) NOT NULL,
                order_total INT NOT NULL,
                point INT NOT NULL,
                action TINYINT NOT NULL,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
                FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE ON UPDATE CASCADE
            )
            ";
        $db->migrate($sql);
    }

    public function down($db) {
        $sql = "DROP TABLE IF EXISTS point_history";
        $db->migrate($sql);
    }
}