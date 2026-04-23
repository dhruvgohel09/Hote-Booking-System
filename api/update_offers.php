<?php
require_once dirname(__DIR__) . '/includes/init.php';

// Add target_rooms column to offers table if it doesn't exist
$mysqli->query("ALTER TABLE offers ADD COLUMN target_rooms VARCHAR(255) NULL AFTER description");

// Find rooms that match '12' or '105' (or '104' if 105 doesn't exist)
$res = $mysqli->query("SELECT id, room_name FROM rooms WHERE room_name LIKE '%12%' OR room_name LIKE '%105%' OR room_name LIKE '%104%' LIMIT 2");
$ids = [];
$roomNames = [];
while($r = $res->fetch_assoc()) {
    $ids[] = $r['id'];
    $roomNames[] = $r['room_name'];
}
$target_str = implode(',', $ids);

// Deactivate all old offers
$mysqli->query("UPDATE offers SET status='inactive'");

// Add the new 50% off offer
if (!empty($target_str)) {
    $title = "Flash Deal: 50% OFF";
    $desc = "Massive 50% discount on " . implode(" and ", $roomNames);
    $mysqli->query("INSERT INTO offers (title, description, discount_percentage, target_rooms, status) VALUES ('$title', '$desc', 50, '$target_str', 'active')");
    echo json_encode(["status" => "success", "targets" => $target_str, "names" => $roomNames]);
} else {
    echo json_encode(["status" => "error", "message" => "Rooms not found"]);
}
