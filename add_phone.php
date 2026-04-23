<?php
require_once __DIR__ . '/includes/init.php';

$res = $mysqli->query("ALTER TABLE contact ADD COLUMN phone VARCHAR(20) DEFAULT NULL AFTER email");
if ($res) {
    echo "Phone column added.";
} else {
    echo "Error or already exists: " . $mysqli->error;
}
