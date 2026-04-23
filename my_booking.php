<?php
require_once __DIR__ . '/includes/init.php';

if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
if (($_SESSION['user_role'] ?? '') === 'admin') {
    header('Location: admin/index.php');
    exit;
}

$uid = (int) $_SESSION['user_id'];
$bookings_list = [];
$q = $mysqli->prepare(
    'SELECT b.id, b.bill_number, b.check_in, b.check_out, b.total_price, b.status, b.created_at, r.room_name, r.image, r.price AS room_price
     FROM bookings b
     INNER JOIN rooms r ON r.id = b.room_id
     WHERE b.user_id = ?
     ORDER BY b.created_at DESC'
);
$q->bind_param('i', $uid);
$q->execute();
$res = $q->get_result();
while ($row = $res->fetch_assoc()) {
    $bookings_list[] = $row;
}
$q->close();

// Determine if any booking is cancelled to change overall theme
$hasCancelledBooking = false;
foreach ($bookings_list as $b) {
    if (($b['status'] ?? '') === 'cancelled') {
        $hasCancelledBooking = true;
        break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - The Imperial Crown Hotel</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <style>
        /* Base body style - will be overridden by dynamic class */
        body {
            min-height: 100vh;
            padding-top: 80px;
            transition: background 0.5s ease;
            display: flex;
            flex-direction: column;
        }

        /* Push footer to bottom */
        .content-wrapper {
            flex: 1;
        }

        /* Hotel Theme Background (default for active bookings) */
        body.hotel-theme {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        /* Gaming Theme Background (when any booking is cancelled) */
        body.gaming-theme {
            background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
            position: relative;
        }

        /* Gaming overlay pattern */
        body.gaming-theme::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(rgba(0, 255, 255, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        /* Gaming floating orbs */
        body.gaming-theme::after {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 30%, rgba(255, 0, 150, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 80% 70%, rgba(0, 255, 200, 0.1) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        .container {
            position: relative;
            z-index: 1;
        }
        
        .page-header {
            text-align: center;
            margin-bottom: 40px;
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .page-header h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .page-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* Gaming header decoration */
        body.gaming-theme .page-header h1 i {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { text-shadow: 0 0 0px cyan; }
            50% { text-shadow: 0 0 10px cyan; }
            100% { text-shadow: 0 0 0px cyan; }
        }
        
        .booking-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 25px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .booking-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }

        /* Gaming card style for cancelled bookings */
        .booking-card.cancelled-card {
            background: linear-gradient(135deg, #1a1a2e, #16213e);
            color: #eee;
            border: 1px solid rgba(0, 255, 255, 0.3);
        }

        .booking-card.cancelled-card .booking-header {
            background: linear-gradient(135deg, #0f0c29, #302b63);
        }

        .booking-card.cancelled-card .room-title {
            color: #00ffff;
        }

        .booking-card.cancelled-card .detail-item .value {
            color: #ddd;
        }

        .booking-card.cancelled-card .guest-badge {
            background: rgba(255,255,255,0.1);
            color: #ccc;
        }

        .booking-card.cancelled-card .booking-footer {
            background: rgba(0,0,0,0.3);
        }
        
        .booking-header {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .booking-id {
            font-size: 1.1rem;
            font-weight: bold;
        }
        
        .booking-id i {
            margin-right: 8px;
        }
        
        .booking-date {
            font-size: 0.85rem;
            opacity: 0.9;
        }
        
        .booking-body {
            padding: 25px;
        }
        
        .room-info {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .room-image {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #3498db, #2c3e50);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        
        .room-image i {
            font-size: 50px;
        }
        
        .room-details {
            flex: 1;
        }
        
        .room-title {
            font-size: 1.3rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .room-price {
            color: #e67e22;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .booking-details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e0e0e0;
        }
        
        .detail-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .detail-item i {
            font-size: 1.2rem;
            color: #3498db;
            width: 30px;
        }
        
        .detail-item .label {
            font-size: 0.8rem;
            color: #7f8c8d;
        }
        
        .detail-item .value {
            font-weight: bold;
            color: #2c3e50;
        }
        
        .guest-info {
            display: flex;
            gap: 15px;
            margin-top: 15px;
            flex-wrap: wrap;
        }
        
        .guest-badge {
            background: #f0f0f0;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        
        .guest-badge i {
            margin-right: 5px;
            color: #3498db;
        }
        
        .booking-footer {
            background: #f8f9fa;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .total-price {
            font-size: 1.2rem;
            font-weight: bold;
        }
        
        .total-price span {
            font-size: 1.5rem;
            color: #28a745;
        }

        /* Gaming theme total price */
        .booking-card.cancelled-card .total-price span {
            color: #ff6b6b;
        }
        
        .btn-cancel {
            background: #dc3545;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 25px;
            transition: all 0.3s;
        }
        
        .btn-cancel:hover {
            background: #c82333;
            transform: scale(1.05);
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .empty-state i {
            font-size: 80px;
            color: #3498db;
            margin-bottom: 20px;
        }
        
        .empty-state h3 {
            font-size: 1.8rem;
            margin-bottom: 15px;
            color: #2c3e50;
        }
        
        .empty-state p {
            color: #7f8c8d;
        }
        
        .btn-book-now {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            border: none;
            padding: 12px 35px;
            font-weight: bold;
            margin-top: 20px;
            border-radius: 30px;
            transition: transform 0.3s;
            color: white;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-book-now:hover {
            transform: translateY(-2px);
            color: white;
            background: linear-gradient(135deg, #1a252f, #2980b9);
        }

        /* Gaming button */
        body.gaming-theme .btn-book-now {
            background: linear-gradient(135deg, #00ffff, #ff00ff);
            box-shadow: 0 0 10px rgba(0,255,255,0.5);
        }
        
        @media (max-width: 768px) {
            .booking-header {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
            
            .booking-footer {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
            
            .room-info {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            
            .detail-item {
                justify-content: center;
            }
            
            .guest-info {
                justify-content: center;
            }
        }
    </style>
</head>
<body class="<?php echo $hasCancelledBooking ? 'gaming-theme' : 'hotel-theme'; ?>">

<!-- Include Navbar -->
<?php include 'navbar.php'; ?>

<!-- Content wrapper to push footer down -->
<div class="content-wrapper">
    <div class="container">
        <div class="page-header">
            <h1><i class="bi bi-calendar-check"></i> My Bookings</h1>
            <p>View and manage all your hotel reservations</p>
        </div>
        
        <div id="bookingsContainer">
            <?php if (count($bookings_list) === 0): ?>
                <div class="empty-state">
                    <i class="bi bi-calendar-x"></i>
                    <h3>No Bookings Found</h3>
                    <p>You haven't made any reservations yet.</p>
                    <p>Book your first stay and create unforgettable memories!</p>
                    <a href="rooms.php" class="btn-book-now">
                        <i class="bi bi-search"></i> Browse Available Rooms
                    </a>
                </div>
            <?php else: ?>
                <?php foreach ($bookings_list as $b):
                    $in = new DateTime($b['check_in']);
                    $out = new DateTime($b['check_out']);
                    $nights = (int) $in->diff($out)->days;
                    $img = trim((string) $b['image']);
                    if ($img === '' || strncmp($img, 'http', 4) !== 0) {
                        $img = 'https://images.pexels.com/photos/271624/pexels-photo-271624.jpeg?auto=compress&cs=tinysrgb&w=600';
                    }
                    $isCancelled = ($b['status'] ?? '') === 'cancelled';
                    $cardClass = $isCancelled ? 'cancelled-card' : '';
                ?>
                <div class="booking-card <?php echo $cardClass; ?>">
                    <div class="booking-header">
                        <div class="booking-id">
                            <i class="bi bi-receipt"></i>
                            Booking #<?php echo (int) $b['id']; ?>
                            <?php if ($isCancelled): ?>
                                <span class="badge bg-danger ms-2">CANCELLED</span>
                            <?php endif; ?>
                        </div>
                        <div class="booking-date">
                            <i class="bi bi-calendar"></i>
                            Booked: <?php echo htmlspecialchars(date('d/m/Y', strtotime($b['created_at']))); ?>
                        </div>
                    </div>
                    <div class="booking-body">
                        <div class="room-info">
                            <div class="room-image" style="background-size:cover;background-position:center;background-image:url('<?php echo htmlspecialchars($img); ?>')">
                                <i class="bi bi-building" style="opacity:0;"></i>
                            </div>
                            <div class="room-details">
                                <div class="room-title"><?php echo htmlspecialchars($b['room_name']); ?></div>
                                <div class="room-price">₹<?php echo htmlspecialchars((string) $b['room_price']); ?> per night</div>
                                <div class="booking-details-grid">
                                    <div class="detail-item">
                                        <i class="bi bi-calendar-check"></i>
                                        <div>
                                            <div class="label">Check In</div>
                                            <div class="value"><?php echo $in->format('d/m/Y'); ?></div>
                                        </div>
                                    </div>
                                    <div class="detail-item">
                                        <i class="bi bi-calendar-x"></i>
                                        <div>
                                            <div class="label">Check Out</div>
                                            <div class="value"><?php echo $out->format('d/m/Y'); ?></div>
                                        </div>
                                    </div>
                                    <div class="detail-item">
                                        <i class="bi bi-moon-stars"></i>
                                        <div>
                                            <div class="label">Nights</div>
                                            <div class="value"><?php echo $nights; ?> Night(s)</div>
                                        </div>
                                    </div>
                                    <div class="detail-item">
                                        <i class="bi bi-info-circle"></i>
                                        <div>
                                            <div class="label">Status</div>
                                            <div class="value"><?php echo htmlspecialchars($b['status']); ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="booking-footer">
                        <div class="total-price">
                            Total Amount: <span>₹<?php echo htmlspecialchars(number_format((float) $b['total_price'], 2)); ?></span>
                            <?php if (!empty($b['bill_number'])): ?>
                                <div class="small text-muted mt-1">Invoice: <?php echo htmlspecialchars($b['bill_number']); ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex flex-wrap gap-2 justify-content-end">
                            <a href="bill.php?id=<?php echo (int) $b['id']; ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-receipt"></i> View bill
                            </a>
                            <?php if (!$isCancelled): ?>
                            <button type="button" class="btn btn-sm btn-cancel" onclick="cancelBookingDb(<?php echo (int) $b['id']; ?>)">
                                <i class="bi bi-x-circle"></i> Cancel
                            </button>
                            <?php else: ?>
                            <button type="button" class="btn btn-sm btn-secondary" disabled>
                                <i class="bi bi-emoji-frown"></i> Cancelled
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Include Footer -->
<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function cancelBookingDb(bookingId) {
    if (!confirm('⚠️ Cancel this booking? ⚠️\nThis action cannot be undone!')) return;
    fetch('api/cancel_booking.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ booking_id: bookingId })
    })
    .then(r => r.json())
    .then(data => {
        alert(data.message || (data.success ? '✅ Booking cancelled successfully!' : '❌ Failed to cancel.'));
        if (data.success) location.reload();
    })
    .catch(() => alert('Network error.'));
}
</script>

</body>
</html>