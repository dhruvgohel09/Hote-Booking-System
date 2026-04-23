<?php
require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/includes/mailer.php';

$sent = hotel_send_mail('db2672329@gmail.com', 'Test Subject', '<p>Test HTML</p>');
echo "hotel_send_mail returned: " . ($sent ? 'true' : 'false') . "\n";
if (file_exists(__DIR__ . '/storage/mail_error.log')) {
    echo "Error log:\n" . file_get_contents(__DIR__ . '/storage/mail_error.log');
}
