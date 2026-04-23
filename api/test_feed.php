<?php
require_once dirname(__DIR__) . '/includes/init.php';

$mysqli->query("INSERT INTO bookings (user_id, room_id, guests, check_in, check_out, total_price, bill_number, status) VALUES (2, 2, 2, '2026-08-01', '2026-08-02', 400, 'INV-TEST-99', 'confirmed')");
$new_booking_id = $mysqli->insert_id;

$msg = "Wow! I literally just submitted this review right now after my stay! The room was spectacular, the staff was perfect, and the 50% discount was absolutely amazing. Easy 5 stars!";
$stmt = $mysqli->prepare("INSERT INTO feedback (booking_id, rating, comments) VALUES (?, 5, ?)");
$stmt->bind_param("is", $new_booking_id, $msg);
$stmt->execute();

echo "Success! Live feedback inserted.";
