<?php
require_once dirname(__DIR__) . '/includes/init.php';

$sql = "CREATE TABLE IF NOT EXISTS offers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    discount_percentage INT NOT NULL DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($mysqli->query($sql)) {
    echo "Table 'offers' created successfully.\n";

    // Insert a sample offer if none exists
    $check = $mysqli->query("SELECT id FROM offers LIMIT 1");
    if ($check->num_rows === 0) {
        $mysqli->query("INSERT INTO offers (title, description, discount_percentage, status) VALUES 
            ('Summer Special 2026', 'Get a flat 15% discount on all room bookings this summer!', 15, 'active')");
        echo "Sample offer inserted.\n";
    }
} else {
    echo "Error creating table: " . $mysqli->error . "\n";
}
