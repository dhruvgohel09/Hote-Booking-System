<?php
require_once __DIR__ . '/../includes/init.php';
if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: ../login.php');
    exit;
}

// -----------------------------------------
// FETCH DASHBOARD STATS
// -----------------------------------------
$query = "SELECT COUNT(*) as count FROM rooms";
$res = $mysqli->query($query);
$totalRooms = $res ? $res->fetch_assoc()['count'] : 0;

$query = "SELECT COUNT(*) as count FROM bookings WHERE status IN ('pending', 'confirmed')";
$res = $mysqli->query($query);
$activeBookings = $res ? $res->fetch_assoc()['count'] : 0;

$query = "SELECT COUNT(*) as count FROM users WHERE role = 'user'";
$res = $mysqli->query($query);
$totalUsers = $res ? $res->fetch_assoc()['count'] : 0;

$query = "SELECT SUM(amount) as total FROM payments WHERE payment_status = 'paid'";
$res = $mysqli->query($query);
$totalRevenue = $res ? $res->fetch_assoc()['total'] : 0;
if (!$totalRevenue)
    $totalRevenue = 0;

$query = "SELECT id, room_name as name, room_type as type, price, status, image FROM rooms ORDER BY id DESC LIMIT 4";
$res = $mysqli->query($query);
$roomsData = [];
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $roomsData[] = $row;
    }
}

$query = "SELECT b.id as raw_id, CONCAT(u.first_name, ' ', u.last_name) as customer, r.room_name as room, b.status, b.bill_number 
          FROM bookings b 
          JOIN users u ON b.user_id = u.id 
          JOIN rooms r ON b.room_id = r.id 
          ORDER BY b.id DESC LIMIT 4";
$res = $mysqli->query($query);
$bookingsData = [];
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $bookingsData[] = [
            'id' => $row['bill_number'] ? $row['bill_number'] : 'B' . str_pad($row['raw_id'], 3, '0', STR_PAD_LEFT),
            'customer' => $row['customer'],
            'room' => $row['room'],
            'status' => $row['status']
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Imperial Crown Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            background: #2c3e50;
            height: 100vh;
            color: white;
            position: fixed;
            width: 280px;
            left: 0;
            top: 0;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar .logo {
            padding: 25px 20px;
            text-align: center;
            border-bottom: 1px solid #34495e;
            background: #243342;
        }

        .sidebar .logo img {
            width: 70px;
            margin-bottom: 10px;
        }

        .sidebar .logo h4 {
            color: white;
            margin: 10px 0 5px;
            font-weight: 600;
        }

        .sidebar .logo span {
            background: #e74c3c;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            display: inline-block;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .sidebar-menu a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            padding: 15px 25px;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            border-left: 4px solid transparent;
            margin: 5px 0;
        }

        .sidebar-menu a:hover {
            background: #34495e;
            color: white;
            border-left-color: #3498db;
            padding-left: 35px;
        }

        .sidebar-menu a.active {
            background: #3498db;
            color: white;
            border-left-color: #f1c40f;
        }

        .sidebar-menu i {
            margin-right: 15px;
            font-size: 20px;
            width: 25px;
        }

        .sidebar-menu span {
            font-size: 15px;
            font-weight: 500;
        }

        .sidebar-menu .badge {
            margin-left: auto;
            background: #e74c3c;
            color: white;
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 10px;
        }

        /* Main Content */
        .content {
            margin-left: 280px;
            padding: 20px 30px;
            transition: all 0.3s;
        }

        .header {
            background: white;
            padding: 15px 25px;
            border-radius: 15px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h4 {
            margin: 0;
            color: #2c3e50;
            font-weight: 600;
        }

        .header h4 i {
            margin-right: 10px;
            color: #3498db;
        }

        .header .date {
            background: #f8f9fa;
            padding: 8px 15px;
            border-radius: 8px;
            color: #2c3e50;
        }

        .header .date i {
            margin-right: 8px;
            color: #3498db;
        }

        .online-badge {
            background: #27ae60;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 13px;
            margin-left: 10px;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
        }

        .stats-card.rooms::before {
            background: #3498db;
        }

        .stats-card.bookings::before {
            background: #27ae60;
        }

        .stats-card.users::before {
            background: #f39c12;
        }

        .stats-card.revenue::before {
            background: #e74c3c;
        }

        .stats-icon {
            font-size: 45px;
            margin-bottom: 15px;
        }

        .stats-icon.rooms {
            color: #3498db;
        }

        .stats-icon.bookings {
            color: #27ae60;
        }

        .stats-icon.users {
            color: #f39c12;
        }

        .stats-icon.revenue {
            color: #e74c3c;
        }

        .stats-card h6 {
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .stats-card h3 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .stats-card small {
            color: #27ae60;
            font-size: 13px;
        }

        .stats-card small i {
            margin-right: 3px;
        }

        /* Two Column Layout */
        .two-column {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-bottom: 25px;
        }

        .table-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .card-header h5 {
            margin: 0;
            color: #2c3e50;
            font-weight: 600;
        }

        .card-header h5 i {
            margin-right: 10px;
            color: #3498db;
        }

        .view-all {
            color: #3498db;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            padding: 5px 12px;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .view-all:hover {
            background: #e8f0fe;
            color: #2980b9;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            border-top: none;
            color: #7f8c8d;
            font-weight: 600;
            font-size: 13px;
            padding: 12px 8px;
        }

        .table td {
            padding: 12px 8px;
            color: #2c3e50;
            vertical-align: middle;
        }

        .badge-status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-confirmed {
            background: #d4edda;
            color: #155724;
        }

        .badge-pending {
            background: #fff3cd;
            color: #856404;
        }

        .badge-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .badge-available {
            background: #d4edda;
            color: #155724;
        }

        .badge-booked {
            background: #f8d7da;
            color: #721c24;
        }

        .badge-maintenance {
            background: #fff3cd;
            color: #856404;
        }

        .room-img-small {
            width: 50px;
            height: 40px;
            object-fit: cover;
            border-radius: 5px;
        }

        /* Quick Actions */
        .quick-actions {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-top: 15px;
        }

        .action-btn {
            background: #f8f9fa;
            border: none;
            padding: 15px;
            border-radius: 10px;
            color: #2c3e50;
            font-weight: 500;
            transition: all 0.3s;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .action-btn:hover {
            background: #3498db;
            color: white;
            transform: translateY(-3px);
        }

        .action-btn i {
            font-size: 24px;
        }

        /* Dev Mode Indicator */
        .dev-mode-indicator {
            position: fixed;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            background: #ffc107;
            color: #000;
            padding: 5px 20px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
            z-index: 10000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                opacity: 0.8;
            }

            50% {
                opacity: 1;
            }

            100% {
                opacity: 0.8;
            }
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .two-column {
                grid-template-columns: 1fr;
            }

            .actions-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                display: none;
            }

            .content {
                margin-left: 0;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <img src="https://cdn-icons-png.flaticon.com/512/1041/1041916.png" alt="Logo">
            <h4>Imperial Crown</h4>
            <span>Admin Panel</span>
        </div>

        <div class="sidebar-menu">
            <a href="index.php" class="active">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
            <a href="rooms.php">
                <i class="bi bi-door-open"></i>
                <span>Rooms</span>
                <span class="badge"></span>
            </a>
            <a href="bookings.php">
                <i class="bi bi-calendar-check"></i>
                <span>Bookings</span>
                <span class="badge"></span>
            </a>
            <a href="users.php">
                <i class="bi bi-people"></i>
                <span>Users</span>
                <span class="badge"></span>
            </a>
            <a href="facilities.php">
                <i class="bi bi-building"></i>
                <span>Facilities</span>
                <span class="badge"></span>
            </a>
            <a href="user_queries.php">
                <i class="bi bi-envelope"></i>
                <span>User Queries</span>
            </a>
            <a href="settings.php">
                <i class="bi bi-gear"></i>
                <span>Settings</span>
            </a>
            <hr style="border-color: #34495e; margin: 20px;">
            <a href="#" onclick="logout()">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
        <!-- Header -->
        <div class="header">
            <h4><i class="bi bi-speedometer2"></i> Dashboard</h4>
            <div>
                <span class="date">
                    <i class="bi bi-calendar3"></i>
                    <span class="current-date"></span>
                </span>
                <span class="online-badge">
                    <i class="bi bi-circle-fill me-1" style="font-size: 8px;"></i> Online
                </span>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stats-card rooms" onclick="window.location.href='rooms.php'">
                <div class="stats-icon rooms"><i class="bi bi-building"></i></div>
                <h6>Total Rooms</h6>
                <h3 id="totalRooms">24</h3>
                <small><i class="bi bi-arrow-up"></i> +3 this month</small>
            </div>
            <div class="stats-card bookings" onclick="window.location.href='bookings.php'">
                <div class="stats-icon bookings"><i class="bi bi-calendar-check"></i></div>
                <h6>Active Bookings</h6>
                <h3 id="activeBookings">18</h3>
                <small><i class="bi bi-arrow-up"></i> +5 today</small>
            </div>
            <div class="stats-card users" onclick="window.location.href='users.php'">
                <div class="stats-icon users"><i class="bi bi-people"></i></div>
                <h6>Total Users</h6>
                <h3 id="totalUsers">156</h3>
                <small><i class="bi bi-arrow-up"></i> +12 this week</small>
            </div>
            <div class="stats-card revenue" onclick="window.location.href='reports.php'">
                <div class="stats-icon revenue"><i class="bi bi-currency-rupee"></i></div>
                <h6>Revenue</h6>
                <h3 id="totalRevenue">₹45.2K</h3>
                <small><i class="bi bi-arrow-up"></i> +15%</small>
            </div>
        </div>

        <!-- Recent Bookings & Room Status -->
        <div class="two-column">
            <!-- Recent Bookings -->
            <div class="table-card">
                <div class="card-header">
                    <h5><i class="bi bi-clock-history"></i> Recent Bookings</h5>
                    <a href="bookings.php" class="view-all">View All <i class="bi bi-arrow-right"></i></a>
                </div>
                <table class="table" id="recentBookingsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Room</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Will be filled by JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- Room Status -->
            <div class="table-card">
                <div class="card-header">
                    <h5><i class="bi bi-door-open"></i> Room Status</h5>
                    <a href="rooms.php" class="view-all">View All <i class="bi bi-arrow-right"></i></a>
                </div>
                <table class="table" id="roomStatusTable">
                    <thead>
                        <tr>
                            <th>Room</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Will be filled by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h5><i class="bi bi-lightning-charge"></i> Quick Actions</h5>
            <div class="actions-grid">
                <a href="rooms.php?action=add" class="action-btn">
                    <i class="bi bi-plus-circle"></i>
                    <span>Add Room</span>
                </a>
                <a href="bookings.php?action=new" class="action-btn">
                    <i class="bi bi-calendar-plus"></i>
                    <span>New Booking</span>
                </a>
                <a href="users.php?action=add" class="action-btn">
                    <i class="bi bi-person-plus"></i>
                    <span>Add User</span>
                </a>
                <a href="reports.php" class="action-btn">
                    <i class="bi bi-graph-up"></i>
                    <span>Reports</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Main JavaScript -->
    <script>
        // ============================================
        // DATA
        // ============================================

        // Rooms Data
        const roomsData = <?= json_encode($roomsData) ?>;

        // Bookings Data
        const bookingsData = <?= json_encode($bookingsData) ?>;

        // ============================================
        // HELPER FUNCTIONS
        // ============================================

        function getStatusClass(status) {
            switch (status) {
                case 'available': return 'badge-available';
                case 'booked': return 'badge-booked';
                case 'maintenance': return 'badge-maintenance';
                case 'confirmed': return 'badge-confirmed';
                case 'pending': return 'badge-pending';
                case 'cancelled': return 'badge-cancelled';
                default: return 'badge-available';
            }
        }

        function getStatusText(status) {
            return status.charAt(0).toUpperCase() + status.slice(1);
        }

        function formatDate() {
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            return new Date().toLocaleDateString('en-US', options);
        }

        // ============================================
        // DISPLAY FUNCTIONS
        // ============================================

        function displayRecentBookings() {
            const tbody = document.querySelector('#recentBookingsTable tbody');
            let html = '';

            bookingsData.slice(0, 4).forEach(booking => {
                html += `
                    <tr>
                        <td><strong>#${booking.id}</strong></td>
                        <td>${booking.customer}</td>
                        <td>${booking.room}</td>
                        <td><span class="badge-status ${getStatusClass(booking.status)}">${getStatusText(booking.status)}</span></td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;
        }

        function displayRoomStatus() {
            const tbody = document.querySelector('#roomStatusTable tbody');
            let html = '';

            roomsData.slice(0, 4).forEach(room => {
                html += `
                    <tr>
                        <td><img src="${room.image}" class="room-img-small" alt="Room"> ${room.id}</td>
                        <td>${room.type}</td>
                        <td><span class="badge-status ${getStatusClass(room.status)}">${getStatusText(room.status)}</span></td>
                        <td>₹${room.price}</td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;
        }

        function updateStats() {
            document.getElementById('totalRooms').textContent = <?= json_encode($totalRooms) ?>;
            document.getElementById('activeBookings').textContent = <?= json_encode($activeBookings) ?>;
            document.getElementById('totalUsers').textContent = <?= json_encode($totalUsers) ?>;
            document.getElementById('totalRevenue').textContent = '₹' + '<?= number_format($totalRevenue, 2) ?>';
        }

        // ============================================
        // LOGOUT FUNCTION
        // ============================================

        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                localStorage.removeItem('isLoggedIn');
                localStorage.removeItem('userType');
                localStorage.removeItem('userEmail');
                localStorage.removeItem('userName');
                alert('✅ Logged out successfully!');
                window.location.href = '../logout.php';
            }
        }

        // ============================================
        // INITIALIZATION
        // ============================================

        document.addEventListener('DOMContentLoaded', function () {
            // Update date
            document.querySelectorAll('.current-date').forEach(el => {
                el.textContent = formatDate();
            });

            // Display data
            displayRecentBookings();
            displayRoomStatus();
            updateStats();
        });
    </script>
</body>

</html>