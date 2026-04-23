<?php
header('Content-Type: application/json; charset=utf-8');
require_once dirname(__DIR__) . '/includes/init.php';
require_once dirname(__DIR__) . '/includes/validation.php';

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

$email = hotel_normalize_email($data['email'] ?? '');
$otp = trim((string)($data['otp'] ?? ''));
$newPassword = (string)($data['new_password'] ?? '');

if ($email === '' || !hotel_valid_email($email)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email.']);
    exit;
}

if ($otp === '' || !ctype_digit($otp) || strlen($otp) !== 6) {
    echo json_encode(['success' => false, 'message' => 'Invalid OTP.']);
    exit;
}

if (!hotel_valid_register_password($newPassword)) {
    echo json_encode(['success' => false, 'message' => 'Invalid new password.']);
    exit;
}

// Fetch user id.
$st = $mysqli->prepare('SELECT id FROM users WHERE LOWER(email) = ? LIMIT 1');
$lookupEmail = strtolower($email);
$st->bind_param('s', $lookupEmail);
$st->execute();
$user = $st->get_result()->fetch_assoc();
$st->close();

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'Invalid email or OTP.']);
    exit;
}
$userId = (int)$user['id'];

// Fetch latest active token.
$tok = $mysqli->prepare('
    SELECT id, otp_hash, expires_at, used_at, attempts
    FROM password_resets
    WHERE user_id = ?
      AND used_at IS NULL
      AND expires_at > NOW()
    ORDER BY id DESC
    LIMIT 1
');
$tok->bind_param('i', $userId);
$tok->execute();
$tokenRow = $tok->get_result()->fetch_assoc();
$tok->close();

if (!$tokenRow) {
    echo json_encode(['success' => false, 'message' => 'OTP expired/used. Please request a new OTP (valid for 30 minutes).']);
    exit;
}

$tokenId = (int)($tokenRow['id'] ?? 0);
$otpHash = (string)($tokenRow['otp_hash'] ?? '');

if (!password_verify($otp, $otpHash)) {
    $attempts = (int)($tokenRow['attempts'] ?? 0) + 1;
    $upd = $mysqli->prepare('UPDATE password_resets SET attempts = ? WHERE id = ?');
    $upd->bind_param('ii', $attempts, $tokenId);
    $upd->execute();
    $upd->close();

    if ($attempts >= 5) {
        $mark = $mysqli->prepare('UPDATE password_resets SET used_at = NOW() WHERE id = ? LIMIT 1');
        $mark->bind_param('i', $tokenId);
        $mark->execute();
        $mark->close();
        echo json_encode(['success' => false, 'message' => 'Too many invalid attempts. Please request a new OTP.']);
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'Invalid OTP.']);
    exit;
}

// Update password and mark token used.
$hash = password_hash($newPassword, PASSWORD_DEFAULT);

$mysqli->begin_transaction();
try {
    $upUser = $mysqli->prepare('UPDATE users SET password = ? WHERE id = ? LIMIT 1');
    $upUser->bind_param('si', $hash, $userId);
    $upUser->execute();
    $upUser->close();

    $mark = $mysqli->prepare('UPDATE password_resets SET used_at = NOW() WHERE id = ? LIMIT 1');
    $mark->bind_param('i', $tokenId);
    $mark->execute();
    $mark->close();

    $mysqli->commit();
} catch (Throwable $e) {
    $mysqli->rollback();
    echo json_encode(['success' => false, 'message' => 'Password update failed. Please try again.']);
    exit;
}

echo json_encode([
    'success' => true,
    'message' => 'Password updated successfully.',
    'redirect' => 'login.php',
]);
exit;

