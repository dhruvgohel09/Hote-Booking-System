<?php
require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/includes/mailer.php';

$sent = hotel_send_mail('test@example.com', 'Test Subject', '<p>Test HTML</p>');
echo "hotel_send_mail returned: " . ($sent ? 'true' : 'false') . "\n";
