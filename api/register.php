<?php
header('Content-Type: application/json; charset=utf-8');
require_once dirname(__DIR__) . '/includes/init.php';
require_once dirname(__DIR__) . '/includes/validation.php';
require_once dirname(__DIR__) . '/includes/mailer.php';

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

$first = trim($data['first_name'] ?? '');
$last = trim($data['last_name'] ?? '');
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';
$phone = preg_replace('/\D/', '', $data['phone'] ?? '');
$address = trim($data['address'] ?? '');
$city = trim($data['city'] ?? '');
$state = trim($data['state'] ?? '');
$pincode = trim($data['pincode'] ?? '');

if ($first === '' || $last === '' || $email === '') {
    echo json_encode(['success' => false, 'message' => 'First name, last name and email are required.']);
    exit;
}

if (!hotel_valid_first_name($first) || !hotel_valid_last_name($last)) {
    echo json_encode(['success' => false, 'message' => 'Enter a valid first name (letters) and last name (letters, optional spaces).']);
    exit;
}

if (!hotel_valid_email($email)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

if (!hotel_valid_register_password($password)) {
    echo json_encode(['success' => false, 'message' => 'Password must be 8–128 characters and include at least one letter and one number.']);
    exit;
}

if (!hotel_valid_phone_in($phone)) {
    echo json_encode(['success' => false, 'message' => 'Enter a valid 10-digit phone number.']);
    exit;
}

if (!hotel_valid_pincode($pincode)) {
    echo json_encode(['success' => false, 'message' => 'Pincode must be 6 digits or left empty.']);
    exit;
}

$check = $mysqli->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
$check->bind_param('s', $email);
$check->execute();
if ($check->get_result()->fetch_assoc()) {
    $check->close();
    echo json_encode(['success' => false, 'message' => 'This email is already registered.']);
    exit;
}
$check->close();

$hash = password_hash($password, PASSWORD_DEFAULT);

$token = bin2hex(random_bytes(32)); // 64-char url-safe token
$tokenHash = hash('sha256', $token);
$expiresAt = date('Y-m-d H:i:s', time() + (24 * 60 * 60)); // 24 hours

$stmt = $mysqli->prepare(
    'INSERT INTO users (first_name, last_name, email, password, phone, address, city, state, pincode, role, is_active) VALUES (?,?,?,?,?,?,?,?,? ,\'user\',1)'
);
$stmt->bind_param(
    'sssssssss',
    $first,
    $last,
    $email,
    $hash,
    $phone,
    $address,
    $city,
    $state,
    $pincode,
);
if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Registration failed. Please try again.']);
    exit;
}

$userId = (int) $stmt->insert_id;
$stmt->close();

// Store email verification token (hashed).
$ver = $mysqli->prepare(
    'INSERT INTO email_verifications (user_id, email, token_hash, expires_at) VALUES (?,?,?,?)'
);
$ver->bind_param('isss', $userId, $email, $tokenHash, $expiresAt);
$okToken = $ver->execute();
$ver->close();

if (!$okToken) {
    echo json_encode(['success' => false, 'message' => 'Registration failed while creating verification token. Please try again.']);
    exit;
}

$basePath = rtrim(str_replace('\\', '/', dirname(dirname($_SERVER['SCRIPT_NAME']))), '/');
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$verifyUrl = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? '') . $basePath . '/verify_email.php?token=' . urlencode($token);

$subject = 'Verify your account - Imperial Crown Hotel';
$text = "Click the verification button to activate your account.\n\nRegistration Email: {$email}\n\nVerification link: {$verifyUrl}\n";
$html = '
<div style="font-family:Segoe UI,Arial,sans-serif;line-height:1.5;background:linear-gradient(135deg,#667eea 0%, #764ba2 100%);padding:24px;border-radius:14px;color:#fff">
  <h2 style="margin:0 0 10px">Verify Your Email</h2>
  <p style="margin:0 0 18px;color:rgba(255,255,255,0.92)">
    Thanks for registering with The Imperial Crown Hotel.
    Please click the button below to activate your account.
  </p>
  <p style="margin:0 0 18px;color:rgba(255,255,255,0.92)">
    <strong>Registration Email:</strong> ' . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . '
  </p>
  <a href="' . htmlspecialchars($verifyUrl, ENT_QUOTES, 'UTF-8') . '"
     style="display:inline-block;text-decoration:none;background:#3498db;color:#fff;padding:12px 22px;border-radius:10px;font-weight:700">
    Verify Account
  </a>
  <p style="margin:16px 0 0;color:rgba(255,255,255,0.9);font-size:13px">
    If the button doesn\'t work, copy and paste this link in your browser:
    <br><span style="word-break:break-all">' . htmlspecialchars($verifyUrl, ENT_QUOTES, 'UTF-8') . '</span>
  </p>
</div>';

$sent = hotel_send_mail($email, $subject, $html, $text);

$msg = 'Registration successful! You can now login.';

echo json_encode([
    'success' => true,
    'message' => $msg,
    'requires_verification' => false,
    'verification_sent' => false,
]);
