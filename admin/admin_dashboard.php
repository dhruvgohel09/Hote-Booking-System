<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Hotel Booking</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f4f6f9;
        }

        .admin-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header Styles */
        .header {
            background: white;
            padding: 20px 30px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #2c3e50;
            font-size: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .current-date {
            color: #7f8c8d;
            font-size: 16px;
            font-weight: 500;
            padding: 8px 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .btn-logout {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-logout:hover {
            background: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }

        .stat-card h3 {
            color: #7f8c8d;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }

        .stat-number {
            font-size: 36px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .stat-label {
            color: #27ae60;
            font-size: 13px;
            font-weight: 500;
        }

        /* Search and Filter Bar */
        .action-bar {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .search-box {
            flex: 1;
            min-width: 250px;
            position: relative;
        }

        .search-box input {
            width: 100%;
            padding: 12px 20px;
            padding-left: 45px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .search-box input:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52,152,219,0.1);
        }

        .search-box::before {
            content: '🔍';
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            color: #999;
        }

        .filter-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            background: #f8f9fa;
            color: #2c3e50;
        }

        .filter-btn:hover {
            background: #e9ecef;
        }

        .filter-btn.active {
            background: #3498db;
            color: white;
        }

        /* Table Styles */
        .table-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .table-header {
            padding: 20px;
            border-bottom: 2px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-header h2 {
            color: #2c3e50;
            font-size: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .export-btn {
            padding: 10px 20px;
            background: #27ae60;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .export-btn:hover {
            background: #219a52;
            transform: translateY(-2px);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #34495e;
            color: white;
            padding: 15px;
            font-size: 14px;
            font-weight: 600;
            text-align: left;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #ecf0f1;
            color: #2c3e50;
            font-size: 14px;
        }

        tr:hover {
            background: #f8f9fa;
        }

        /* Status Badges */
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
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

        .badge-paid {
            background: #cce5ff;
            color: #004085;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-view, .btn-edit {
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-view {
            background: #3498db;
            color: white;
        }

        .btn-view:hover {
            background: #2980b9;
        }

        .btn-edit {
            background: #f39c12;
            color: white;
        }

        .btn-edit:hover {
            background: #e67e22;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background: white;
            width: 90%;
            max-width: 500px;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            position: relative;
        }

        .modal-content h2 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 22px;
        }

        .modal-close {
            position: absolute;
            right: 20px;
            top: 15px;
            font-size: 24px;
            cursor: pointer;
            color: #999;
        }

        .modal-close:hover {
            color: #333;
        }

        .booking-detail {
            margin: 15px 0;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .booking-detail strong {
            color: #2c3e50;
            display: inline-block;
            width: 100px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .action-bar {
                flex-direction: column;
            }
            
            .search-box {
                width: 100%;
            }
            
            table {
                display: block;
                overflow-x: auto;
            }
        }

        /* Loading State */
        .loading {
            text-align: center;
            padding: 50px;
            color: #7f8c8d;
            font-size: 16px;
        }

        .loading::after {
            content: '...';
            animation: dots 1.5s steps(4, end) infinite;
        }

        @keyframes dots {
            0%, 20% { content: '.'; }
            40% { content: '..'; }
            60%, 100% { content: '...'; }
        }

        /* Success Message */
        .toast {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #27ae60;
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            display: none;
            z-index: 1100;
        }

        .toast.show {
            display: block;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Header -->
        <div class="header">
            <h1>
                <span>🏨</span>
                Hotel Booking Admin Panel
            </h1>
            <div class="header-right">
                <span class="current-date" id="currentDate"></span>
                <button class="btn-logout" onclick="logout()">
                    <span>🚪</span>
                    Logout
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Bookings</h3>
                <div class="stat-number" id="totalBookings">0</div>
                <div class="stat-label">↑ 12% from last month</div>
            </div>
            <div class="stat-card">
                <h3>Total Revenue</h3>
                <div class="stat-number" id="totalRevenue">₹0</div>
                <div class="stat-label">↑ 8% from last month</div>
            </div>
            <div class="stat-card">
                <h3>Confirmed</h3>
                <div class="stat-number" id="confirmedBookings">0</div>
                <div class="stat-label">Ready for check-in</div>
            </div>
            <div class="stat-card">
                <h3>Pending</h3>
                <div class="stat-number" id="pendingBookings">0</div>
                <div class="stat-label">Awaiting confirmation</div>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="action-bar">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Search by guest name, booking ID or room..." onkeyup="searchBookings(this.value)">
            </div>
            <div class="filter-buttons">
                <button class="filter-btn active" onclick="filterBookings('all')">All</button>
                <button class="filter-btn" onclick="filterBookings('confirmed')">Confirmed</button>
                <button class="filter-btn" onclick="filterBookings('pending')">Pending</button>
                <button class="filter-btn" onclick="filterBookings('cancelled')">Cancelled</button>
            </div>
        </div>

        <!-- Bookings Table -->
        <div class="table-container">
            <div class="table-header">
                <h2>
                    <span>📋</span>
                    Recent Bookings
                </h2>
                <button class="export-btn" onclick="exportData()">
                    <span>📥</span>
                    Export Report
                </button>
            </div>
            <table id="bookingsTable">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Guest Name</th>
                        <th>Room No</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Guests</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="bookingsTableBody">
                    <tr>
                        <td colspan="10" class="loading">Loading bookings</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Booking Details Modal -->
    <div class="modal" id="bookingModal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeModal()">&times;</span>
            <h2>Booking Details</h2>
            <div id="modalContent">
                <!-- Details will be shown here -->
            </div>
        </div>
    </div>

    <!-- Toast Message -->
    <div class="toast" id="toast"></div>

    <script>
        // ==================== FAKE DATA ====================
        let bookings = [
            {
                id: "BK001",
                guest: "Ramesh Patel",
                room: "101",
                checkIn: "2026-03-04",
                checkOut: "2026-03-06",
                guests: 2,
                amount: 7000,
                status: "confirmed",
                payment: "paid",
                email: "ramesh@email.com",
                phone: "9876543210",
                address: "Ahmedabad"
            },
            {
                id: "BK002",
                guest: "Priya Sharma",
                room: "205",
                checkIn: "2026-03-04",
                checkOut: "2026-03-05",
                guests: 1,
                amount: 3500,
                status: "pending",
                payment: "pending",
                email: "priya@email.com",
                phone: "9876543211",
                address: "Mumbai"
            },
            {
                id: "BK003",
                guest: "Amit Kumar",
                room: "304",
                checkIn: "2026-03-05",
                checkOut: "2026-03-07",
                guests: 3,
                amount: 11000,
                status: "confirmed",
                payment: "paid",
                email: "amit@email.com",
                phone: "9876543212",
                address: "Delhi"
            },
            {
                id: "BK004",
                guest: "Neha Gupta",
                room: "102",
                checkIn: "2026-03-04",
                checkOut: "2026-03-05",
                guests: 2,
                amount: 3800,
                status: "cancelled",
                payment: "refunded",
                email: "neha@email.com",
                phone: "9876543213",
                address: "Surat"
            },
            {
                id: "BK005",
                guest: "Suresh Yadav",
                room: "401",
                checkIn: "2026-03-06",
                checkOut: "2026-03-08",
                guests: 4,
                amount: 15000,
                status: "confirmed",
                payment: "paid",
                email: "suresh@email.com",
                phone: "9876543214",
                address: "Vadodara"
            }
        ];

        // ==================== LOGOUT FUNCTION ====================
        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                // Local storage thi data remove karo
                localStorage.removeItem('isLoggedIn');
                localStorage.removeItem('userType');
                localStorage.removeItem('userEmail');
                localStorage.removeItem('userName');
                
                // Show toast message
                showToast('Logging out...', 'success');
                
                // Redirect to login page after 1 second
                setTimeout(() => {
                    window.location.href = './login.php';
                }, 1000);
            }
        }

        // ==================== DISPLAY CURRENT DATE ====================
        function displayCurrentDate() {
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            const today = new Date();
            document.getElementById('currentDate').textContent = today.toLocaleDateString('en-US', options);
        }

        // ==================== DISPLAY BOOKINGS ====================
        function displayBookings(bookingsToShow) {
            const tableBody = document.getElementById('bookingsTableBody');
            
            if (!bookingsToShow || bookingsToShow.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="10" style="text-align: center; padding: 50px; color: #999;">
                            No bookings found
                        </td>
                    </tr>
                `;
                return;
            }
            
            let html = '';
            
            bookingsToShow.forEach(booking => {
                html += `
                    <tr>
                        <td><strong>${booking.id}</strong></td>
                        <td>${booking.guest}</td>
                        <td>${booking.room}</td>
                        <td>${booking.checkIn}</td>
                        <td>${booking.checkOut}</td>
                        <td>${booking.guests}</td>
                        <td><strong>₹${booking.amount.toLocaleString()}</strong></td>
                        <td><span class="badge badge-${booking.status}">${booking.status}</span></td>
                        <td><span class="badge badge-${booking.payment}">${booking.payment}</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-view" onclick="viewBooking('${booking.id}')">👁️ View</button>
                                <button class="btn-edit" onclick="editBooking('${booking.id}')">✏️ Edit</button>
                            </div>
                        </td>
                    </tr>
                `;
            });
            
            tableBody.innerHTML = html;
        }

        // ==================== UPDATE STATS ====================
        function updateStats() {
            // Total bookings
            const totalBookings = bookings.length;
            
            // Total revenue (only paid bookings)
            const totalRevenue = bookings
                .filter(b => b.payment === 'paid')
                .reduce((sum, booking) => sum + booking.amount, 0);
            
            // Confirmed bookings
            const confirmedBookings = bookings.filter(b => b.status === 'confirmed').length;
            
            // Pending bookings
            const pendingBookings = bookings.filter(b => b.status === 'pending').length;
            
            // Update DOM
            document.getElementById('totalBookings').textContent = totalBookings;
            document.getElementById('totalRevenue').textContent = '₹' + totalRevenue.toLocaleString();
            document.getElementById('confirmedBookings').textContent = confirmedBookings;
            document.getElementById('pendingBookings').textContent = pendingBookings;
        }

        // ==================== VIEW BOOKING DETAILS ====================
        function viewBooking(bookingId) {
            const booking = bookings.find(b => b.id === bookingId);
            
            if (booking) {
                const modalContent = `
                    <div class="booking-detail">
                        <p><strong>Booking ID:</strong> ${booking.id}</p>
                        <p><strong>Guest Name:</strong> ${booking.guest}</p>
                        <p><strong>Email:</strong> ${booking.email}</p>
                        <p><strong>Phone:</strong> ${booking.phone}</p>
                        <p><strong>Address:</strong> ${booking.address}</p>
                        <p><strong>Room No:</strong> ${booking.room}</p>
                        <p><strong>Check In:</strong> ${booking.checkIn}</p>
                        <p><strong>Check Out:</strong> ${booking.checkOut}</p>
                        <p><strong>Guests:</strong> ${booking.guests}</p>
                        <p><strong>Amount:</strong> ₹${booking.amount}</p>
                        <p><strong>Status:</strong> <span class="badge badge-${booking.status}">${booking.status}</span></p>
                        <p><strong>Payment:</strong> <span class="badge badge-${booking.payment}">${booking.payment}</span></p>
                    </div>
                    <button onclick="closeModal()" style="width:100%; padding:12px; background:#3498db; color:white; border:none; border-radius:5px; margin-top:20px; cursor:pointer;">Close</button>
                `;
                
                document.getElementById('modalContent').innerHTML = modalContent;
                document.getElementById('bookingModal').classList.add('show');
            }
        }

        // ==================== EDIT BOOKING ====================
        function editBooking(bookingId) {
            showToast('Edit functionality coming soon for ' + bookingId, 'info');
        }

        // ==================== CLOSE MODAL ====================
        function closeModal() {
            document.getElementById('bookingModal').classList.remove('show');
        }

        // ==================== FILTER BOOKINGS ====================
        function filterBookings(status) {
            // Update active button
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
            
            // Filter bookings
            let filtered;
            if (status === 'all') {
                filtered = bookings;
            } else {
                filtered = bookings.filter(b => b.status === status);
            }
            
            displayBookings(filtered);
            showToast(`Showing ${filtered.length} ${status} bookings`, 'success');
        }

        // ==================== SEARCH BOOKINGS ====================
        function searchBookings(query) {
            query = query.toLowerCase().trim();
            
            if (query === '') {
                displayBookings(bookings);
                return;
            }
            
            const filtered = bookings.filter(booking => 
                booking.id.toLowerCase().includes(query) ||
                booking.guest.toLowerCase().includes(query) ||
                booking.room.includes(query) ||
                booking.email.toLowerCase().includes(query)
            );
            
            displayBookings(filtered);
        }

        // ==================== EXPORT DATA ====================
        function exportData() {
            // Create CSV content
            let csv = "ID,Guest,Room,Check In,Check Out,Guests,Amount,Status,Payment\n";
            
            bookings.forEach(b => {
                csv += `${b.id},${b.guest},${b.room},${b.checkIn},${b.checkOut},${b.guests},${b.amount},${b.status},${b.payment}\n`;
            });
            
            // Download CSV
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'bookings_report.csv';
            a.click();
            
            showToast('Report exported successfully!', 'success');
        }

        // ==================== SHOW TOAST MESSAGE ====================
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.style.background = type === 'success' ? '#27ae60' : '#f39c12';
            toast.classList.add('show');
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }

        // ==================== INITIALIZATION ====================
        document.addEventListener('DOMContentLoaded', function() {
            // Display current date
            displayCurrentDate();
            
            // Simulate loading delay
            setTimeout(() => {
                // Display bookings
                displayBookings(bookings);
                
                // Update stats
                updateStats();
                
                showToast('Welcome to Admin Panel!', 'success');
            }, 1000);
            
            // Check if user is logged in (for demo)
            const isLoggedIn = localStorage.getItem('isLoggedIn');
            if (!isLoggedIn) {
                // For demo, set fake login
                localStorage.setItem('isLoggedIn', 'true');
                localStorage.setItem('userType', 'admin');
                localStorage.setItem('userName', 'Admin');
            }
        });

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('bookingModal');
            if (event.target == modal) {
                modal.classList.remove('show');
            }
        };
    </script>
</body>
</html>