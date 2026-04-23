<?php
header('Content-Type: application/json; charset=utf-8');
require_once dirname(__DIR__) . '/includes/init.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

$amount = isset($data['amount']) ? (int)$data['amount'] : 0;
if ($amount <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid amount']);
    exit;
}

$keyId = defined('RAZORPAY_KEY_ID') ? trim(RAZORPAY_KEY_ID) : '';
$keySecret = defined('RAZORPAY_KEY_SECRET') ? trim(RAZORPAY_KEY_SECRET) : '';

if (empty($keyId) || empty($keySecret) || $keyId === 'rzp_test_YourTestKeyHere' || $keyId === 'rzp_test_YOUR_NEW_KEY_ID') {
    echo json_encode(['success' => false, 'message' => 'Invalid Razorpay config']);
    exit;
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.razorpay.com/v1/orders");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    "amount" => $amount * 100, // paise
    "currency" => "INR",
    "receipt" => "rcptid_" . rand(1000, 9999) . time()
]));
curl_setopt($ch, CURLOPT_USERPWD, $keyId . ":" . $keySecret);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo json_encode(['success' => false, 'message' => 'Curl error: ' . curl_error($ch)]);
    exit;
}
curl_close($ch);

$orderData = json_decode($response, true);

if (isset($orderData['id'])) {
    echo json_encode(['success' => true, 'order_id' => $orderData['id']]);
} else {
    $rzpError = isset($orderData['error']['description']) ? $orderData['error']['description'] : 'Unknown Razorpay Error';
    echo json_encode(['success' => false, 'message' => 'Order API failed: ' . $rzpError, 'debug' => $orderData]);
}
exit;
