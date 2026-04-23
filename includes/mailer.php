<?php

/**
 * Mail helper (SMTP via PHPMailer; fallback to log).
 *
 * Configure SMTP_* constants in includes/config.php.
 * If sending fails, email content is written to storage/mail.log.
 */
function hotel_send_mail(string $to, string $subject, string $html, string $text = ''): bool
{
    $sent = false;

    $smtpUser = defined('SMTP_USER') ? (string) SMTP_USER : '';
    $smtpPass = defined('SMTP_PASS') ? (string) SMTP_PASS : '';
    $smtpHost = defined('SMTP_HOST') ? (string) SMTP_HOST : '';
    $smtpPort = defined('SMTP_PORT') ? (int) SMTP_PORT : 0;
    $smtpSecure = defined('SMTP_SECURE') ? (string) SMTP_SECURE : 'tls';
    $fromEmail = defined('SMTP_FROM_EMAIL') ? (string) SMTP_FROM_EMAIL : '';
    $fromName = defined('SMTP_FROM_NAME') ? (string) SMTP_FROM_NAME : 'Imperial Crown Hotel';

    if ($fromEmail === '') {
        $fromEmail = $smtpUser;
    }

    $smtpConfigured = ($smtpUser !== '' && $smtpPass !== '' && $smtpHost !== '' && $smtpPort > 0);
    if ($smtpConfigured) {
        $autoload = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
        if (is_file($autoload)) {
            require_once $autoload;
            try {
                $mail = new PHPMailer\PHPMailer\PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = $smtpHost;
                $mail->SMTPAuth = true;
                $mail->Username = $smtpUser;
                $mail->Password = $smtpPass;
                $mail->Port = $smtpPort;
                $mail->SMTPSecure = ($smtpSecure === 'ssl')
                    ? PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS
                    : PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;

                $mail->CharSet = 'UTF-8';
                $mail->setFrom($fromEmail, $fromName);
                $mail->addReplyTo($fromEmail, $fromName);
                $mail->Sender = $fromEmail;
                $mail->addAddress($to);
                $mail->Subject = $subject;
                $mail->isHTML(true);
                $mail->Body = $html;
                $mail->AltBody = $text !== '' ? $text : strip_tags($html);

                $sent = $mail->send();
            } catch (Throwable $e) {
                $sent = false;
                error_log("PHPMailer Exception: " . $e->getMessage(), 3, dirname(__DIR__) . '/storage/mail_error.log');
            }
        }
    }

    if ($sent) {
        return true;
    }

    $root = dirname(__DIR__);
    $dir = $root . DIRECTORY_SEPARATOR . 'storage';
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
    $log = $dir . DIRECTORY_SEPARATOR . 'mail.log';
    $line = "----\n" .
        'To: ' . $to . "\n" .
        'Subject: ' . $subject . "\n" .
        "Text:\n" . ($text !== '' ? $text : strip_tags($html)) . "\n" .
        "HTML:\n" . $html . "\n";
    @file_put_contents($log, $line, FILE_APPEND);

    return false;
}

