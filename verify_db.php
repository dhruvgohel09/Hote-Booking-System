<?php
require_once __DIR__ . '/includes/init.php';

$res = $mysqli->query("SELECT * FROM contact ORDER BY id DESC LIMIT 1");
if ($res) {
    while($row = $res->fetch_assoc()) {
        print_r($row);
    }
}
