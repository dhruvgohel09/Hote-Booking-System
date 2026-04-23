<?php

/**
 * @return array<string, mixed>|null
 */
function hotel_get_bill_data(mysqli $mysqli, int $bookingId): ?array
{
    $sql = 'SELECT b.id, b.user_id, b.bill_number, b.check_in, b.check_out, b.total_price, b.status AS booking_status,
            b.guests, b.created_at,
            u.first_name, u.last_name, u.email, u.phone, u.address, u.city, u.state, u.pincode,
            r.room_name, r.room_type, r.price AS nightly_rate
            FROM bookings b
            INNER JOIN users u ON u.id = b.user_id
            INNER JOIN rooms r ON r.id = b.room_id
            WHERE b.id = ? LIMIT 1';
    $st = $mysqli->prepare($sql);
    $st->bind_param('i', $bookingId);
    $st->execute();
    $row = $st->get_result()->fetch_assoc();
    $st->close();
    if (!$row) {
        return null;
    }

    $in = new DateTime($row['check_in']);
    $out = new DateTime($row['check_out']);
    $nights = max(1, (int) $in->diff($out)->days);
    $row['nights'] = $nights;
    $row['guests'] = isset($row['guests']) ? (int) $row['guests'] : 2;
    $row['customer_name'] = trim($row['first_name'] . ' ' . $row['last_name']);
    $row['bill_number'] = $row['bill_number'] ?: ('INV-' . date('Y', strtotime($row['created_at'])) . '-' . str_pad((string) $row['id'], 6, '0', STR_PAD_LEFT));

    $paySt = $mysqli->prepare('SELECT amount, payment_method, payment_status, payment_date FROM payments WHERE booking_id = ? LIMIT 1');
    $paySt->bind_param('i', $bookingId);
    $paySt->execute();
    $pay = $paySt->get_result()->fetch_assoc();
    $paySt->close();
    $row['payment'] = $pay ?: null;

    return $row;
}

function hotel_format_inr(float $n): string
{
    return '₹' . number_format($n, 2);
}
