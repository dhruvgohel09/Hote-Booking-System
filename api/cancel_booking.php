<?php
header('Content-Type: application/json; charset=utf-8');
require_once dirname(__DIR__) . '/includes/init.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

if (empty($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please log in.']);
    exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!is_array($data)) {
    $data = $_POST;
}

$booking_id = (int) ($data['booking_id'] ?? 0);
$user_id = (int) $_SESSION['user_id'];

if ($booking_id < 1) {
    echo json_encode(['success' => false, 'message' => 'Invalid booking.']);
    exit;
}

$stmt = $mysqli->prepare('SELECT id, status FROM bookings WHERE id = ? AND user_id = ? LIMIT 1');
$stmt->bind_param('ii', $booking_id, $user_id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row) {
    echo json_encode(['success' => false, 'message' => 'Booking not found.']);
    exit;
}

if ($row['status'] === 'cancelled') {
    echo json_encode(['success' => false, 'message' => 'Already cancelled.']);
    exit;
}

$upd = $mysqli->prepare('UPDATE bookings SET status = ? WHERE id = ? AND user_id = ?');
$cancelled = 'cancelled';
$upd->bind_param('sii', $cancelled, $booking_id, $user_id);
$upd->execute();
$upd->close();

echo json_encode(['success' => true, 'message' => 'Booking cancelled.']);
