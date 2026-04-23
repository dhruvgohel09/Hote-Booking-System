<?php
require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/includes/bill_helpers.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id < 1) {
    header('HTTP/1.1 404 Not Found');
    exit('Invalid bill.');
}

$data = hotel_get_bill_data($mysqli, $id);
if (!$data) {
    header('HTTP/1.1 404 Not Found');
    exit('Booking not found.');
}

$uid = (int) ($_SESSION['user_id'] ?? 0);
$role = $_SESSION['user_role'] ?? '';
if ($uid !== (int) $data['user_id'] && $role !== 'admin') {
    header('Location: login.php');
    exit;
}

$addr = trim(implode(', ', array_filter([
    $data['address'] ?? '',
    $data['city'] ?? '',
    $data['state'] ?? '',
    $data['pincode'] ?? '',
])));
$pay = $data['payment'] ?? null;
$payStatus = (is_array($pay) && isset($pay['payment_status'])) ? $pay['payment_status'] : 'unpaid';
$payMethod = (is_array($pay) && isset($pay['payment_method'])) ? $pay['payment_method'] : '—';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice <?php echo htmlspecialchars($data['bill_number']); ?> — Imperial Crown Hotel</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Segoe UI', system-ui, sans-serif; margin: 0; padding: 24px; background: #f0f2f5; color: #222; }
        .inv { max-width: 720px; margin: 0 auto; background: #fff; padding: 32px; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,.08); }
        .inv h1 { margin: 0 0 8px; font-size: 1.5rem; color: #2c3e50; }
        .inv .sub { color: #666; font-size: 0.9rem; margin-bottom: 24px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px; }
        .box { background: #f8f9fa; padding: 14px; border-radius: 8px; }
        .box strong { display: block; color: #2c3e50; margin-bottom: 6px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #2c3e50; color: #fff; }
        .total-row td { font-weight: 700; font-size: 1.15rem; border-bottom: none; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 0.85rem; }
        .badge-paid { background: #d4edda; color: #155724; }
        .badge-unpaid { background: #fff3cd; color: #856404; }
        .actions { margin-top: 24px; display: flex; gap: 10px; flex-wrap: wrap; }
        .btn { display: inline-block; padding: 10px 18px; border-radius: 8px; text-decoration: none; font-weight: 600; border: none; cursor: pointer; font-size: 0.95rem; }
        .btn-primary { background: linear-gradient(135deg, #2c3e50, #3498db); color: #fff; }
        .btn-outline { background: #fff; color: #2c3e50; border: 2px solid #2c3e50; }
        @media print {
            body { background: #fff; padding: 0; }
            .actions { display: none; }
            .inv { box-shadow: none; }
        }
    </style>
</head>
<body>
    <div class="inv">
        <h1>The Imperial Crown Hotel</h1>
        <p class="sub">Rajkot, Gujarat, India · GSTIN: 24XXXXX0000X1Z5 (demo)</p>

        <div class="grid">
            <div class="box">
                <strong>Invoice</strong>
                <?php echo htmlspecialchars($data['bill_number']); ?><br>
                <span style="color:#666;">Issued: <?php echo date('d M Y', strtotime($data['created_at'])); ?></span>
            </div>
            <div class="box">
                <strong>Bill to</strong>
                <?php echo htmlspecialchars($data['customer_name']); ?><br>
                <?php echo htmlspecialchars($data['email']); ?><br>
                <?php echo htmlspecialchars($data['phone'] ?? '—'); ?><br>
                <?php if ($addr !== '') echo htmlspecialchars($addr) . '<br>'; ?>
            </div>
        </div>

        <p>
            <strong>Booking status:</strong> <?php echo htmlspecialchars($data['booking_status']); ?>
            &nbsp;|&nbsp;
            <strong>Payment:</strong>
            <span class="badge <?php echo $payStatus === 'paid' ? 'badge-paid' : 'badge-unpaid'; ?>">
                <?php echo htmlspecialchars(ucfirst($payStatus)); ?>
            </span>
            <?php if ($payMethod && $payMethod !== '—') echo ' · ' . htmlspecialchars($payMethod); ?>
        </p>

        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Qty</th>
                    <th>Rate</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <?php echo htmlspecialchars($data['room_name']); ?> (<?php echo htmlspecialchars($data['room_type']); ?>)<br>
                        <small style="color:#666;">Check-in <?php echo htmlspecialchars($data['check_in']); ?> → Check-out <?php echo htmlspecialchars($data['check_out']); ?></small>
                    </td>
                    <td><?php echo (int) $data['nights']; ?> night(s)</td>
                    <td><?php echo hotel_format_inr((float) $data['nightly_rate']); ?></td>
                    <td><?php echo hotel_format_inr((float) $data['total_price']); ?></td>
                </tr>
                <tr class="total-row">
                    <td colspan="3">Total due</td>
                    <td><?php echo hotel_format_inr((float) $data['total_price']); ?></td>
                </tr>
            </tbody>
        </table>

        <p style="color:#666;font-size:0.9rem;">Guests: <?php echo (int) $data['guests']; ?> · This document is a computer-generated invoice.</p>

        <div class="actions">
            <button type="button" class="btn btn-primary" onclick="window.print()">Print / Save as PDF</button>
            <a href="my_booking.php" class="btn btn-outline">Back to my bookings</a>
        </div>
    </div>
</body>
</html>
