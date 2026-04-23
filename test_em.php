<?php
require_once __DIR__ . '/includes/init.php';
$r = $mysqli->query('SELECT email FROM users');
if($r) {
    while($row = $r->fetch_assoc()) {
        echo $row['email'] . "\n";
    }
} else {
    echo "Query failed";
}
