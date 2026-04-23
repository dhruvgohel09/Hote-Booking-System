<?php
require_once __DIR__ . '/includes/init.php';

$email = 'db2672329@gmail.com';
$st = $mysqli->prepare('SELECT id, email, is_active FROM users WHERE email = ?');
$st->bind_param('s', $email);
$st->execute();
$res = $st->get_result();

if ($row = $res->fetch_assoc()) {
    echo "Found user: ID " . $row['id'] . ", active " . $row['is_active'] . "\n";
} else {
    echo "User NOT FOUND via direct fetch.\n";
    // Check if there are any trailing spaces in the DB
    $res2 = $mysqli->query("SELECT id, email FROM users WHERE email LIKE '%db26723%'");
    while ($r = $res2->fetch_assoc()) {
        echo "Found similar: [" . $r['email'] . "] (ID " . $r['id'] . ")\n";
    }
}
