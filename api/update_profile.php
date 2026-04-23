<?php
require_once __DIR__ . '/../includes/init.php';

header('Content-Type: application/json');

if (empty($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$uid = (int) $_SESSION['user_id'];
$new_username = trim($input['username'] ?? '');
$new_email = trim($input['email'] ?? '');
$new_phone = preg_replace('/\D/', '', $input['phone'] ?? '');
$new_address = trim($input['address'] ?? '');

if (empty($new_username) || empty($new_email)) {
    echo json_encode(['success' => false, 'message' => 'Name and Email are required!']);
    exit;
}

if (!preg_match('/^[a-zA-Z\s]+$/', $new_username)) {
    echo json_encode(['success' => false, 'message' => 'Name must contain letters only.']);
    exit;
}

if (!empty($new_phone) && strlen($new_phone) !== 10) {
    echo json_encode(['success' => false, 'message' => 'Enter a valid 10-digit phone number.']);
    exit;
}

$parts = preg_split('/\s+/', $new_username, 2);
$fn = $parts[0];
$ln = $parts[1] ?? '';

$upd = $mysqli->prepare('UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, address = ? WHERE id = ?');
$upd->bind_param('sssssi', $fn, $ln, $new_email, $new_phone, $new_address, $uid);

if ($upd->execute()) {
    $_SESSION['user_name'] = trim($fn . ' ' . $ln);
    $_SESSION['user_email'] = $new_email;
    echo json_encode([
        'success' => true, 
        'message' => 'Profile updated successfully!',
        'new_name' => $_SESSION['user_name'],
        'new_email' => $_SESSION['user_email']
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Could not update (email may already be in use).']);
}
$upd->close();
