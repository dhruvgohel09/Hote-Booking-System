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

$email = hotel_normalize_email($data['email'] ?? '');
if ($email === '' || !hotel_valid_email($email)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

// Standard message if email exists
$genericMsg = 'We sent an OTP to your email to reset your password.';

// Find user.
$st = $mysqli->prepare('SELECT id, email FROM users WHERE LOWER(email) = ? LIMIT 1');
$lookupEmail = strtolower($email);
$st->bind_param('s', $lookupEmail);
$st->execute();
$user = $st->get_result()->fetch_assoc();
$st->close();

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'Email address not found in our records.']);
    exit;
}

$userId = (int)$user['id'];
$otpResendCooldownSeconds = 30;

// Rate limit: allow one active OTP per 30 seconds.
$rate = $mysqli->prepare('SELECT TIMESTAMPDIFF(SECOND, created_at, NOW()) as elapsed FROM password_resets WHERE user_id = ? ORDER BY id DESC LIMIT 1');
$rate->bind_param('i', $userId);
$rate->execute();
$row = $rate->get_result()->fetch_assoc();
$rate->close();

if ($row !== null && isset($row['elapsed'])) {
    $elapsed = (int)$row['elapsed'];
    $remaining = max(0, $otpResendCooldownSeconds - $elapsed);
    if ($remaining > 0) {
        echo json_encode([
            'success' => true,
            'message' => "OTP already sent recently. Please wait {$remaining} seconds and try resend.",
            'resend_wait_seconds' => (int)$remaining
        ]);
        exit;
    }
}

// Create OTP.
$otpExpiryMinutes = 30;
$otp = (string)random_int(100000, 999999);
$otpHash = password_hash($otp, PASSWORD_DEFAULT);

// Invalidate previous unused tokens (optional cleanup).
$clear = $mysqli->prepare('UPDATE password_resets SET used_at = NOW() WHERE user_id = ? AND used_at IS NULL');
$clear->bind_param('i', $userId);
$clear->execute();
$clear->close();

$ins = $mysqli->prepare('INSERT INTO password_resets (user_id, email, otp_hash, expires_at) VALUES (?,?,?, DATE_ADD(NOW(), INTERVAL ? MINUTE))');
$ins->bind_param('issi', $userId, $email, $otpHash, $otpExpiryMinutes);
$ins->execute();
$ins->close();

$basePath = rtrim(str_replace('\\', '/', dirname(dirname($_SERVER['SCRIPT_NAME']))), '/');
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$resetUrl = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? '') . $basePath . '/reset_password.php?email=' . urlencode($email);

$subject = 'Reset password OTP - Imperial Crown Hotel';
$text = "Your OTP to reset password is: {$otp}\nOTP expires in {$otpExpiryMinutes} minutes.\nSet new password: {$resetUrl}\nIf you did not request, ignore this email.";
$html = '
<div style="font-family:Segoe UI,Arial,sans-serif;line-height:1.5;background:linear-gradient(135deg,#667eea 0%, #764ba2 100%);padding:24px;border-radius:14px;color:#fff">
  <h2 style="margin:0 0 10px">Password Reset</h2>
  <p style="margin:0 0 18px;color:rgba(255,255,255,0.92)">
    We received a request to reset your password.
    Use the OTP below (expires in ' . (int) $otpExpiryMinutes . ' minutes).
  </p>

  <div style="font-size:28px;font-weight:800;letter-spacing:6px;margin:14px 0;color:#0b1020;background:rgba(255,255,255,0.95);padding:14px 18px;border-radius:12px;display:inline-block">
    ' . htmlspecialchars($otp) . '
  </div>

  <div style="margin-top:18px">
    <a href="' . htmlspecialchars($resetUrl, ENT_QUOTES, 'UTF-8') . '"
       style="display:inline-block;text-decoration:none;background:#3498db;color:#fff;padding:12px 22px;border-radius:10px;font-weight:700">
      Set New Password
    </a>
  </div>

  <p style="margin:16px 0 0;color:rgba(255,255,255,0.85);font-size:13px">
    If you did not request this, ignore this email.
  </p>
</div>';

$sent = hotel_send_mail($email, $subject, $html, $text);

$resp = [
    'success' => true,
    'message' => $genericMsg,
    'mail_sent' => (bool)$sent
];

// Helpful for local dev/testing: show OTP when running on localhost.
if (!$sent && stripos((string)($_SERVER['HTTP_HOST'] ?? ''), 'localhost') !== false) {
    $resp['debug_otp'] = $otp;
    $resp['debug_note'] = 'Mail not configured. OTP logged in storage/mail.log';
    $resp['message'] = 'Mail delivery failed in local server. Use the OTP shown below for testing.';
}

echo json_encode($resp);
exit;

