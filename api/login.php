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

$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

if ($email === '' || $password === '') {
    echo json_encode(['success' => false, 'message' => 'Email and password are required.']);
    exit;
}

if (!hotel_valid_email($email)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

if (!hotel_valid_login_password($password)) {
    echo json_encode(['success' => false, 'message' => 'Invalid password length.']);
    exit;
}

$stmt = $mysqli->prepare('SELECT id, first_name, last_name, email, password, role, is_active FROM users WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();
$stmt->close();

if (!$user || !password_verify($password, $user['password'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
    exit;
}

if (!((int)($user['is_active'] ?? 0) === 1)) {
    echo json_encode(['success' => false, 'message' => 'Please verify your email first to activate your account.']);
    exit;
}

$_SESSION['user_id'] = (int) $user['id'];
$_SESSION['user_name'] = trim($user['first_name'] . ' ' . $user['last_name']);
$_SESSION['user_email'] = $user['email'];
$_SESSION['user_role'] = $user['role'];
$_SESSION['user_logged_in'] = true;
$_SESSION['last_activity'] = time();

$redirect = 'index.php';
if ($user['role'] === 'admin') {
    $redirect = 'admin/index.php';
}

echo json_encode([
    'success' => true,
    'message' => 'Login successful.',
    'redirect' => $redirect,
    'user' => [
        'name' => $_SESSION['user_name'],
        'email' => $user['email'],
        'role' => $user['role'],
    ],
]);
exit;
