<?php
header('Content-Type: application/json; charset=utf-8');
require_once dirname(__DIR__) . '/includes/init.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!is_array($data)) {
    $data = $_POST;
}

$room_id = (int) ($data['room_id'] ?? 0);
$check_in = $data['check_in'] ?? '';
$check_out = $data['check_out'] ?? '';

if ($room_id < 1 || $check_in === '' || $check_out === '') {
    echo json_encode(['success' => false, 'message' => 'Missing room or date details.']);
    exit;
}

$in = DateTime::createFromFormat('Y-m-d', $check_in);
$out = DateTime::createFromFormat('Y-m-d', $check_out);
if (!$in || !$out || $out <= $in) {
    echo json_encode(['success' => false, 'message' => 'Invalid dates.']);
    exit;
}

$stmt = $mysqli->prepare('SELECT status FROM rooms WHERE id = ? LIMIT 1');
$stmt->bind_param('i', $room_id);
$stmt->execute();
$room = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$room || $room['status'] !== 'available') {
    echo json_encode(['success' => false, 'message' => 'This room is not available for booking.']);
    exit;
}

$col_query = "SELECT id FROM bookings WHERE room_id = ? AND status != 'cancelled' AND check_in < ? AND check_out > ?";
$col_stmt = $mysqli->prepare($col_query);
$col_stmt->bind_param('iss', $room_id, $check_out, $check_in);
$col_stmt->execute();
$col_res = $col_stmt->get_result();
$col_stmt->close();

if ($col_res->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Sorry, this room has already been booked for these dates.']);
    exit;
}

echo json_encode(['success' => true, 'message' => 'Room is available.']);
exit;
