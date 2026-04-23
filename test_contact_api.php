<?php
$data = json_encode([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'subject' => 'Test Subject',
    'message' => 'This is a test message to ensure data gets inserted correctly with phone number.',
    'phone' => '1234567890'
]);

$ch = curl_init('http://localhost/Hotel_Booking_System/Hotel_Booking_System/api/contact_submit.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

$response = curl_exec($ch);
curl_close($ch);

echo "Response from API:\n";
echo $response;
