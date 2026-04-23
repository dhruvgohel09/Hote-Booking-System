<?php

function hotel_room_features(string $t): array
{
    $t = strtolower($t);
    $map = [
        'simple' => ['bedroom'],
        'delux' => ['bedroom', 'balcony'],
        'luxury' => ['bedroom', 'balcony', 'kitchen'],
        'executive' => ['bedroom', 'balcony', 'kitchen'],
    ];
    return $map[$t] ?? ['bedroom'];
}

function hotel_room_facilities(string $t): array
{
    $t = strtolower($t);
    $map = [
        'simple' => ['Wifi', 'Television'],
        'delux' => ['Geyser', 'Television', 'Wifi'],
        'luxury' => ['Geyser', 'Television', 'Wifi', 'AC'],
        'executive' => ['Geyser', 'Television', 'Wifi', 'AC', 'Room Heater', 'Spa'],
    ];
    return $map[$t] ?? ['Wifi'];
}

/**
 * @return array<int, array<string, mixed>>
 */
function hotel_rooms_for_js(mysqli $mysqli, string $check_in = '', string $check_out = ''): array
{
    $query = 'SELECT id, room_name, room_type, price, capacity, description, image, status FROM rooms WHERE status="available"';

    // If dates are provided, exclude rooms that are booked during this period.
    if (!empty($check_in) && !empty($check_out)) {
        $query .= " AND id NOT IN (
            SELECT room_id FROM bookings 
            WHERE status != 'cancelled' 
            AND check_in < '" . $mysqli->real_escape_string($check_out) . "' 
            AND check_out > '" . $mysqli->real_escape_string($check_in) . "'
        )";
    }
    
    $query .= ' ORDER BY id ASC';
    $res = $mysqli->query($query);
    if (!$res) {
        return [];
    }
    $out = [];
    $fallbackImg = 'https://images.pexels.com/photos/271624/pexels-photo-271624.jpeg?auto=compress&cs=tinysrgb&w=600';
    while ($row = $res->fetch_assoc()) {
        $img = trim((string) $row['image']);
        if ($img === '' || strncmp($img, 'http', 4) !== 0) {
            $img = $fallbackImg;
        }
        $out[] = [
            'id' => (int) $row['id'],
            'name' => $row['room_name'],
            'price' => (float) $row['price'],
            'adult' => (int) $row['capacity'],
            'children' => 2,
            'features' => hotel_room_features((string) $row['room_type']),
            'facilities' => hotel_room_facilities((string) $row['room_type']),
            'image' => $img,
            'type' => strtolower((string) $row['room_type']),
            'status' => $row['status'],
        ];
    }
    return $out;
}
