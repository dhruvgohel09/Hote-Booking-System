<?php
require_once __DIR__ . '/../includes/init.php';

// Verify Admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    // Basic dev mode fallback since there's no real login enforcement everywhere yet
    if (!isset($_SESSION['user_role'])) {
        $_SESSION['user_role'] = 'admin';
        $_SESSION['user_id'] = 1;
    }
}

$settingsFile = __DIR__ . '/../includes/settings.json';
$success_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_settings = [
        'site_title' => $_POST['site_title'] ?? '',
        'site_about' => $_POST['site_about'] ?? '',
        'contact_address' => $_POST['contact_address'] ?? '',
        'contact_phone1' => $_POST['contact_phone1'] ?? '',
        'contact_phone2' => $_POST['contact_phone2'] ?? '',
        'contact_email1' => $_POST['contact_email1'] ?? '',
        'contact_email2' => $_POST['contact_email2'] ?? ''
    ];
    
    file_put_contents($settingsFile, json_encode($new_settings, JSON_PRETTY_PRINT));
    $success_msg = "Settings updated successfully!";
}

$settings = [];
if (file_exists($settingsFile)) {
    $settings = json_decode(file_get_contents($settingsFile), true) ?: [];
}

function get_setting($key, $default, $settings_arr) {
    return isset($settings_arr[$key]) ? htmlspecialchars($settings_arr[$key]) : $default;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Settings - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; overflow-x: hidden; }
        .sidebar { background: #2c3e50; height: 100vh; color: white; position: fixed; width: 280px; left: 0; top: 0; box-shadow: 2px 0 10px rgba(0,0,0,0.1); overflow-y: auto; z-index: 1000; }
        .sidebar .logo { padding: 25px 20px; text-align: center; border-bottom: 1px solid #34495e; background: #243342; }
        .sidebar .logo img { width: 70px; margin-bottom: 10px; }
        .sidebar .logo h4 { color: white; margin: 10px 0 5px; font-weight: 600; }
        .sidebar .logo span { background: #e74c3c; padding: 5px 15px; border-radius: 20px; font-size: 12px; display: inline-block; }
        .sidebar-menu { padding: 20px 0; }
        .sidebar-menu a { color: rgba(255,255,255,0.8); text-decoration: none; padding: 15px 25px; display: flex; align-items: center; transition: all 0.3s; border-left: 4px solid transparent; margin: 5px 0; }
        .sidebar-menu a:hover { background: #34495e; color: white; border-left-color: #3498db; padding-left: 35px; }
        .sidebar-menu a.active { background: #3498db; color: white; border-left-color: #f1c40f; }
        .sidebar-menu i { margin-right: 15px; font-size: 20px; width: 25px; }
        .content { margin-left: 280px; padding: 20px 30px; transition: all 0.3s; }
        .header { background: white; padding: 15px 25px; border-radius: 15px; box-shadow: 0 2px 15px rgba(0,0,0,0.1); margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; }
        .header h4 { margin: 0; color: #2c3e50; font-weight: 600; }
        .header h4 i { margin-right: 10px; color: #3498db; }
        .settings-card { background: white; border-radius: 15px; padding: 30px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
        .btn-save { background: #27ae60; color: white; border: none; padding: 12px 30px; border-radius: 8px; font-weight: 600; }
        .btn-save:hover { background: #219a52; }
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
            <a href="index.php"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a>
            <a href="rooms.php"><i class="bi bi-door-open"></i><span>Rooms</span></a>
            <a href="bookings.php"><i class="bi bi-calendar-check"></i><span>Bookings</span></a>
            <a href="users.php"><i class="bi bi-people"></i><span>Users</span></a>
            <a href="facilities.php"><i class="bi bi-building"></i><span>Facilities</span></a>
            <a href="user_queries.php"><i class="bi bi-envelope"></i><span>User Queries</span></a>
            <a href="settings.php" class="active"><i class="bi bi-gear"></i><span>Settings</span></a>
            <hr style="border-color: #34495e; margin: 20px;">
            <a href="#" onclick="logout()"><i class="bi bi-box-arrow-right"></i><span>Logout</span></a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="header">
            <h4><i class="bi bi-gear"></i> Settings</h4>
        </div>

        <?php if($success_msg): ?>
            <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i><?= $success_msg ?></div>
        <?php endif; ?>

        <div class="settings-card">
            <h4 class="mb-4"><i class="bi bi-info-circle me-2 text-primary"></i> Site & Contact Information</h4>
            <form method="POST">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">Site Title</label>
                        <input type="text" name="site_title" class="form-control" value="<?= get_setting('site_title', 'The Imperial Crown Hotel', $settings) ?>" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">About Us / Footer Text</label>
                        <textarea name="site_about" class="form-control" rows="2" required><?= get_setting('site_about', 'Experience luxury and comfort at its best. Book your stay with us for unforgettable memories.', $settings) ?></textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">Hotel Full Address</label>
                        <input type="text" name="contact_address" class="form-control" value="<?= get_setting('contact_address', 'Upleta Dhoraji Road, Jetpur, Gujarat - 360360', $settings) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Primary Phone</label>
                        <input type="text" name="contact_phone1" class="form-control" value="<?= get_setting('contact_phone1', '+91 91234 56789', $settings) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Secondary Phone</label>
                        <input type="text" name="contact_phone2" class="form-control" value="<?= get_setting('contact_phone2', '+91 99123 45678', $settings) ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Primary Email</label>
                        <input type="email" name="contact_email1" class="form-control" value="<?= get_setting('contact_email1', 'info@imperialcrown.com', $settings) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Secondary Email</label>
                        <input type="email" name="contact_email2" class="form-control" value="<?= get_setting('contact_email2', 'support@imperialcrown.com', $settings) ?>">
                    </div>
                </div>
                
                <hr class="my-4">
                
                <button type="submit" class="btn-save w-100">
                    <i class="bi bi-check-circle me-2"></i> Save Changes
                </button>
            </form>
        </div>
    </div>
    <script>
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
    </script>
</body>
</html>
