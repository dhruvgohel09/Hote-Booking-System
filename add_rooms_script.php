<?php
require_once __DIR__ . '/includes/init.php';

$rooms = [
    [
        'name' => 'Premium Suite 401',
        'type' => 'luxury',
        'price' => 1500,
        'capacity' => 2,
        'desc' => 'A beautifully appointed premium suite with panoramic city views and exclusive amenities.',
        'image' => 'https://images.pexels.com/photos/164595/pexels-photo-164595.jpeg?auto=compress&cs=tinysrgb&w=600'
    ],
    [
        'name' => 'Family Suite 405',
        'type' => 'delux',
        'price' => 1000,
        'capacity' => 4,
        'desc' => 'Spacious family suite with two bedrooms and a comfortable living area.',
        'image' => 'https://images.pexels.com/photos/271618/pexels-photo-271618.jpeg?auto=compress&cs=tinysrgb&w=600'
    ],
    [
        'name' => 'Standard Double 108',
        'type' => 'simple',
        'price' => 500,
        'capacity' => 2,
        'desc' => 'Comfortable standard room equipped with a double bed, TV, and high-speed Wi-Fi.',
        'image' => 'https://images.pexels.com/photos/1743227/pexels-photo-1743227.jpeg?auto=compress&cs=tinysrgb&w=600'
    ],
    [
        'name' => 'Ocean View Delux 210',
        'type' => 'delux',
        'price' => 950,
        'capacity' => 2,
        'desc' => 'Enjoy breathtaking ocean views from the private balcony of this deluxe room.',
        'image' => 'https://images.pexels.com/photos/3201761/pexels-photo-3201761.jpeg?auto=compress&cs=tinysrgb&w=600'
    ],
    [
        'name' => 'Presidential Suite 501',
        'type' => 'executive',
        'price' => 3500,
        'capacity' => 4,
        'desc' => 'The ultimate luxury experience. Features a private jacuzzi, personal butler service, and sprawling spaces.',
        'image' => 'https://images.pexels.com/photos/262048/pexels-photo-262048.jpeg?auto=compress&cs=tinysrgb&w=600'
    ]
];

foreach ($rooms as $r) {
    $stmt = $mysqli->prepare("INSERT INTO rooms (room_name, room_type, price, capacity, description, image, status) VALUES (?, ?, ?, ?, ?, ?, 'available')");
    $stmt->bind_param("ssdiss", $r['name'], $r['type'], $r['price'], $r['capacity'], $r['desc'], $r['image']);
    $stmt->execute();
}

echo "Added 5 rooms successfully.";
?>
