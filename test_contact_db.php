<?php
require_once __DIR__ . '/includes/init.php';

$res = $mysqli->query("SHOW TABLES LIKE 'contact'");
if ($res->num_rows > 0) {
    echo "Table contact exists.\n";
} else {
    echo "Table contact DOES NOT exist.\n";
}

$res = $mysqli->query("DESCRIBE contact");
if ($res) {
    while($row = $res->fetch_assoc()) {
        print_r($row);
    }
} else {
    echo "Error describing contact: " . $mysqli->error . "\n";
}
