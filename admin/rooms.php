<?php
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/room_helpers.php';
if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: ../login.php');
    exit;
}
$rr = $mysqli->query('SELECT id, room_name, room_type, price, capacity, description, image, status FROM rooms ORDER BY id ASC');
$rooms_json = [];
if ($rr) {
    while ($r = $rr->fetch_assoc()) {
        $rooms_json[] = [
            'id' => (int) $r['id'],
            'name' => $r['room_name'],
            'type' => $r['room_type'],
            'price' => (float) $r['price'],
            'adult' => (int) $r['capacity'],
            'children' => 2,
            'features' => hotel_room_features((string) $r['room_type']),
            'facilities' => hotel_room_facilities((string) $r['room_type']),
            'status' => $r['status'],
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Rooms - Imperial Crown Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- jQuery Validation -->
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

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

        /* Add Room Button */
        .add-room-btn {
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
            margin-bottom: 25px;
        }

        .add-room-btn:hover {
            background: #219a52;
            transform: translateY(-2px);
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

        /* Rooms Table */
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

        .badge-status {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
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

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-edit {
            background: #3498db;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            font-size: 13px;
            cursor: pointer;
        }

        .btn-delete {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            font-size: 13px;
            cursor: pointer;
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
        }

        .modal-header.bg-success {
            background: #27ae60 !important;
        }

        .modal-header.bg-warning {
            background: #f39c12 !important;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .modal-body {
            padding: 25px;
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

        /* Validation Styles */
        .error {
            color: #e74c3c;
            font-size: 12px;
            margin-top: -10px;
            margin-bottom: 10px;
            display: block;
        }

        .form-control.error,
        .form-select.error {
            border-color: #e74c3c;
            background-color: #fdf3f2;
        }

        .form-control.valid,
        .form-select.valid {
            border-color: #27ae60;
            background-color: #f0f9f0;
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

        /* No Image Placeholder */
        .no-image {
            width: 60px;
            height: 40px;
            background: #e0e0e0;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #7f8c8d;
            font-size: 12px;
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
            <a href="rooms.php" class="active">
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
            <h4><i class="bi bi-door-open"></i> Manage Rooms</h4>
            <div>
                <span class="date">
                    <i class="bi bi-calendar3"></i>
                    <span class="current-date"></span>
                </span>
            </div>
        </div>

        <!-- Add Room Button -->
        <button class="add-room-btn" onclick="openAddModal()">
            <i class="bi bi-plus-circle"></i> Add New Room
        </button>

        <!-- Filter Bar -->
        <div class="filter-bar">
            <select class="filter-select" id="typeFilter" onchange="filterRooms()">
                <option value="">All Types</option>
                <option value="simple">Simple</option>
                <option value="delux">Delux</option>
                <option value="luxury">Luxury</option>
                <option value="executive">Executive</option>
            </select>

            <select class="filter-select" id="statusFilter" onchange="filterRooms()">
                <option value="">All Status</option>
                <option value="available">Available</option>
                <option value="booked">Booked</option>
                <option value="maintenance">Maintenance</option>
            </select>

            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" placeholder="Search rooms..." onkeyup="filterRooms()">
            </div>

            <button class="btn btn-outline-secondary" onclick="resetFilters()">
                <i class="bi bi-arrow-repeat"></i> Reset
            </button>
        </div>

        <!-- Rooms Table -->
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Room Name</th>
                        <th>Type</th>
                        <th>Price/Night</th>
                        <th>Capacity</th>
                        <th>Features</th>
                        <th>Facilities</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="roomsTableBody">
                    <!-- Rooms will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Room Modal -->
    <div class="modal fade" id="addRoomModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Add New Room</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addRoomForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Room Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="roomName" id="addName"
                                    placeholder="e.g., Delux Room 104">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Room Type <span class="text-danger">*</span></label>
                                <select class="form-select" name="roomType" id="addType">
                                    <option value="">Select Type</option>
                                    <option value="simple">Simple</option>
                                    <option value="delux">Delux</option>
                                    <option value="luxury">Luxury</option>
                                    <option value="executive">Executive</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Price (₹) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="price" id="addPrice" placeholder="300"
                                    min="1">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Adults <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="adults" id="addAdults" value="2" min="1"
                                    max="10">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Children</label>
                                <input type="number" class="form-control" name="children" id="addChildren" value="0"
                                    min="0" max="10">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Features</label>
                                <input type="text" class="form-control" name="features" id="addFeatures"
                                    placeholder="bedroom, balcony, kitchen">
                                <small class="text-muted">Comma separated values</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Facilities</label>
                                <input type="text" class="form-control" name="facilities" id="addFacilities"
                                    placeholder="Wifi, AC, TV, Geyser">
                                <small class="text-muted">Comma separated values</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select" name="status" id="addStatus">
                                    <option value="available">Available</option>
                                    <option value="booked">Booked</option>
                                    <option value="maintenance">Maintenance</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" onclick="addRoom()">Add Room</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Room Modal -->
    <div class="modal fade" id="editRoomModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Room</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editRoomForm">
                        <input type="hidden" id="editId">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Room Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="roomName" id="editName">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Room Type <span class="text-danger">*</span></label>
                                <select class="form-select" name="roomType" id="editType">
                                    <option value="simple">Simple</option>
                                    <option value="delux">Delux</option>
                                    <option value="luxury">Luxury</option>
                                    <option value="executive">Executive</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Price (₹) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="price" id="editPrice" min="1">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Adults <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="adults" id="editAdults" min="1"
                                    max="10">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Children</label>
                                <input type="number" class="form-control" name="children" id="editChildren" min="0"
                                    max="10">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Features</label>
                                <input type="text" class="form-control" name="features" id="editFeatures">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Facilities</label>
                                <input type="text" class="form-control" name="facilities" id="editFacilities">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select" name="status" id="editStatus">
                                    <option value="available">Available</option>
                                    <option value="booked">Booked</option>
                                    <option value="maintenance">Maintenance</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning" onclick="updateRoom()">Update Room</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Delete Room</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this room?</p>
                    <p class="text-danger"><small>This action cannot be undone!</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete Room</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ============================================
        // DEVELOPMENT MODE - Auto Login
        // ============================================
        let rooms = <?php echo json_encode($rooms_json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;

        // ============================================
        // JQUERY VALIDATION RULES
        // ============================================

        // Add Form Validation
        $('#addRoomForm').validate({
            rules: {
                roomName: {
                    required: true,
                    minlength: 3,
                    maxlength: 100
                },
                roomType: {
                    required: true
                },
                price: {
                    required: true,
                    number: true,
                    min: 1,
                    max: 100000
                },
                adults: {
                    required: true,
                    number: true,
                    min: 1,
                    max: 10
                },
                children: {
                    number: true,
                    min: 0,
                    max: 10
                },
                status: {
                    required: true
                }
            },
            messages: {
                roomName: {
                    required: "Please enter room name",
                    minlength: "Room name must be at least 3 characters",
                    maxlength: "Room name cannot exceed 100 characters"
                },
                roomType: {
                    required: "Please select room type"
                },
                price: {
                    required: "Please enter price",
                    number: "Please enter a valid number",
                    min: "Price must be at least ₹1",
                    max: "Price cannot exceed ₹1,00,000"
                },
                adults: {
                    required: "Please enter number of adults",
                    number: "Please enter a valid number",
                    min: "At least 1 adult required",
                    max: "Maximum 10 adults allowed"
                },
                children: {
                    number: "Please enter a valid number",
                    min: "Children cannot be negative",
                    max: "Maximum 10 children allowed"
                },
                status: {
                    required: "Please select status"
                }
            },
            errorClass: "error",
            validClass: "valid",
            errorElement: "span",
            highlight: function (element) {
                $(element).addClass("error").removeClass("valid");
            },
            unhighlight: function (element) {
                $(element).removeClass("error").addClass("valid");
            }
        });

        // Edit Form Validation
        $('#editRoomForm').validate({
            rules: {
                roomName: {
                    required: true,
                    minlength: 3,
                    maxlength: 100
                },
                roomType: {
                    required: true
                },
                price: {
                    required: true,
                    number: true,
                    min: 1,
                    max: 100000
                },
                adults: {
                    required: true,
                    number: true,
                    min: 1,
                    max: 10
                },
                children: {
                    number: true,
                    min: 0,
                    max: 10
                },
                status: {
                    required: true
                }
            },
            messages: {
                roomName: {
                    required: "Please enter room name",
                    minlength: "Room name must be at least 3 characters",
                    maxlength: "Room name cannot exceed 100 characters"
                },
                roomType: {
                    required: "Please select room type"
                },
                price: {
                    required: "Please enter price",
                    number: "Please enter a valid number",
                    min: "Price must be at least ₹1",
                    max: "Price cannot exceed ₹1,00,000"
                },
                adults: {
                    required: "Please enter number of adults",
                    number: "Please enter a valid number",
                    min: "At least 1 adult required",
                    max: "Maximum 10 adults allowed"
                },
                children: {
                    number: "Please enter a valid number",
                    min: "Children cannot be negative",
                    max: "Maximum 10 children allowed"
                },
                status: {
                    required: "Please select status"
                }
            },
            errorClass: "error",
            validClass: "valid",
            errorElement: "span",
            highlight: function (element) {
                $(element).addClass("error").removeClass("valid");
            },
            unhighlight: function (element) {
                $(element).removeClass("error").addClass("valid");
            }
        });

        // ============================================
        // DISPLAY FUNCTIONS
        // ============================================

        function displayRooms(roomsToShow) {
            const tbody = document.getElementById('roomsTableBody');
            let html = '';

            roomsToShow.forEach(room => {
                let statusClass = room.status === 'available' ? 'badge-available' :
                    room.status === 'booked' ? 'badge-booked' : 'badge-maintenance';

                let featuresHtml = room.features ? room.features.join(', ') : '-';
                let facilitiesHtml = room.facilities ? room.facilities.join(', ') : '-';

                html += `
                    <tr>
                        <td><strong>${room.id}</strong></td>
                        <td>${room.name}</td>
                        <td>${room.type.charAt(0).toUpperCase() + room.type.slice(1)}</td>
                        <td>₹${room.price}</td>
                        <td>${room.adult} Adults, ${room.children} Children</td>
                        <td>${featuresHtml}</td>
                        <td>${facilitiesHtml}</td>
                        <td><span class="badge-status ${statusClass}">${room.status.charAt(0).toUpperCase() + room.status.slice(1)}</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-edit" onclick="openEditModal(${room.id})"><i class="bi bi-pencil"></i> Edit</button>
                                <button class="btn-delete" onclick="openDeleteModal(${room.id})"><i class="bi bi-trash"></i> Delete</button>
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

        function filterRooms() {
            const typeFilter = document.getElementById('typeFilter').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();

            const filtered = rooms.filter(room => {
                const matchesType = !typeFilter || room.type === typeFilter;
                const matchesStatus = !statusFilter || room.status === statusFilter;
                const matchesSearch = !searchTerm ||
                    room.name.toLowerCase().includes(searchTerm) ||
                    room.id.toString().includes(searchTerm);

                return matchesType && matchesStatus && matchesSearch;
            });

            displayRooms(filtered);
        }

        function resetFilters() {
            document.getElementById('typeFilter').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('searchInput').value = '';
            displayRooms(rooms);
        }

        // ============================================
        // CRUD FUNCTIONS
        // ============================================

        function openAddModal() {
            $('#addRoomForm')[0].reset();
            $('#addRoomForm').validate().resetForm();
            $('.form-control, .form-select').removeClass('error valid');
            new bootstrap.Modal(document.getElementById('addRoomModal')).show();
        }

        function addRoom() {
            if (!$('#addRoomForm').valid()) {
                alert('❌ Please fill all required fields correctly');
                return;
            }
            const feat = document.getElementById('addFeatures').value.trim();
            const fac = document.getElementById('addFacilities').value.trim();
            const desc = 'Features: ' + feat + ' | Facilities: ' + fac;
            fetch('../api/admin/rooms_manage.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'create',
                    room_name: document.getElementById('addName').value.trim(),
                    room_type: document.getElementById('addType').value,
                    price: parseFloat(document.getElementById('addPrice').value),
                    capacity: parseInt(document.getElementById('addAdults').value, 10),
                    description: desc,
                    image: '',
                    status: document.getElementById('addStatus').value
                })
            })
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        bootstrap.Modal.getInstance(document.getElementById('addRoomModal')).hide();
                        location.reload();
                    } else {
                        alert(d.message || 'Could not add room');
                    }
                })
                .catch(() => alert('Network error'));
        }

        function openEditModal(id) {
            const room = rooms.find(r => r.id === id);

            document.getElementById('editId').value = room.id;
            document.getElementById('editName').value = room.name;
            document.getElementById('editType').value = room.type;
            document.getElementById('editPrice').value = room.price;
            document.getElementById('editAdults').value = room.adult;
            document.getElementById('editChildren').value = room.children;
            document.getElementById('editFeatures').value = room.features ? room.features.join(', ') : '';
            document.getElementById('editFacilities').value = room.facilities ? room.facilities.join(', ') : '';
            document.getElementById('editStatus').value = room.status;

            $('#editRoomForm').validate().resetForm();
            $('.form-control, .form-select').removeClass('error valid');

            new bootstrap.Modal(document.getElementById('editRoomModal')).show();
        }

        function updateRoom() {
            if (!$('#editRoomForm').valid()) {
                alert('❌ Please fill all required fields correctly');
                return;
            }
            const id = parseInt(document.getElementById('editId').value, 10);
            const feat = document.getElementById('editFeatures').value.trim();
            const fac = document.getElementById('editFacilities').value.trim();
            const desc = 'Features: ' + feat + ' | Facilities: ' + fac;
            fetch('../api/admin/rooms_manage.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'update',
                    id: id,
                    room_name: document.getElementById('editName').value.trim(),
                    room_type: document.getElementById('editType').value,
                    price: parseFloat(document.getElementById('editPrice').value),
                    capacity: parseInt(document.getElementById('editAdults').value, 10),
                    description: desc,
                    image: '',
                    status: document.getElementById('editStatus').value
                })
            })
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        bootstrap.Modal.getInstance(document.getElementById('editRoomModal')).hide();
                        location.reload();
                    } else {
                        alert(d.message || 'Could not update room');
                    }
                })
                .catch(() => alert('Network error'));
        }

        function openDeleteModal(id) {
            window.currentDeleteId = id;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }

        document.getElementById('confirmDeleteBtn').onclick = function () {
            if (!window.currentDeleteId) return;
            fetch('../api/admin/rooms_manage.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'delete', id: window.currentDeleteId })
            })
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
                        location.reload();
                    } else {
                        alert(d.message || 'Delete failed (room may have active bookings)');
                    }
                })
                .catch(() => alert('Network error'));
        };

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

            displayRooms(rooms);
        });
    </script>
</body>

</html>