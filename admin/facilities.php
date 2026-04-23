<?php
// admin/facilities.php - Complete Facilities Management
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facilities Management - Imperial Crown Hotel</title>
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

        .stats-icon.popular {
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

        .add-facility-btn {
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

        .add-facility-btn:hover {
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

        .category-tabs {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .category-tab {
            padding: 8px 20px;
            border-radius: 20px;
            background: #f8f9fa;
            border: 2px solid #e0e0e0;
            cursor: pointer;
            transition: all 0.3s;
        }

        .category-tab.active {
            background: #3498db;
            color: white;
            border-color: #3498db;
        }

        /* Facilities Grid */
        .facilities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .facility-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .facility-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .facility-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #3498db, #27ae60);
        }

        .facility-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            color: white;
            font-size: 36px;
        }

        .facility-icon img {
            width: 50px;
            height: 50px;
            filter: brightness(0) invert(1);
        }

        .facility-name {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
            text-align: center;
        }

        .facility-category {
            text-align: center;
            margin-bottom: 10px;
        }

        .category-badge {
            background: #e0e0e0;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            color: #666;
        }

        .facility-description {
            color: #7f8c8d;
            font-size: 14px;
            text-align: center;
            margin-bottom: 15px;
            line-height: 1.4;
        }

        .facility-stats {
            display: flex;
            justify-content: space-around;
            margin: 15px 0;
            padding: 10px 0;
            border-top: 1px solid #f0f0f0;
            border-bottom: 1px solid #f0f0f0;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-weight: 600;
            color: #2c3e50;
        }

        .stat-label {
            font-size: 11px;
            color: #7f8c8d;
        }

        .facility-status {
            text-align: center;
            margin-bottom: 15px;
        }

        .badge-status {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-available {
            background: #d4edda;
            color: #155724;
        }

        .badge-maintenance {
            background: #fff3cd;
            color: #856404;
        }

        .badge-unavailable {
            background: #f8d7da;
            color: #721c24;
        }

        .facility-actions {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin-top: 15px;
        }

        .btn-action {
            width: 40px;
            height: 40px;
            border-radius: 10px;
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

        .btn-action:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
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

        .icon-preview {
            width: 100px;
            height: 100px;
            border-radius: 10px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 10px auto;
            border: 2px dashed #e0e0e0;
        }

        .icon-preview img {
            width: 60px;
            height: 60px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .empty-state i {
            font-size: 60px;
            color: #ccc;
            margin-bottom: 20px;
        }

        .empty-state h5 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #7f8c8d;
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
            <a href="users.php">
                <i class="bi bi-people"></i>
                <span>Users</span>
                <span class="badge"></span>
            </a>
            <a href="facilities.php" class="active">
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
            <h4><i class="bi bi-building"></i> Facilities Management</h4>
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
                <div class="stats-icon total"><i class="bi bi-building"></i></div>
                <div class="stats-info">
                    <h3 id="statTotalFacilities">6</h3>
                    <p>Total Facilities</p>
                </div>
            </div>
            <div class="stats-card">
                <div class="stats-icon active"><i class="bi bi-check-circle"></i></div>
                <div class="stats-info">
                    <h3 id="statAvailableFacilities">6</h3>
                    <p>Available</p>
                </div>
            </div>
            <div class="stats-card">
                <div class="stats-icon popular"><i class="bi bi-star"></i></div>
                <div class="stats-info">
                    <h3>3</h3>
                    <p>Most Popular</p>
                </div>
            </div>
            <div class="stats-card">
                <div class="stats-icon new"><i class="bi bi-clock"></i></div>
                <div class="stats-info">
                    <h3>24/7</h3>
                    <p>Open Hours</p>
                </div>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="action-bar">
            <button class="add-facility-btn" onclick="openAddFacilityModal()">
                <i class="bi bi-plus-circle"></i> Add New Facility
            </button>

            <div class="category-tabs">
                <span class="category-tab active" onclick="filterByCategory('all')">All</span>
                <span class="category-tab" onclick="filterByCategory('indoor')">Indoor</span>
                <span class="category-tab" onclick="filterByCategory('outdoor')">Outdoor</span>
                <span class="category-tab" onclick="filterByCategory('wellness')">Wellness</span>
                <span class="category-tab" onclick="filterByCategory('dining')">Dining</span>
            </div>

            <select class="filter-select" id="statusFilter" onchange="filterFacilities()">
                <option value="">All Status</option>
                <option value="available">Available</option>
                <option value="maintenance">Maintenance</option>
                <option value="unavailable">Unavailable</option>
            </select>

            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" placeholder="Search facilities..." onkeyup="filterFacilities()">
            </div>
        </div>

        <!-- Facilities Grid -->
        <div id="facilitiesContainer">
            <!-- Facilities will be loaded here -->
        </div>
    </div>

    <!-- Add Facility Modal -->
    <div class="modal fade" id="addFacilityModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Add New Facility</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addFacilityForm">
                        <label class="form-label">Facility Name</label>
                        <input type="text" class="form-control" id="addName" placeholder="e.g., Swimming Pool" required>

                        <label class="form-label">Category</label>
                        <select class="form-select" id="addCategory" required>
                            <option value="">Select Category</option>
                            <option value="indoor">Indoor</option>
                            <option value="outdoor">Outdoor</option>
                            <option value="wellness">Wellness</option>
                            <option value="dining">Dining</option>
                            <option value="other">Other</option>
                        </select>

                        <label class="form-label">Description</label>
                        <textarea class="form-control" id="addDescription" rows="3"
                            placeholder="Describe the facility..." required></textarea>

                        <label class="form-label">Icon URL</label>
                        <input type="url" class="form-control" id="addIcon"
                            placeholder="https://cdn-icons-png.flaticon.com/512/..." onchange="previewAddIcon()"
                            required>
                        <div class="icon-preview" id="addIconPreview">
                            <img src="https://cdn-icons-png.flaticon.com/512/1041/1041916.png" alt="Icon Preview">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select class="form-select" id="addStatus" required>
                                    <option value="available">Available</option>
                                    <option value="maintenance">Maintenance</option>
                                    <option value="unavailable">Unavailable</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Open Hours</label>
                                <input type="text" class="form-control" id="addHours" placeholder="24/7 or 8AM-10PM"
                                    required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Capacity</label>
                                <input type="number" class="form-control" id="addCapacity" placeholder="e.g., 50"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Location</label>
                                <input type="text" class="form-control" id="addLocation"
                                    placeholder="e.g., Ground Floor" required>
                            </div>
                        </div>

                        <label class="form-label">Additional Info</label>
                        <input type="text" class="form-control" id="addInfo"
                            placeholder="Free for guests, charges apply for visitors">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addFacility()">Add Facility</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Facility Modal -->
    <div class="modal fade" id="editFacilityModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Facility</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editFacilityForm">
                        <input type="hidden" id="editFacilityId">

                        <label class="form-label">Facility Name</label>
                        <input type="text" class="form-control" id="editName" required>

                        <label class="form-label">Category</label>
                        <select class="form-select" id="editCategory" required>
                            <option value="indoor">Indoor</option>
                            <option value="outdoor">Outdoor</option>
                            <option value="wellness">Wellness</option>
                            <option value="dining">Dining</option>
                            <option value="other">Other</option>
                        </select>

                        <label class="form-label">Description</label>
                        <textarea class="form-control" id="editDescription" rows="3" required></textarea>

                        <label class="form-label">Icon URL</label>
                        <input type="url" class="form-control" id="editIcon" onchange="previewEditIcon()" required>
                        <div class="icon-preview" id="editIconPreview">
                            <img src="" alt="Icon Preview">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select class="form-select" id="editStatus" required>
                                    <option value="available">Available</option>
                                    <option value="maintenance">Maintenance</option>
                                    <option value="unavailable">Unavailable</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Open Hours</label>
                                <input type="text" class="form-control" id="editHours" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Capacity</label>
                                <input type="number" class="form-control" id="editCapacity" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Location</label>
                                <input type="text" class="form-control" id="editLocation" required>
                            </div>
                        </div>

                        <label class="form-label">Additional Info</label>
                        <input type="text" class="form-control" id="editInfo">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="updateFacility()">Update Facility</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Facility Modal -->
    <div class="modal fade" id="viewFacilityModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-eye me-2"></i>Facility Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="viewFacilityDetails">
                    <!-- Details will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Facility Modal -->
    <div class="modal fade" id="deleteFacilityModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Delete Facility</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this facility?</p>
                    <p class="text-danger"><small>This action cannot be undone!</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteFacilityBtn">Delete Facility</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Facilities data
        let facilities = [
            {
                id: 16,
                name: 'Geyser',
                category: 'indoor',
                description: '24/7 hot and cold water facility.',
                icon: 'https://cdn-icons-png.flaticon.com/512/3105/3105838.png', 
                status: 'available',
                hours: '24/7',
                capacity: 1,
                location: 'All Rooms',
                info: 'Included with room',
                usage: 50,
                rating: 4.5,
                lastMaintenance: '2026-03-01'
            },
            {
                id: 17,
                name: 'Television',
                category: 'indoor',
                description: 'Flat screen smart TV with satellite channels.',
                icon: 'https://cdn-icons-png.flaticon.com/512/3170/3170733.png', 
                status: 'available',
                hours: '24/7',
                capacity: 1,
                location: 'All Rooms',
                info: 'Smart TV features available',
                usage: 300,
                rating: 4.8,
                lastMaintenance: '2026-02-15'
            },
            {
                id: 18,
                name: 'Wifi',
                category: 'indoor',
                description: 'High speed wireless internet access.',
                icon: 'https://cdn-icons-png.flaticon.com/512/2966/2966327.png', 
                status: 'available',
                hours: '24/7',
                capacity: 100,
                location: 'Entire Hotel',
                info: 'Free for guests',
                usage: 800,
                rating: 4.9,
                lastMaintenance: '2026-03-10'
            },
            {
                id: 19,
                name: 'Air Conditioning',
                category: 'indoor',
                description: 'Individual climate control for your comfort.',
                icon: 'https://cdn-icons-png.flaticon.com/512/2917/2917996.png', 
                status: 'available',
                hours: '24/7',
                capacity: 1,
                location: 'All Rooms',
                info: 'Remote controlled AC',
                usage: 450,
                rating: 4.7,
                lastMaintenance: '2026-03-05'
            },
            {
                id: 20,
                name: 'Room Heater',
                category: 'indoor',
                description: 'Keep your room warm and cozy.',
                icon: 'https://cdn-icons-png.flaticon.com/512/3144/3144456.png', 
                status: 'available',
                hours: '24/7',
                capacity: 1,
                location: 'Executive Rooms',
                info: 'Available on request',
                usage: 120,
                rating: 4.6,
                lastMaintenance: '2026-01-20'
            },
            {
                id: 22,
                name: 'Spa',
                category: 'wellness',
                description: 'Luxury spa with massage rooms.',
                icon: 'https://cdn-icons-png.flaticon.com/512/2970/2970785.png', 
                status: 'available',
                hours: '9AM - 9PM',
                capacity: 20,
                location: 'Second Floor, Spa Wing',
                info: 'Advance booking required',
                usage: 89,
                rating: 4.9,
                lastMaintenance: '2026-02-10'
            }
        ];

        let currentDeleteId = null;
        let currentCategory = 'all';

        // Initialize on page load
        window.onload = function () {
            displayFacilities(facilities);

            // Check if action=add in URL
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('action') === 'add') {
                openAddFacilityModal();
            }
        };

        // Display facilities in grid
        function displayFacilities(facilitiesToShow) {
            const container = document.getElementById('facilitiesContainer');

            if (facilitiesToShow.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="bi bi-building"></i>
                        <h5>No facilities found</h5>
                        <p>Try adjusting your filters or add a new facility</p>
                        <button class="add-facility-btn" onclick="openAddFacilityModal()">
                            <i class="bi bi-plus-circle"></i> Add New Facility
                        </button>
                    </div>
                `;
                return;
            }

            let html = '<div class="facilities-grid">';

            facilitiesToShow.forEach(facility => {
                let statusClass = facility.status === 'available' ? 'badge-available' :
                    facility.status === 'maintenance' ? 'badge-maintenance' : 'badge-unavailable';
                let statusText = facility.status.charAt(0).toUpperCase() + facility.status.slice(1);
                let categoryText = facility.category.charAt(0).toUpperCase() + facility.category.slice(1);

                html += `
                    <div class="facility-card">
                        <div class="facility-icon">
                            <img src="${facility.icon}" alt="${facility.name}">
                        </div>
                        <h3 class="facility-name">${facility.name}</h3>
                        <div class="facility-category">
                            <span class="category-badge">${categoryText}</span>
                        </div>
                        <p class="facility-description">${facility.description.substring(0, 60)}...</p>
                        <div class="facility-stats">
                            <div class="stat-item">
                                <div class="stat-value">${facility.usage}</div>
                                <div class="stat-label">Uses</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">${facility.rating}</div>
                                <div class="stat-label">Rating</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">${facility.capacity}</div>
                                <div class="stat-label">Capacity</div>
                            </div>
                        </div>
                        <div class="facility-status">
                            <span class="badge-status ${statusClass}">${statusText}</span>
                        </div>
                        <div class="facility-actions">
                            <button class="btn-action btn-view" onclick="viewFacility(${facility.id})" title="View">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="btn-action btn-edit" onclick="openEditModal(${facility.id})" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn-action btn-delete" onclick="openDeleteModal(${facility.id})" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
            });

            html += '</div>';
            container.innerHTML = html;
        }

        // Filter facilities
        function filterFacilities() {
            const statusFilter = document.getElementById('statusFilter').value;
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();

            const filtered = facilities.filter(facility => {
                const matchesCategory = currentCategory === 'all' || facility.category === currentCategory;
                const matchesStatus = !statusFilter || facility.status === statusFilter;
                const matchesSearch = !searchTerm ||
                    facility.name.toLowerCase().includes(searchTerm) ||
                    facility.description.toLowerCase().includes(searchTerm) ||
                    facility.location.toLowerCase().includes(searchTerm);

                return matchesCategory && matchesStatus && matchesSearch;
            });

            displayFacilities(filtered);
        }

        // Filter by category
        function filterByCategory(category) {
            currentCategory = category;

            // Update active tab
            document.querySelectorAll('.category-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            event.target.classList.add('active');

            filterFacilities();
        }

        // Reset filters
        function resetFilters() {
            document.getElementById('statusFilter').value = '';
            document.getElementById('searchInput').value = '';
            currentCategory = 'all';

            document.querySelectorAll('.category-tab').forEach(tab => {
                tab.classList.remove('active');
                if (tab.textContent === 'All') {
                    tab.classList.add('active');
                }
            });

            displayFacilities(facilities);
        }

        // Open add facility modal
        function openAddFacilityModal() {
            document.getElementById('addFacilityForm').reset();
            document.getElementById('addIconPreview').innerHTML = '<img src="https://cdn-icons-png.flaticon.com/512/1041/1041916.png" alt="Icon Preview">';
            clearFormErrors('addFacilityForm');
            new bootstrap.Modal(document.getElementById('addFacilityModal')).show();
        }

        // Preview add icon
        function previewAddIcon() {
            const url = document.getElementById('addIcon').value;
            if (url) {
                document.getElementById('addIconPreview').innerHTML = `<img src="${url}" alt="Icon Preview">`;
            }
        }

        // Preview edit icon
        function previewEditIcon() {
            const url = document.getElementById('editIcon').value;
            if (url) {
                document.getElementById('editIconPreview').innerHTML = `<img src="${url}" alt="Icon Preview">`;
            }
        }

        // Add new facility
        function addFacility() {
            if (!validateFacilityForm('add')) return;
            const newFacility = {
                id: facilities.length + 1,
                name: document.getElementById('addName').value,
                category: document.getElementById('addCategory').value,
                description: document.getElementById('addDescription').value,
                icon: document.getElementById('addIcon').value,
                status: document.getElementById('addStatus').value,
                hours: document.getElementById('addHours').value,
                capacity: parseInt(document.getElementById('addCapacity').value),
                location: document.getElementById('addLocation').value,
                info: document.getElementById('addInfo').value,
                usage: 0,
                rating: 5.0,
                lastMaintenance: new Date().toISOString().split('T')[0]
            };

            facilities.push(newFacility);
            displayFacilities(facilities);
            bootstrap.Modal.getInstance(document.getElementById('addFacilityModal')).hide();
            alert('Facility added successfully!');
        }

        // Open edit modal
        function openEditModal(id) {
            const facility = facilities.find(f => f.id === id);

            document.getElementById('editFacilityId').value = facility.id;
            document.getElementById('editName').value = facility.name;
            document.getElementById('editCategory').value = facility.category;
            document.getElementById('editDescription').value = facility.description;
            document.getElementById('editIcon').value = facility.icon;
            document.getElementById('editStatus').value = facility.status;
            document.getElementById('editHours').value = facility.hours;
            document.getElementById('editCapacity').value = facility.capacity;
            document.getElementById('editLocation').value = facility.location;
            document.getElementById('editInfo').value = facility.info;

            document.getElementById('editIconPreview').innerHTML = `<img src="${facility.icon}" alt="Icon Preview">`;
            clearFormErrors('editFacilityForm');

            new bootstrap.Modal(document.getElementById('editFacilityModal')).show();
        }

        // Update facility
        function updateFacility() {
            if (!validateFacilityForm('edit')) return;
            const id = parseInt(document.getElementById('editFacilityId').value);
            const index = facilities.findIndex(f => f.id === id);

            facilities[index] = {
                ...facilities[index],
                name: document.getElementById('editName').value,
                category: document.getElementById('editCategory').value,
                description: document.getElementById('editDescription').value,
                icon: document.getElementById('editIcon').value,
                status: document.getElementById('editStatus').value,
                hours: document.getElementById('editHours').value,
                capacity: parseInt(document.getElementById('editCapacity').value),
                location: document.getElementById('editLocation').value,
                info: document.getElementById('editInfo').value
            };

            displayFacilities(facilities);
            bootstrap.Modal.getInstance(document.getElementById('editFacilityModal')).hide();
            alert('Facility updated successfully!');
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

        function validateFacilityForm(mode) {
            const prefix = mode === 'add' ? 'add' : 'edit';
            const formId = mode === 'add' ? 'addFacilityForm' : 'editFacilityForm';
            clearFormErrors(formId);

            const name = document.getElementById(prefix + 'Name').value.trim();
            const category = document.getElementById(prefix + 'Category').value;
            const desc = document.getElementById(prefix + 'Description').value.trim();
            const icon = document.getElementById(prefix + 'Icon').value.trim();
            const status = document.getElementById(prefix + 'Status').value;
            const hours = document.getElementById(prefix + 'Hours').value.trim();
            const capacity = document.getElementById(prefix + 'Capacity').value.trim();
            const location = document.getElementById(prefix + 'Location').value.trim();

            let ok = true;
            if (!name) { setFieldError(prefix + 'Name', 'Please enter facility name'); ok = false; }
            if (!category) { setFieldError(prefix + 'Category', 'Please select category'); ok = false; }
            if (!desc) { setFieldError(prefix + 'Description', 'Please enter description'); ok = false; }
            if (!icon) { setFieldError(prefix + 'Icon', 'Please enter icon URL'); ok = false; }
            else if (!/^https?:\/\/.+/i.test(icon)) { setFieldError(prefix + 'Icon', 'Please enter a valid URL'); ok = false; }
            if (!status) { setFieldError(prefix + 'Status', 'Please select status'); ok = false; }
            if (!hours) { setFieldError(prefix + 'Hours', 'Please enter open hours'); ok = false; }
            if (!capacity) { setFieldError(prefix + 'Capacity', 'Please enter capacity'); ok = false; }
            else if (!(parseInt(capacity, 10) > 0)) { setFieldError(prefix + 'Capacity', 'Capacity must be greater than 0'); ok = false; }
            if (!location) { setFieldError(prefix + 'Location', 'Please enter location'); ok = false; }
            return ok;
        }

        // View facility details
        function viewFacility(id) {
            const facility = facilities.find(f => f.id === id);

            const statusClass = facility.status === 'available' ? 'badge-available' :
                facility.status === 'maintenance' ? 'badge-maintenance' : 'badge-unavailable';
            const statusText = facility.status.charAt(0).toUpperCase() + facility.status.slice(1);
            const categoryText = facility.category.charAt(0).toUpperCase() + facility.category.slice(1);

            const details = `
                <div class="text-center mb-4">
                    <div class="facility-icon" style="margin: 0 auto 15px;">
                        <img src="${facility.icon}" alt="${facility.name}">
                    </div>
                    <h4>${facility.name}</h4>
                    <span class="category-badge">${categoryText}</span>
                    <span class="badge-status ${statusClass} ms-2">${statusText}</span>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6"><strong>Description:</strong></div>
                    <div class="col-6">${facility.description}</div>
                </div>
                <div class="row mt-2">
                    <div class="col-6"><strong>Location:</strong></div>
                    <div class="col-6">${facility.location}</div>
                </div>
                <div class="row mt-2">
                    <div class="col-6"><strong>Open Hours:</strong></div>
                    <div class="col-6">${facility.hours}</div>
                </div>
                <div class="row mt-2">
                    <div class="col-6"><strong>Capacity:</strong></div>
                    <div class="col-6">${facility.capacity} people</div>
                </div>
                <div class="row mt-2">
                    <div class="col-6"><strong>Usage:</strong></div>
                    <div class="col-6">${facility.usage} times</div>
                </div>
                <div class="row mt-2">
                    <div class="col-6"><strong>Rating:</strong></div>
                    <div class="col-6">⭐ ${facility.rating}/5</div>
                </div>
                <div class="row mt-2">
                    <div class="col-6"><strong>Last Maintenance:</strong></div>
                    <div class="col-6">${formatDate(facility.lastMaintenance)}</div>
                </div>
                <div class="row mt-2">
                    <div class="col-6"><strong>Additional Info:</strong></div>
                    <div class="col-6">${facility.info}</div>
                </div>
            `;

            document.getElementById('viewFacilityDetails').innerHTML = details;
            new bootstrap.Modal(document.getElementById('viewFacilityModal')).show();
        }

        // Format date
        function formatDate(dateString) {
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('en-US', options);
        }

        // Open delete modal
        function openDeleteModal(id) {
            currentDeleteId = id;
            new bootstrap.Modal(document.getElementById('deleteFacilityModal')).show();
        }

        // Confirm delete
        document.getElementById('confirmDeleteFacilityBtn').onclick = function () {
            facilities = facilities.filter(f => f.id !== currentDeleteId);
            displayFacilities(facilities);
            bootstrap.Modal.getInstance(document.getElementById('deleteFacilityModal')).hide();
            alert('Facility deleted successfully!');
        };
    </script>
</body>

</html>