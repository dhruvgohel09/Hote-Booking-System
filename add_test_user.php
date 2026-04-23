<?php
require_once __DIR__ . '/includes/init.php';

$email = 'db2672329@gmail.com';
$password = password_hash('password123', PASSWORD_DEFAULT);

$stmt = $mysqli->prepare("INSERT IGNORE INTO users (first_name, last_name, email, password, phone, role, is_active) VALUES ('Demo', 'User', ?, ?, '1234567890', 'user', 1)");
$stmt->bind_param('ss', $email, $password);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Successfully injected $email into database.";
} else {
    echo "Email $email is already in the database.";
}
$stmt->close();
