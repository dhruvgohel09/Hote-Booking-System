<?php
/**
 * Database configuration — adjust for your environment (Laragon default shown).
 */
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'hotelbooking');

/** Idle session timeout (seconds). After this period of inactivity, user must log in again. */
define('SESSION_LIFETIME', 3600);

/**
 * SMTP (Gmail App Password)
 *
 * How to use:
 * - Turn ON 2-Step Verification in your Google account
 * - Create an App Password (Mail) and paste it below as SMTP_PASS
 *
 * Note: This password is NOT your normal Gmail password.
 */
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587); // 587 (TLS) or 465 (SSL)
define('SMTP_SECURE', 'tls'); // 'tls' or 'ssl'
define('SMTP_USER', 'bhadarakaparth7@gmail.com'); // example: yourname@gmail.com
define('SMTP_PASS', 'ogpz hclv jsjc dlhi'); // example: xxxx xxxx xxxx xxxx (Google App Password)
define('SMTP_FROM_EMAIL', 'bhadarakaparth7@gmail.com'); // usually same as SMTP_USER
define('SMTP_FROM_NAME', 'Imperial Crown Hotel');

/**
 * Razorpay Configuration
 */
define('RAZORPAY_KEY_ID', 'rzp_test_Se3pvENqKoBPoJ');      // Tamari navi test key id
define('RAZORPAY_KEY_SECRET', 'rW28Al8f2fmZAfZ7L56eOgVt');     // Tamaro navo test secret