<?php
header('Content-Type: application/json; charset=utf-8');
require_once dirname(__DIR__, 2) . '/includes/init.php';

if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Forbidden']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
    $input = $_POST;
}
$action = $input['action'] ?? '';

if ($action === 'update_status') {
    $id = (int) ($input['id'] ?? 0);
    $status = $input['status'] ?? '';
    if ($id < 1 || !in_array($status, ['pending', 'confirmed', 'cancelled'], true)) {
        echo json_encode(['success' => false, 'message' => 'Invalid data']);
        exit;
    }
    $st = $mysqli->prepare('UPDATE bookings SET status = ? WHERE id = ?');
    $st->bind_param('si', $status, $id);
    $ok = $st->execute();
    $st->close();
    echo json_encode(['success' => $ok]);
    exit;
}

if ($action === 'delete') {
    $id = (int) ($input['id'] ?? 0);
    if ($id < 1) {
        echo json_encode(['success' => false, 'message' => 'Invalid id']);
        exit;
    }
    $p = $mysqli->prepare('DELETE FROM payments WHERE booking_id = ?');
    $p->bind_param('i', $id);
    $p->execute();
    $p->close();
    $b = $mysqli->prepare('DELETE FROM bookings WHERE id = ?');
    $b->bind_param('i', $id);
    $ok = $b->execute();
    $b->close();
    echo json_encode(['success' => $ok]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Unknown action']);
