<?php
require_once __DIR__ . '/../includes/init.php';
if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: ../login.php');
    exit;
}

// Fetch Queries
$query = "SELECT * FROM contact ORDER BY created_at DESC";
$res = $mysqli->query($query);
$queriesData = [];
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $queriesData[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Queries - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .sidebar { background: #2c3e50; height: 100vh; color: white; position: fixed; width: 280px; left: 0; top: 0; box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1); overflow-y: auto; z-index: 1000; }
        .sidebar .logo { padding: 25px 20px; text-align: center; border-bottom: 1px solid #34495e; background: #243342; }
        .sidebar .logo h4 { color: white; margin: 10px 0 5px; font-weight: 600; }
        .sidebar-menu { padding: 20px 0; }
        .sidebar-menu a { color: rgba(255, 255, 255, 0.8); text-decoration: none; padding: 15px 25px; display: flex; align-items: center; transition: all 0.3s; margin: 5px 0; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: #34495e; color: white; border-left: 4px solid #3498db; }
        .sidebar-menu i { margin-right: 15px; font-size: 20px; width: 25px; }
        .content { margin-left: 280px; padding: 20px 30px; }
        .header { background: white; padding: 15px 25px; border-radius: 15px; box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1); margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; }
        .table-card { background: white; border-radius: 15px; padding: 20px; box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1); }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <h4>Imperial Crown</h4>
            <span>Admin Panel</span>
        </div>
        <div class="sidebar-menu">
            <a href="index.php"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a>
            <a href="rooms.php"><i class="bi bi-door-open"></i><span>Rooms</span></a>
            <a href="bookings.php"><i class="bi bi-calendar-check"></i><span>Bookings</span></a>
            <a href="users.php"><i class="bi bi-people"></i><span>Users</span></a>
            <a href="facilities.php"><i class="bi bi-building"></i><span>Facilities</span></a>
            <a href="user_queries.php" class="active"><i class="bi bi-envelope"></i><span>User Queries</span></a>
            <a href="settings.php"><i class="bi bi-gear"></i><span>Settings</span></a>
            <hr style="border-color: #34495e; margin: 20px;">
            <a href="../logout.php"><i class="bi bi-box-arrow-right"></i><span>Logout</span></a>
        </div>
    </div>
    <div class="content">
        <div class="header">
            <h4><i class="bi bi-envelope"></i> User Queries</h4>
        </div>
        <div class="table-card">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($queriesData)): ?>
                        <tr><td colspan="7" class="text-center">No queries found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($queriesData as $index => $q): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($q['name']) ?></td>
                            <td><?= htmlspecialchars($q['email']) ?></td>
                            <td><?= htmlspecialchars($q['phone'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($q['subject']) ?></td>
                            <td><?= nl2br(htmlspecialchars($q['message'])) ?></td>
                            <td><?= date('d M, Y', strtotime($q['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
