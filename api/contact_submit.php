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


$name = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');
$subject = trim($data['subject'] ?? '');
$message = trim($data['message'] ?? '');
$phone = trim($data['phone'] ?? '');

if (strlen($name) < 3 || $email === '' || strlen($subject) < 5 || strlen($message) < 10) {
    echo json_encode(['success' => false, 'message' => 'Please complete all fields (message at least 10 characters).']);
    exit;
}

if (!preg_match('/^[a-zA-Z\s]+$/', $name)) {
    echo json_encode(['success' => false, 'message' => 'Name must contain letters only.']);
    exit;
}

$stmt = $mysqli->prepare('INSERT INTO contact (name, email, phone, subject, message) VALUES (?,?,?,?,?)');
$stmt->bind_param('sssss', $name, $email, $phone, $subject, $message);
if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Could not save message.']);
    exit;
}
$stmt->close();

echo json_encode(['success' => true, 'message' => 'Send Message Successfully']);
