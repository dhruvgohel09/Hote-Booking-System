<?php

function hotel_normalize_email(?string $email): string
{
    $email = (string) $email;
    $email = preg_replace('/[\x00-\x1F\x7F\x{00A0}\s]+/u', '', $email) ?? '';

    return strtolower(trim($email));
}

function hotel_valid_email(string $email): bool
{
    $email = hotel_normalize_email($email);

    return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
}

/** Login: reasonable length only (hash verified server-side). */
function hotel_valid_login_password(string $password): bool
{
    $len = strlen($password);
    return $len >= 1 && $len <= 128;
}

/** Register: min 8 chars, at least one letter and one number. */
function hotel_valid_register_password(string $password): bool
{
    if (strlen($password) < 8 || strlen($password) > 128) {
        return false;
    }
    return (bool) preg_match('/[A-Za-z]/', $password) && (bool) preg_match('/[0-9]/', $password);
}

function hotel_valid_first_name(string $name): bool
{
    $name = trim($name);

    return (bool) preg_match('/^[A-Za-z]{2,50}$/', $name);
}

function hotel_valid_last_name(string $name): bool
{
    $name = trim($name);
    if (strlen($name) < 2 || strlen($name) > 60) {
        return false;
    }

    return (bool) preg_match('/^[A-Za-z]+(?: [A-Za-z]+)*$/', $name);
}

function hotel_valid_phone_in(string $digits): bool
{
    return strlen($digits) === 10 && ctype_digit($digits);
}

function hotel_valid_pincode(?string $pin): bool
{
    if ($pin === null || $pin === '') {
        return true;
    }
    return (bool) preg_match('/^[0-9]{6}$/', $pin);
}
