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

$booking_id = isset($data['booking_id']) ? (int)$data['booking_id'] : null;
$rating = isset($data['rating']) ? (int)$data['rating'] : 0;
$comments = isset($data['comments']) ? trim($data['comments']) : '';

if ($rating < 1 || $rating > 5) {
    echo json_encode(['success' => false, 'message' => 'Invalid rating provided.']);
    exit;
}

$stmt = $mysqli->prepare('INSERT INTO feedback (booking_id, rating, comments) VALUES (?, ?, ?)');
$stmt->bind_param('iis', $booking_id, $rating, $comments);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Thank you! Your feedback has been submitted.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error submitting feedback.']);
}
$stmt->close();
