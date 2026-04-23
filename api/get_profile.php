<?php
require_once __DIR__ . '/../includes/init.php';

header('Content-Type: application/json');

if (empty($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$uid = (int) $_SESSION['user_id'];

$stmt = $mysqli->prepare('SELECT first_name, last_name, email, phone, address FROM users WHERE id = ?');
$stmt->bind_param('i', $uid);
$stmt->execute();
$u_res = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($u_res) {
    $user_name_val = trim($u_res['first_name'] . ' ' . $u_res['last_name']);
    echo json_encode([
        'success' => true,
        'user' => [
            'name' => $user_name_val,
            'email' => $u_res['email'],
            'phone' => $u_res['phone'] ?? '',
            'address' => $u_res['address'] ?? ''
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'User not found']);
}
