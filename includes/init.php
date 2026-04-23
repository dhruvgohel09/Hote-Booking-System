<?php
require_once __DIR__ . '/config.php';

$sessionLifetime = max(300, (int) SESSION_LIFETIME);
ini_set('session.gc_maxlifetime', (string) $sessionLifetime);

session_set_cookie_params([
    'lifetime' => $sessionLifetime,
    'path' => '/',
    'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
    'httponly' => true,
    'samesite' => 'Lax',
]);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';

$GLOBAL_SETTINGS = [];
$settingsFile = __DIR__ . '/settings.json';
if (file_exists($settingsFile)) {
    $GLOBAL_SETTINGS = json_decode(file_get_contents($settingsFile), true) ?: [];
}

$site_name = !empty($GLOBAL_SETTINGS['site_title']) ? $GLOBAL_SETTINGS['site_title'] : 'The Imperial Crown Hotel';
$script = basename($_SERVER['SCRIPT_NAME'] ?? '');
$requestUri = $_SERVER['REQUEST_URI'] ?? '';
$isApi = strpos($requestUri, '/api/') !== false;

if (!empty($_SESSION['user_id'])) {
    $now = time();
    $idleExpired = isset($_SESSION['last_activity']) && ($now - (int) $_SESSION['last_activity'] > $sessionLifetime);

    if ($idleExpired) {
        $_SESSION = [];
        session_destroy();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($isApi) {
            http_response_code(401);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => false,
                'message' => 'Session expired. Please login again.',
                'session_expired' => true,
            ]);
            exit;
        }

        if ($script !== 'login.php' && $script !== 'register.php') {
            header('Location: login.php?expired=1');
            exit;
        }
    } else {
        $_SESSION['last_activity'] = $now;
    }
}
