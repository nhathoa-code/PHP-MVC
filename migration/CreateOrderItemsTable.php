<?php

namespace App\Migration;

class CreateOrderItemsTable {
    public function up($db) {
        $sql = "CREATE TABLE IF NOT EXISTS order_items (
                id INT AUTO_INCREMENT PRIMARY KEY,
                order_id VARCHAR(50) NOT NULL,
                p_id VARCHAR(50) NOT NULL,
                quantity INT NOT NULL,
                p_price INT NOT NULL,
                p_size VARCHAR(20) NULL,
                p_color_id INT NULL,
                FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE ON UPDATE CASCADE,
                FOREIGN KEY (p_id) REFERENCES products(id) ON DELETE CASCADE ON UPDATE CASCADE,
                FOREIGN KEY (p_color_id) REFERENCES product_colors(id) ON DELETE CASCADE ON UPDATE CASCADE
            )
            ";
            $db->migrate($sql);
    }

    public function down($db) {
        $sql = "DROP TABLE IF EXISTS order_items";
        $db->migrate($sql);
    }
}