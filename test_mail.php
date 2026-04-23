<?php
require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/vendor/autoload.php';

$smtpUser = defined('SMTP_USER') ? (string) SMTP_USER : '';
$smtpPass = defined('SMTP_PASS') ? (string) SMTP_PASS : '';
$smtpHost = defined('SMTP_HOST') ? (string) SMTP_HOST : '';
$smtpPort = defined('SMTP_PORT') ? (int) SMTP_PORT : 0;
$smtpSecure = defined('SMTP_SECURE') ? (string) SMTP_SECURE : 'tls';

echo "Host: $smtpHost\n";
echo "Port: $smtpPort\n";
echo "User: $smtpUser\n";

try {
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    $mail->SMTPDebug = 3;
    $mail->Debugoutput = 'html';
    $mail->isSMTP();
    $mail->Host = $smtpHost;
    $mail->SMTPAuth = true;
    $mail->Username = $smtpUser;
    $mail->Password = $smtpPass;
    $mail->Port = $smtpPort;
    $mail->SMTPSecure = ($smtpSecure === 'ssl') ? PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS : PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
    $mail->setFrom($smtpUser, 'Test');
    $mail->addAddress('test@example.com');
    $mail->Subject = 'Test';
    $mail->Body = 'Test';
    $mail->send();
    echo "Sent successfully\n";
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo "Mailer Error: " . $mail->ErrorInfo . "\n";
}
