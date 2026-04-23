<?php
header('Content-Type: application/json; charset=utf-8');
require_once dirname(__DIR__) . '/includes/init.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') === 'admin') {
    echo json_encode(['success' => false, 'message' => 'Please log in as a guest user to book.']);
    exit;
}

$razorpayKeyId = defined('RAZORPAY_KEY_ID') ? RAZORPAY_KEY_ID : '';

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!is_array($data)) {
    $data = $_POST;
}

$room_id = (int) ($data['room_id'] ?? 0);
$check_in = $data['check_in'] ?? '';
$check_out = $data['check_out'] ?? '';
$guests = (int) ($data['guests'] ?? 2);
$extra_persons = (int) ($data['extra_persons'] ?? 0);
$extra_charge_per_night = (float) ($data['extra_charge_per_night'] ?? 500);

if ($room_id < 1 || $check_in === '' || $check_out === '') {
    echo json_encode(['success' => false, 'message' => 'Missing booking details.']);
    exit;
}

$in = DateTime::createFromFormat('Y-m-d', $check_in);
$out = DateTime::createFromFormat('Y-m-d', $check_out);
if (!$in || !$out || $out <= $in) {
    echo json_encode(['success' => false, 'message' => 'Invalid dates.']);
    exit;
}

$stmt = $mysqli->prepare('SELECT id, price, status, capacity FROM rooms WHERE id = ? LIMIT 1');
$stmt->bind_param('i', $room_id);
$stmt->execute();
$room = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$room || $room['status'] !== 'available') {
    echo json_encode(['success' => false, 'message' => 'This room is not available for booking.']);
    exit;
}

// Check for double booking collision
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

if ($guests > (int) $room['capacity']) {
    echo json_encode(['success' => false, 'message' => 'Guest count exceeds room capacity.']);
    exit;
}

$nights = (int) $in->diff($out)->days;
$expected = $nights * (float) $room['price'];

// Fetch active discount offer
$discount_percentage = 0;
$offer_q = $mysqli->query("SELECT discount_percentage, target_rooms FROM offers WHERE status='active'");
if ($offer_q) {
    while ($offer_row = $offer_q->fetch_assoc()) {
        $targets = $offer_row['target_rooms'];
        if (empty($targets) || in_array((string)$room_id, explode(',', $targets))) {
            $discount_percentage = (int)$offer_row['discount_percentage'];
            break;
        }
    }
}

if ($discount_percentage > 0) {
    $discount_amount = ($expected * $discount_percentage) / 100;
    $expected -= $discount_amount;
}

// Optional UI feature: extra persons charged at a fixed rate per night.
if ($extra_persons > 0) {
    if ($extra_charge_per_night < 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid extra charge.']);
        exit;
    }
    $expected += $extra_persons * $extra_charge_per_night * $nights;
}
$declared = isset($data['total_price']) ? (float) $data['total_price'] : $expected;
if (abs($declared - $expected) > 0.02) {
    echo json_encode(['success' => false, 'message' => 'Price mismatch. Please refresh and try again.']);
    exit;
}

$user_id = (int) $_SESSION['user_id'];
$total = round($expected, 2);
$status = 'confirmed';

$ins = $mysqli->prepare(
    'INSERT INTO bookings (user_id, room_id, guests, check_in, check_out, total_price, status) VALUES (?,?,?,?,?,?,?)'
);
$ins->bind_param('iiissds', $user_id, $room_id, $guests, $check_in, $check_out, $total, $status);
if (!$ins->execute()) {
    $ins->close();
    echo json_encode(['success' => false, 'message' => 'Could not create booking.']);
    exit;
}
$booking_id = $ins->insert_id;
$ins->close();

$bill_number = 'INV-' . date('Y') . '-' . str_pad((string) $booking_id, 6, '0', STR_PAD_LEFT);
$bn = $mysqli->prepare('UPDATE bookings SET bill_number = ? WHERE id = ?');
$bn->bind_param('si', $bill_number, $booking_id);
$bn->execute();
$bn->close();

$pay = $mysqli->prepare('INSERT INTO payments (booking_id, amount, payment_method, payment_status, transaction_id) VALUES (?,?,?,?,?)');
$pm = $data['payment_method'] ?? 'cash';
$razorpayData = $data['razorpay_payment_id'] ?? '';
$ps = ($pm === 'online' && !empty($razorpayData)) ? 'paid' : 'unpaid';
$pay->bind_param('idsss', $booking_id, $total, $pm, $ps, $razorpayData);
$pay->execute();
$pay->close();

// Both methods should route to success to offer feedback
$redirectTarget = 'payment_success.php?booking_id=' . $booking_id;

echo json_encode([
    'success' => true,
    'message' => 'Booking created.',
    'booking_id' => $booking_id,
    'bill_number' => $bill_number,
    'redirect' => $redirectTarget,
    'bill_url' => 'bill.php?id=' . $booking_id,
]);
exit;
