<?php
require_once __DIR__ . '/../includes/init.php';
if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: ../login.php');
    exit;
}
$res = $mysqli->query(
    'SELECT b.id, b.check_in, b.check_out, b.total_price, b.status, b.created_at, b.guests, b.bill_number,
            CONCAT(u.first_name, \' \', u.last_name) AS customer, u.email, u.phone,
            r.room_name, r.id AS room_id, p.transaction_id
     FROM bookings b
     INNER JOIN users u ON u.id = b.user_id
     INNER JOIN rooms r ON r.id = b.room_id
     LEFT JOIN payments p ON p.booking_id = b.id
     ORDER BY b.id DESC'
);
$admin_bookings = [];
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $admin_bookings[] = [
            'id' => (int) $row['id'],
            'customer' => $row['customer'],
            'email' => $row['email'],
            'phone' => $row['phone'] ?? '',
            'room' => $row['room_name'],
            'roomId' => (int) $row['room_id'],
            'checkIn' => $row['check_in'],
            'checkOut' => $row['check_out'],
            'adults' => (int) $row['guests'],
            'children' => 0,
            'total' => (float) $row['total_price'],
            'status' => $row['status'],
            'bookedOn' => date('Y-m-d', strtotime($row['created_at'])),
            'bill_number' => $row['bill_number'] ?? '',
            'transaction_id' => $row['transaction_id'] ?? '',
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Management - Imperial Crown Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }

        .stats-icon.total {
            background: #e3f2fd;
            color: #1976d2;
        }

        .stats-icon.confirmed {
            background: #e8f5e9;
            color: #388e3c;
        }

        .stats-icon.pending {
            background: #fff3e0;
            color: #f57c00;
        }

        .stats-icon.cancelled {
            background: #ffebee;
            color: #c62828;
        }

        .stats-info h3 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }

        .stats-info p {
            margin: 5px 0 0;
            color: #7f8c8d;
            font-size: 14px;
        }

        /* Filter Bar */
        .filter-bar {
            background: white;
            padding: 20px 25px;
            border-radius: 15px;
            margin-bottom: 25px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .filter-select {
            padding: 10px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            min-width: 150px;
            font-size: 14px;
        }

        .date-input {
            padding: 10px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
        }

        .search-box {
            flex: 1;
            position: relative;
            min-width: 250px;
        }

        .search-box input {
            width: 100%;
            padding: 10px 20px 10px 45px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 12px;
            color: #7f8c8d;
        }

        /* Bookings Table */
        .table-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            background: #f8f9fa;
            padding: 15px 10px;
            color: #2c3e50;
            font-weight: 600;
            font-size: 14px;
            text-align: left;
            border-bottom: 2px solid #e0e0e0;
        }

        .table td {
            padding: 15px 10px;
            color: #2c3e50;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
        }

        .customer-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .customer-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .badge-status {
            padding: 6px 15px;
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

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-view {
            background: #3498db;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            font-size: 13px;
            cursor: pointer;
        }

        .btn-cancel {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            font-size: 13px;
            cursor: pointer;
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
            z-index: 10000;
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
            <a href="index.php">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
            <a href="rooms.php">
                <i class="bi bi-door-open"></i>
                <span>Rooms</span>
                <span class="badge"></span>
            </a>
            <a href="bookings.php" class="active">
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
            </a><a href="settings.php">
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
            <h4><i class="bi bi-calendar-check"></i> Booking Management</h4>
            <div>
                <span class="date">
                    <i class="bi bi-calendar3"></i>
                    <span class="current-date"></span>
                </span>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stats-card">
                <div class="stats-icon total"><i class="bi bi-calendar-check"></i></div>
                <div class="stats-info">
                    <h3 id="totalBookings">156</h3>
                    <p>Total Bookings</p>
                </div>
            </div>
            <div class="stats-card">
                <div class="stats-icon confirmed"><i class="bi bi-check-circle"></i></div>
                <div class="stats-info">
                    <h3 id="confirmedBookings">98</h3>
                    <p>Confirmed</p>
                </div>
            </div>
            <div class="stats-card">
                <div class="stats-icon pending"><i class="bi bi-clock"></i></div>
                <div class="stats-info">
                    <h3 id="pendingBookings">42</h3>
                    <p>Pending</p>
                </div>
            </div>
            <div class="stats-card">
                <div class="stats-icon cancelled"><i class="bi bi-x-circle"></i></div>
                <div class="stats-info">
                    <h3 id="cancelledBookings">16</h3>
                    <p>Cancelled</p>
                </div>
            </div>
        </div>

        <!-- Filter Bar (No Add Button) -->
        <div class="filter-bar">
            <select class="filter-select" id="statusFilter" onchange="filterBookings()">
                <option value="">All Status</option>
                <option value="confirmed">Confirmed</option>
                <option value="pending">Pending</option>
                <option value="cancelled">Cancelled</option>
            </select>

            <input type="date" class="date-input" id="dateFilter" onchange="filterBookings()">

            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" placeholder="Search by name, email or booking ID..."
                    onkeyup="filterBookings()">
            </div>

            <button class="btn btn-outline-secondary" onclick="resetFilters()">
                <i class="bi bi-arrow-repeat"></i> Reset
            </button>
        </div>

        <!-- Bookings Table -->
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Customer</th>
                        <th>Room</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Guests</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="bookingsTableBody">
                    <!-- Bookings will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- View Booking Modal -->
    <div class="modal fade" id="viewBookingModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="bi bi-eye me-2"></i>Booking Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="viewBookingDetails">
                    <!-- Details will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Booking Modal -->
    <div class="modal fade" id="cancelBookingModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Cancel Booking</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to cancel this booking?</p>
                    <p class="text-danger"><small>This action cannot be undone!</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, Keep it</button>
                    <button type="button" class="btn btn-danger" id="confirmCancelBtn">Yes, Cancel Booking</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let bookings = <?php echo json_encode($admin_bookings, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;

        // ============================================
        // HELPER FUNCTIONS
        // ============================================

        function formatDate(dateString) {
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('en-US', options);
        }

        function getInitials(name) {
            return name.split(' ').map(n => n[0]).join('').toUpperCase();
        }

        function getStatusClass(status) {
            switch (status) {
                case 'confirmed': return 'badge-confirmed';
                case 'pending': return 'badge-pending';
                case 'cancelled': return 'badge-cancelled';
                default: return 'badge-pending';
            }
        }

        function updateStats() {
            const total = bookings.length;
            const confirmed = bookings.filter(b => b.status === 'confirmed').length;
            const pending = bookings.filter(b => b.status === 'pending').length;
            const cancelled = bookings.filter(b => b.status === 'cancelled').length;

            document.getElementById('totalBookings').textContent = total;
            document.getElementById('confirmedBookings').textContent = confirmed;
            document.getElementById('pendingBookings').textContent = pending;
            document.getElementById('cancelledBookings').textContent = cancelled;
        }

        // ============================================
        // DISPLAY FUNCTIONS
        // ============================================

        function displayBookings(bookingsToShow) {
            const tbody = document.getElementById('bookingsTableBody');
            let html = '';

            bookingsToShow.forEach(booking => {
                html += `
                    <tr>
                        <td><strong>#${booking.id}</strong>${booking.bill_number ? '<br><small class="text-muted">' + booking.bill_number + '</small>' : ''}</td>
                        <td>
                            <div class="customer-info">
                                <div class="customer-avatar">${getInitials(booking.customer)}</div>
                                <div>
                                    <strong>${booking.customer}</strong><br>
                                    <small>${booking.email}</small>
                                </div>
                            </div>
                        </td>
                        <td>${booking.room}</td>
                        <td>${formatDate(booking.checkIn)}</td>
                        <td>${formatDate(booking.checkOut)}</td>
                        <td>${booking.adults} Adults, ${booking.children} Children</td>
                        <td>
                            <strong>₹${booking.total}</strong>
                            ${booking.transaction_id ? `<br><small class="text-muted" title="Transaction ID" style="font-size:0.75rem;"><i class="bi bi-upc-scan"></i> ${booking.transaction_id}</small>` : ''}
                        </td>
                        <td>
                            <select class="form-select form-select-sm" style="max-width:130px" onchange="updateBookingStatus(${booking.id}, this.value)" data-prev="${booking.status}">
                                <option value="pending" ${booking.status === 'pending' ? 'selected' : ''}>Pending</option>
                                <option value="confirmed" ${booking.status === 'confirmed' ? 'selected' : ''}>Confirmed</option>
                                <option value="cancelled" ${booking.status === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                            </select>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-view" onclick="viewBooking(${booking.id})">
                                    <i class="bi bi-eye"></i> View
                                </button>
                                <a class="btn-view" href="../bill.php?id=${booking.id}" target="_blank" title="Invoice"><i class="bi bi-receipt"></i></a>
                                ${booking.status !== 'cancelled' ? `
                                    <button class="btn-cancel" onclick="openCancelModal(${booking.id})">
                                        <i class="bi bi-x-circle"></i> Cancel
                                    </button>
                                ` : ''}
                                <button class="btn-cancel" onclick="deleteBooking(${booking.id})" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;
        }

        // ============================================
        // FILTER FUNCTIONS
        // ============================================

        function filterBookings() {
            const statusFilter = document.getElementById('statusFilter').value;
            const dateFilter = document.getElementById('dateFilter').value;
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();

            const filtered = bookings.filter(booking => {
                const matchesStatus = !statusFilter || booking.status === statusFilter;
                const matchesDate = !dateFilter || booking.checkIn === dateFilter || booking.checkOut === dateFilter;
                const matchesSearch = !searchTerm ||
                    String(booking.id).includes(searchTerm) ||
                    (booking.bill_number && booking.bill_number.toLowerCase().includes(searchTerm)) ||
                    booking.customer.toLowerCase().includes(searchTerm) ||
                    booking.email.toLowerCase().includes(searchTerm) ||
                    booking.room.toLowerCase().includes(searchTerm);

                return matchesStatus && matchesDate && matchesSearch;
            });

            displayBookings(filtered);
        }

        function resetFilters() {
            document.getElementById('statusFilter').value = '';
            document.getElementById('dateFilter').value = '';
            document.getElementById('searchInput').value = '';
            displayBookings(bookings);
        }

        // ============================================
        // VIEW BOOKING DETAILS
        // ============================================

        function updateBookingStatus(id, status) {
            fetch('../api/admin/bookings_manage.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'update_status', id, status })
            })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        const b = bookings.find(x => x.id === id);
                        if (b) b.status = status;
                        updateStats();
                    } else {
                        alert(data.message || 'Update failed');
                        location.reload();
                    }
                })
                .catch(() => { alert('Network error'); location.reload(); });
        }

        function deleteBooking(id) {
            if (!confirm('Permanently delete this booking?')) return;
            fetch('../api/admin/bookings_manage.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'delete', id })
            })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        bookings = bookings.filter(b => b.id !== id);
                        displayBookings(bookings);
                        updateStats();
                    } else alert('Delete failed');
                })
                .catch(() => alert('Network error'));
        }

        function viewBooking(id) {
            const booking = bookings.find(b => b.id == id);

            const details = `
                <div class="text-center mb-4">
                    <div class="customer-avatar" style="width: 80px; height: 80px; font-size: 32px; margin: 0 auto;">
                        ${getInitials(booking.customer)}
                    </div>
                    <h5 class="mt-3">${booking.customer}</h5>
                    <span class="badge-status ${getStatusClass(booking.status)}">${booking.status.toUpperCase()}</span>
                </div>
                <hr>
                <div class="row">
                    <div class="col-5"><strong>Booking ID:</strong></div>
                    <div class="col-7">#${booking.id}${booking.bill_number ? ' · ' + booking.bill_number : ''}</div>
                </div>
                <div class="row mt-2">
                    <div class="col-5"><strong>Email:</strong></div>
                    <div class="col-7">${booking.email}</div>
                </div>
                <div class="row mt-2">
                    <div class="col-5"><strong>Phone:</strong></div>
                    <div class="col-7">${booking.phone}</div>
                </div>
                <div class="row mt-2">
                    <div class="col-5"><strong>Room:</strong></div>
                    <div class="col-7">${booking.room}</div>
                </div>
                <div class="row mt-2">
                    <div class="col-5"><strong>Check In:</strong></div>
                    <div class="col-7">${formatDate(booking.checkIn)}</div>
                </div>
                <div class="row mt-2">
                    <div class="col-5"><strong>Check Out:</strong></div>
                    <div class="col-7">${formatDate(booking.checkOut)}</div>
                </div>
                <div class="row mt-2">
                    <div class="col-5"><strong>Guests:</strong></div>
                    <div class="col-7">${booking.adults} Adults, ${booking.children} Children</div>
                </div>
                <div class="row mt-2">
                    <div class="col-5"><strong>Total Amount:</strong></div>
                    <div class="col-7"><strong>₹${booking.total}</strong></div>
                </div>
                <div class="row mt-2">
                    <div class="col-5"><strong>Booked On:</strong></div>
                    <div class="col-7">${formatDate(booking.bookedOn)}</div>
                </div>
            `;

            document.getElementById('viewBookingDetails').innerHTML = details;
            new bootstrap.Modal(document.getElementById('viewBookingModal')).show();
        }

        // ============================================
        // CANCEL BOOKING
        // ============================================

        let currentCancelId = null;

        function openCancelModal(id) {
            currentCancelId = id;
            new bootstrap.Modal(document.getElementById('cancelBookingModal')).show();
        }

        document.getElementById('confirmCancelBtn').onclick = function () {
            if (!currentCancelId) return;
            fetch('../api/admin/bookings_manage.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'update_status', id: currentCancelId, status: 'cancelled' })
            })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        const b = bookings.find(x => x.id === currentCancelId);
                        if (b) b.status = 'cancelled';
                        displayBookings(bookings);
                        updateStats();
                        bootstrap.Modal.getInstance(document.getElementById('cancelBookingModal')).hide();
                        alert('Booking cancelled.');
                    } else alert('Failed to cancel');
                })
                .catch(() => alert('Network error'));
        };

        // ============================================
        // LOGOUT FUNCTION
        // ============================================

        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = '../logout.php';
            }
        }

        // ============================================
        // INITIALIZATION
        // ============================================

        document.addEventListener('DOMContentLoaded', function () {
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            document.querySelectorAll('.current-date').forEach(el => {
                el.textContent = new Date().toLocaleDateString('en-US', options);
            });

            displayBookings(bookings);
            updateStats();
        });
    </script>
</body>

</html>