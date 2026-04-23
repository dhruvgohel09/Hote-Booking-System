<?php
require_once __DIR__ . '/includes/init.php';

$token = isset($_GET['token']) ? (string) $_GET['token'] : '';

function h(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

$status = 'invalid';
$message = 'Invalid or expired verification link.';

if ($token !== '') {
    $tokenHash = hash('sha256', $token);

    $sql = '
        SELECT ev.id AS token_id, ev.user_id, ev.expires_at, ev.used_at
        FROM email_verifications ev
        WHERE ev.token_hash = ?
          AND ev.used_at IS NULL
          AND ev.expires_at > NOW()
        LIMIT 1
    ';

    $st = $mysqli->prepare($sql);
    $st->bind_param('s', $tokenHash);
    $st->execute();
    $row = $st->get_result()->fetch_assoc();
    $st->close();

    if ($row && isset($row['user_id'])) {
        $userId = (int) $row['user_id'];
        $tokenId = (int) $row['token_id'];

        $mysqli->begin_transaction();
        try {
            $up = $mysqli->prepare('UPDATE users SET is_active = 1 WHERE id = ? LIMIT 1');
            $up->bind_param('i', $userId);
            $up->execute();
            $up->close();

            $mark = $mysqli->prepare('UPDATE email_verifications SET used_at = NOW() WHERE id = ? LIMIT 1');
            $mark->bind_param('i', $tokenId);
            $mark->execute();
            $mark->close();

            $mysqli->commit();
            $status = 'verified';
            $message = 'Your email has been verified. You can now log in.';
        } catch (Throwable $e) {
            $mysqli->rollback();
            $status = 'error';
            $message = 'Verification failed. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:linear-gradient(135deg,#667eea 0%, #764ba2 100%);min-height:100vh;">
    <div class="container" style="max-width:560px;margin-top:80px;">
        <div class="card" style="border:none;border-radius:16px;box-shadow:0 10px 30px rgba(0,0,0,0.2);">
            <div class="card-header" style="background:linear-gradient(135deg,#2c3e50 0%, #3498db 100%);color:#fff;border-radius:16px 16px 0 0;">
                <h4 class="mb-0">
                    <?php echo $status === 'verified' ? 'Verified' : 'Email Verification'; ?>
                </h4>
            </div>
            <div class="card-body p-4">
                <div class="alert <?php echo $status === 'verified' ? 'alert-success' : 'alert-danger'; ?>" role="alert">
                    <?php echo h($message); ?>
                </div>

                <?php if ($status === 'verified'): ?>
                    <a href="login.php" class="btn btn-primary w-100">Go to Login</a>
                <?php else: ?>
                    <a href="register.php" class="btn btn-secondary w-100 mb-2">Back to Register</a>
                    <a href="login.php" class="btn btn-primary w-100">Go to Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>

