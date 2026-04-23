<?php
require_once __DIR__ . '/../includes/init.php';
// admin/users.php - Complete User Management
if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$total_users = 0;
$active_users = 0;
$inactive_users = 0;
$new_this_month = 0;
$current_month = date('Y-m');

$ur = $mysqli->query('SELECT u.id, u.first_name, u.last_name, u.email, u.phone, u.role, u.is_active, u.created_at, COUNT(b.id) as bookings FROM users u LEFT JOIN bookings b ON u.id = b.user_id GROUP BY u.id ORDER BY u.id ASC');
$users_json = [];
if ($ur) {
    while ($r = $ur->fetch_assoc()) {
        $joined_date = date('Y-m-d', strtotime($r['created_at']));
        $joined_month = date('Y-m', strtotime($r['created_at']));
        
        $total_users++;
        if ($r['is_active']) {
            $active_users++;
        } else {
            $inactive_users++;
        }
        if ($joined_month === $current_month) {
            $new_this_month++;
        }
        
        $users_json[] = [
            'id' => (int) $r['id'],
            'name' => $r['first_name'] . ' ' . $r['last_name'],
            'email' => $r['email'],
            'phone' => $r['phone'] ?? '',
            'role' => $r['role'],
            'status' => $r['is_active'] ? 'active' : 'inactive',
            'joined' => $joined_date,
            'lastActive' => $joined_date,
            'bookings' => (int) $r['bookings']
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Imperial Crown Hotel</title>
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

        .stats-icon.active {
            background: #e8f5e9;
            color: #388e3c;
        }

        .stats-icon.inactive {
            background: #fff3e0;
            color: #f57c00;
        }

        .stats-icon.new {
            background: #e8eaf6;
            color: #3f51b5;
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

        /* Action Bar */
        .action-bar {
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

        .add-user-btn {
            background: #27ae60;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
        }

        .add-user-btn:hover {
            background: #219a52;
            transform: translateY(-2px);
        }

        .filter-select {
            padding: 10px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            min-width: 150px;
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

        .export-btn {
            background: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Users Table */
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

        .table tbody tr:hover {
            background: #f8f9fa;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 18px;
        }

        .user-details {
            line-height: 1.4;
        }

        .user-details strong {
            font-size: 15px;
        }

        .user-details small {
            color: #7f8c8d;
            font-size: 12px;
        }

        .badge-status {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-active {
            background: #d4edda;
            color: #155724;
        }

        .badge-inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .badge-verified {
            background: #cce5ff;
            color: #004085;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .btn-icon {
            width: 35px;
            height: 35px;
            border-radius: 8px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-view {
            background: #3498db;
            color: white;
        }

        .btn-edit {
            background: #f39c12;
            color: white;
        }

        .btn-delete {
            background: #e74c3c;
            color: white;
        }

        .btn-icon:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 15px;
            border: none;
        }

        .modal-header {
            background: #2c3e50;
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 20px 25px;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .modal-body {
            padding: 25px;
        }

        .modal-footer {
            padding: 20px 25px;
            border-top: 2px solid #f0f0f0;
        }

        .form-label {
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 10px 15px;
            margin-bottom: 15px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #3498db;
            box-shadow: none;
        }

        .password-strength {
            height: 5px;
            background: #e0e0e0;
            border-radius: 5px;
            margin: 5px 0 15px;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            width: 0;
            transition: all 0.3s;
        }

        .strength-weak {
            background: #e74c3c;
            width: 33.33%;
        }

        .strength-medium {
            background: #f39c12;
            width: 66.66%;
        }

        .strength-strong {
            background: #27ae60;
            width: 100%;
        }

        /* Pagination */
        .pagination {
            margin-top: 25px;
            display: flex;
            justify-content: center;
            gap: 5px;
        }

        .page-link {
            padding: 8px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            color: #2c3e50;
            text-decoration: none;
            cursor: pointer;
        }

        .page-link.active {
            background: #3498db;
            color: white;
            border-color: #3498db;
        }

        /* User Details View */
        .user-detail-card {
            text-align: center;
            padding: 20px;
        }

        .detail-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 40px;
            font-weight: 600;
            margin: 0 auto 20px;
        }

        .detail-row {
            display: flex;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .detail-label {
            width: 120px;
            font-weight: 600;
            color: #7f8c8d;
        }

        .detail-value {
            flex: 1;
            color: #2c3e50;
        }

        .field-error {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: -8px;
            margin-bottom: 10px;
            display: block;
        }

        .error-border {
            border-color: #dc3545 !important;
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
            <a href="bookings.php">
                <i class="bi bi-calendar-check"></i>
                <span>Bookings</span>
                <span class="badge"></span>
            </a>
            <a href="users.php" class="active">
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
            <a href="../logout.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
        <!-- Header -->
        <div class="header">
            <h4><i class="bi bi-people"></i> User Management</h4>
            <div>
                <span class="date">
                    <i class="bi bi-calendar3"></i>
                    <?php echo date('d M, Y'); ?>
                </span>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stats-card">
                <div class="stats-icon total"><i class="bi bi-people"></i></div>
                <div class="stats-info">
                    <h3><?php echo $total_users; ?></h3>
                    <p>Total Users</p>
                </div>
            </div>
            <div class="stats-card">
                <div class="stats-icon active"><i class="bi bi-check-circle"></i></div>
                <div class="stats-info">
                    <h3><?php echo $active_users; ?></h3>
                    <p>Active Users</p>
                </div>
            </div>
            <div class="stats-card">
                <div class="stats-icon inactive"><i class="bi bi-x-circle"></i></div>
                <div class="stats-info">
                    <h3><?php echo $inactive_users; ?></h3>
                    <p>Inactive</p>
                </div>
            </div>
            <div class="stats-card">
                <div class="stats-icon new"><i class="bi bi-person-plus"></i></div>
                <div class="stats-info">
                    <h3><?php echo $new_this_month; ?></h3>
                    <p>New This Month</p>
                </div>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="action-bar">
            <button class="add-user-btn" onclick="openAddUserModal()">
                <i class="bi bi-person-plus"></i> Add New User
            </button>

            <select class="filter-select" id="statusFilter" onchange="filterUsers()">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>

            <select class="filter-select" id="roleFilter" onchange="filterUsers()">
                <option value="">All Roles</option>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>

            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" placeholder="Search by name, email or phone..."
                    onkeyup="filterUsers()">
            </div>

            <button class="export-btn" onclick="exportUsers()">
                <i class="bi bi-download"></i> Export
            </button>

            <button class="btn btn-outline-secondary" onclick="resetFilters()">
                <i class="bi bi-arrow-repeat"></i> Reset
            </button>
        </div>

        <!-- Users Table -->
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Contact</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th>Last Active</th>
                        <th>Bookings</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    <!-- Users will be loaded here -->
                </tbody>
            </table>
            <div id="emptyState" class="text-center py-5" style="display: none;">
                <i class="bi bi-person-x" style="font-size: 48px; color: #ccc;"></i>
                <h5 class="mt-3">No users found</h5>
                <p class="text-muted">Try adjusting your filters or add a new user</p>
            </div>

            <!-- Pagination -->
            <div class="pagination" id="pagination"></div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-person-plus me-2"></i>Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="addName" required>

                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="addEmail" required>

                        <label class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="addPhone" required>

                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" id="addPassword" onkeyup="checkPasswordStrength()"
                            required>
                        <div class="password-strength">
                            <div class="password-strength-bar" id="passwordStrength"></div>
                        </div>

                        <label class="form-label">Role</label>
                        <select class="form-select" id="addRole">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>

                        <label class="form-label">Status</label>
                        <select class="form-select" id="addStatus">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addUser()">Add User</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm">
                        <input type="hidden" id="editUserId">

                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="editName" required>

                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="editEmail" required>

                        <label class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="editPhone" required>

                        <label class="form-label">Role</label>
                        <select class="form-select" id="editRole">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>

                        <label class="form-label">Status</label>
                        <select class="form-select" id="editStatus">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="updateUser()">Update User</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View User Modal -->
    <div class="modal fade" id="viewUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-person-circle me-2"></i>User Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="viewUserDetails">
                    <!-- User details will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete User Modal -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Delete User</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this user?</p>
                    <p class="text-danger"><small>This action cannot be undone! All user data will be lost.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete User</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Sample users data
        let users = <?php echo json_encode($users_json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;

        let currentDeleteId = null;

        // Initialize on page load
        window.onload = function () {
            displayUsers(users);

            // Check if action=add in URL
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('action') === 'add') {
                openAddUserModal();
            }
        };

        // Display users in table
        function displayUsers(usersToShow) {
            const tbody = document.getElementById('usersTableBody');
            const emptyState = document.getElementById('emptyState');

            if (usersToShow.length === 0) {
                tbody.innerHTML = '';
                emptyState.style.display = 'block';
                return;
            }

            emptyState.style.display = 'none';
            let html = '';

            usersToShow.forEach(user => {
                let statusClass = user.status === 'active' ? 'badge-active' : 'badge-inactive';
                let statusText = user.status.charAt(0).toUpperCase() + user.status.slice(1);
                let roleBadge = user.role === 'admin' ? '<span class="badge-status badge-verified ms-2">Admin</span>' : '';

                html += `
                    <tr>
                        <td>
                            <div class="user-info">
                                <div class="user-avatar">${getInitials(user.name)}</div>
                                <div class="user-details">
                                    <strong>${user.name}</strong> ${roleBadge}<br>
                                    <small>ID: #${user.id}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>${user.email}</div>
                            <small>${user.phone}</small>
                        </td>
                        <td>${user.role.charAt(0).toUpperCase() + user.role.slice(1)}</td>
                        <td>${formatDate(user.joined)}</td>
                        <td>${formatDate(user.lastActive)}</td>
                        <td><strong>${user.bookings}</strong> bookings</td>
                        <td><span class="badge-status ${statusClass}">${statusText}</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" onclick="viewUser(${user.id})" title="View">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn-icon btn-edit" onclick="openEditModal(${user.id})" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn-icon btn-delete" onclick="openDeleteModal(${user.id})" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;
        }

        // Get initials from name
        function getInitials(name) {
            return name.split(' ').map(n => n[0]).join('').toUpperCase();
        }

        // Format date
        function formatDate(dateString) {
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('en-US', options);
        }

        // Filter users
        function filterUsers() {
            const statusFilter = document.getElementById('statusFilter').value;
            const roleFilter = document.getElementById('roleFilter').value;
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();

            const filtered = users.filter(user => {
                const matchesStatus = !statusFilter || user.status === statusFilter;
                const matchesRole = !roleFilter || user.role === roleFilter;
                const matchesSearch = !searchTerm ||
                    user.name.toLowerCase().includes(searchTerm) ||
                    user.email.toLowerCase().includes(searchTerm) ||
                    user.phone.includes(searchTerm);

                return matchesStatus && matchesRole && matchesSearch;
            });

            displayUsers(filtered);
        }

        // Reset filters
        function resetFilters() {
            document.getElementById('statusFilter').value = '';
            document.getElementById('roleFilter').value = '';
            document.getElementById('searchInput').value = '';
            displayUsers(users);
        }

        // Export users (demo)
        function exportUsers() {
            alert('Export functionality will be available in the full version!');
        }

        // Open add user modal
        function openAddUserModal() {
            document.getElementById('addUserForm').reset();
            document.getElementById('passwordStrength').className = 'password-strength-bar';
            clearFormErrors('addUserForm');
            new bootstrap.Modal(document.getElementById('addUserModal')).show();
        }

        // Check password strength
        function checkPasswordStrength() {
            const password = document.getElementById('addPassword').value;
            const strengthBar = document.getElementById('passwordStrength');

            if (password.length < 6) {
                strengthBar.className = 'password-strength-bar strength-weak';
            } else if (password.length < 10) {
                strengthBar.className = 'password-strength-bar strength-medium';
            } else {
                strengthBar.className = 'password-strength-bar strength-strong';
            }
        }

        // Add new user
        function addUser() {
            if (!validateUserForm('add')) return;
            const newUser = {
                id: users.length + 1,
                name: document.getElementById('addName').value,
                email: document.getElementById('addEmail').value,
                phone: document.getElementById('addPhone').value,
                role: document.getElementById('addRole').value,
                status: document.getElementById('addStatus').value,
                joined: new Date().toISOString().split('T')[0],
                lastActive: new Date().toISOString().split('T')[0],
                bookings: 0,
                avatar: getInitials(document.getElementById('addName').value)
            };

            users.push(newUser);
            displayUsers(users);
            bootstrap.Modal.getInstance(document.getElementById('addUserModal')).hide();
            alert('User added successfully!');
        }

        // Open edit modal
        function openEditModal(id) {
            const user = users.find(u => u.id === id);

            document.getElementById('editUserId').value = user.id;
            document.getElementById('editName').value = user.name;
            document.getElementById('editEmail').value = user.email;
            document.getElementById('editPhone').value = user.phone;
            document.getElementById('editRole').value = user.role;
            document.getElementById('editStatus').value = user.status;
            clearFormErrors('editUserForm');

            new bootstrap.Modal(document.getElementById('editUserModal')).show();
        }

        // Update user
        function updateUser() {
            if (!validateUserForm('edit')) return;
            const id = parseInt(document.getElementById('editUserId').value);
            const index = users.findIndex(u => u.id === id);

            users[index] = {
                ...users[index],
                name: document.getElementById('editName').value,
                email: document.getElementById('editEmail').value,
                phone: document.getElementById('editPhone').value,
                role: document.getElementById('editRole').value,
                status: document.getElementById('editStatus').value,
                avatar: getInitials(document.getElementById('editName').value)
            };

            displayUsers(users);
            bootstrap.Modal.getInstance(document.getElementById('editUserModal')).hide();
            alert('User updated successfully!');
        }

        function clearFormErrors(formId) {
            const form = document.getElementById(formId);
            if (!form) return;
            form.querySelectorAll('.field-error').forEach(node => node.remove());
            form.querySelectorAll('.error-border').forEach(node => node.classList.remove('error-border'));
        }

        function setFieldError(inputId, message) {
            const input = document.getElementById(inputId);
            if (!input) return;
            input.classList.add('error-border');
            const err = document.createElement('span');
            err.className = 'field-error';
            err.textContent = message;
            input.insertAdjacentElement('afterend', err);
        }

        function validateUserForm(mode) {
            const prefix = mode === 'add' ? 'add' : 'edit';
            const formId = mode === 'add' ? 'addUserForm' : 'editUserForm';
            clearFormErrors(formId);

            const name = document.getElementById(prefix + 'Name').value.trim();
            const email = document.getElementById(prefix + 'Email').value.trim();
            const phone = document.getElementById(prefix + 'Phone').value.trim();
            const role = document.getElementById(prefix + 'Role').value;
            const status = document.getElementById(prefix + 'Status').value;
            const password = mode === 'add' ? document.getElementById('addPassword').value : '';

            let ok = true;
            if (!name) { setFieldError(prefix + 'Name', 'Please enter full name'); ok = false; }
            else if (!/^[a-zA-Z\s]+$/.test(name)) { setFieldError(prefix + 'Name', 'Name must contain letters only'); ok = false; }
            if (!email) { setFieldError(prefix + 'Email', 'Please enter email address'); ok = false; }
            else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { setFieldError(prefix + 'Email', 'Please enter a valid email'); ok = false; }
            if (!phone) { setFieldError(prefix + 'Phone', 'Please enter phone number'); ok = false; }
            else if (!/^\d{10,15}$/.test(phone)) { setFieldError(prefix + 'Phone', 'Please enter valid 10-15 digit phone number'); ok = false; }
            if (mode === 'add' && !password) { setFieldError('addPassword', 'Please enter your password'); ok = false; }
            else if (mode === 'add' && password.length < 6) { setFieldError('addPassword', 'Password must be at least 6 characters'); ok = false; }
            if (!role) { setFieldError(prefix + 'Role', 'Please select role'); ok = false; }
            if (!status) { setFieldError(prefix + 'Status', 'Please select status'); ok = false; }
            return ok;
        }

        // View user details
        function viewUser(id) {
            const user = users.find(u => u.id === id);

            const details = `
                <div class="user-detail-card">
                    <div class="detail-avatar">${getInitials(user.name)}</div>
                    <h4>${user.name}</h4>
                    <span class="badge-status ${user.status === 'active' ? 'badge-active' : 'badge-inactive'}">
                        ${user.status.toUpperCase()}
                    </span>
                    ${user.role === 'admin' ? '<span class="badge-status badge-verified ms-2">ADMIN</span>' : ''}
                </div>
                <hr>
                <div class="detail-row">
                    <div class="detail-label">User ID:</div>
                    <div class="detail-value">#${user.id}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Email:</div>
                    <div class="detail-value">${user.email}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Phone:</div>
                    <div class="detail-value">${user.phone}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Joined:</div>
                    <div class="detail-value">${formatDate(user.joined)}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Last Active:</div>
                    <div class="detail-value">${formatDate(user.lastActive)}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Total Bookings:</div>
                    <div class="detail-value"><strong>${user.bookings}</strong></div>
                </div>
            `;

            document.getElementById('viewUserDetails').innerHTML = details;
            new bootstrap.Modal(document.getElementById('viewUserModal')).show();
        }

        // Open delete modal
        function openDeleteModal(id) {
            currentDeleteId = id;
            new bootstrap.Modal(document.getElementById('deleteUserModal')).show();
        }

        // Confirm delete
        document.getElementById('confirmDeleteBtn').onclick = function () {
            users = users.filter(u => u.id !== currentDeleteId);
            displayUsers(users);
            bootstrap.Modal.getInstance(document.getElementById('deleteUserModal')).hide();
            alert('User deleted successfully!');
        };
    </script>
</body>

</html>