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

$defaultImg = 'https://images.pexels.com/photos/271624/pexels-photo-271624.jpeg?auto=compress&cs=tinysrgb&w=600';

if ($action === 'create') {
    $name = trim($input['room_name'] ?? '');
    $type = trim($input['room_type'] ?? '');
    $price = (float) ($input['price'] ?? 0);
    $capacity = (int) ($input['capacity'] ?? 2);
    $description = trim($input['description'] ?? '');
    $image = trim($input['image'] ?? '') ?: $defaultImg;
    $status = $input['status'] ?? 'available';
    if ($name === '' || $type === '' || $price <= 0) {
        echo json_encode(['success' => false, 'message' => 'Missing fields']);
        exit;
    }
    if (!in_array($status, ['available', 'booked', 'maintenance'], true)) {
        $status = 'available';
    }
    $st = $mysqli->prepare(
        'INSERT INTO rooms (room_name, room_type, price, capacity, description, image, status) VALUES (?,?,?,?,?,?,?)'
    );
    $st->bind_param('ssdisss', $name, $type, $price, $capacity, $description, $image, $status);
    $ok = $st->execute();
    $newId = $ok ? $st->insert_id : 0;
    $st->close();
    echo json_encode(['success' => $ok, 'id' => $newId]);
    exit;
}

if ($action === 'update') {
    $id = (int) ($input['id'] ?? 0);
    $name = trim($input['room_name'] ?? '');
    $type = trim($input['room_type'] ?? '');
    $price = (float) ($input['price'] ?? 0);
    $capacity = (int) ($input['capacity'] ?? 2);
    $description = trim($input['description'] ?? '');
    $image = trim($input['image'] ?? '') ?: $defaultImg;
    $status = $input['status'] ?? 'available';
    if ($id < 1 || $name === '' || $type === '' || $price <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid data']);
        exit;
    }
    if (!in_array($status, ['available', 'booked', 'maintenance'], true)) {
        $status = 'available';
    }
    $st = $mysqli->prepare(
        'UPDATE rooms SET room_name = ?, room_type = ?, price = ?, capacity = ?, description = ?, image = ?, status = ? WHERE id = ?'
    );
    $st->bind_param('ssdisssi', $name, $type, $price, $capacity, $description, $image, $status, $id);
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
    $st = $mysqli->prepare('DELETE FROM rooms WHERE id = ?');
    $st->bind_param('i', $id);
    $ok = $st->execute();
    $st->close();
    echo json_encode(['success' => $ok]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Unknown action']);
