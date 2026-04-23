<?php
require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/includes/room_helpers.php';
$rid = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$room_row = null;
if ($rid > 0) {
    $st = $mysqli->prepare('SELECT id, room_name, room_type, price, capacity, description, image, status FROM rooms WHERE id = ? LIMIT 1');
    $st->bind_param('i', $rid);
    $st->execute();
    $room_row = $st->get_result()->fetch_assoc();
    $st->close();
}
if (!$room_row) {
    header('Location: rooms.php');
    exit;
}
$rd_img = trim((string) $room_row['image']);
if ($rd_img === '' || strncmp($rd_img, 'http', 4) !== 0) {
    $rd_img = 'https://images.pexels.com/photos/271624/pexels-photo-271624.jpeg?auto=compress&cs=tinysrgb&w=600';
}
$rd_features = hotel_room_features((string) $room_row['room_type']);
$rd_facilities = hotel_room_facilities((string) $room_row['room_type']);
$php_logged = !empty($_SESSION['user_id']);
$php_admin = (($_SESSION['user_role'] ?? '') === 'admin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Details - Imperial Crown Hotel</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        .room-image {
            height: 400px;
            object-fit: cover;
            border-radius: 15px;
            width: 100%;
        }
        .thumbnail-img {
            width: 100px;
            height: 70px;
            object-fit: cover;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
            border: 2px solid transparent;
        }
        .thumbnail-img:hover {
            transform: scale(1.1);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        .thumbnail-img.active {
            border: 3px solid #2c3e50;
        }
        .facility-badge {
            background: #f8f9fa;
            padding: 8px 15px;
            border-radius: 20px;
            margin: 5px;
            display: inline-block;
        }
        .feature-badge {
            background: #2c3e50;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            margin: 5px;
            display: inline-block;
        }
        .btn-book {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-block;
            text-decoration: none;
            width: 100%;
            text-align: center;
        }
        .btn-book:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            color: white;
        }
        .btn-login-book {
            background: #6c757d;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-block;
            text-decoration: none;
            width: 100%;
            text-align: center;
            cursor: pointer;
        }
        .btn-login-book:hover {
            background: #5a6268;
            transform: translateY(-2px);
            color: white;
        }
        
        /* Modal Styles */
        .modal-content {
            border-radius: 20px;
            overflow: hidden;
        }
        .modal-header {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            border: none;
            padding: 20px;
        }
        .modal-header .btn-close {
            background-color: white;
        }
        .modal-body {
            padding: 30px;
        }
        .btn-modal {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            border: none;
            padding: 10px;
            border-radius: 10px;
            font-weight: bold;
            width: 100%;
        }
        .demo-box {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 10px;
            margin-top: 15px;
            text-align: center;
            font-size: 0.8rem;
        }
        .switch-link {
            text-align: center;
            margin-top: 15px;
        }
        .switch-link a {
            color: #3498db;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-light">

<?php require('navbar.php'); ?>

<!-- Spacer for fixed navbar -->
<div style="height: 120px;"></div>

<div class="container mb-5" id="roomDetails">
    <!-- Room details will load here -->
</div>

<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-box-arrow-in-right"></i> Login Required</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="bi bi-lock-fill" style="font-size: 50px; color: #3498db;"></i>
                    <h5 class="mt-2">Please Login to Book This Room</h5>
                </div>
                <div id="loginForm">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email Address</label>
                        <input type="email" id="loginEmail" class="form-control" placeholder="Enter your email">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Password</label>
                        <input type="password" id="loginPassword" class="form-control" placeholder="Enter your password">
                    </div>
                    <div id="loginError" class="alert alert-danger" style="display: none;"></div>
                    <button class="btn btn-modal" onclick="handleLoginFromRoom()">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Login
                    </button>
                    <div class="demo-box">
                        <small><strong>Demo Credentials:</strong></small><br>
                        <small>📧 rohan@gmail.com | 🔑 123456</small><br>
                        <small>📧 demo@hotel.com | 🔑 demo123</small>
                    </div>
                    <div class="switch-link">
                        <p>Don't have an account? <a href="register.php">Register here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-4 mt-5">
    <div class="container">
        <p>&copy; 2026 Imperial Crown Hotel. All rights reserved.</p>
    </div>
</footer>

<script>
<?php
$room_js = [
    'id' => (int) $room_row['id'],
    'name' => $room_row['room_name'],
    'price' => (float) $room_row['price'],
    'area' => 120,
    'adult' => (int) $room_row['capacity'],
    'children' => 2,
    'description' => (string) $room_row['description'],
    'features' => $rd_features,
    'facilities' => $rd_facilities,
    'status' => $room_row['status'],
];

$type = strtolower((string) $room_row['room_type']);
$gallery = [];
$gallery[] = ['image' => $rd_img, 'thumb' => 1];

if ($type === 'luxury' || $type === 'executive') {
    $gallery[] = ['image' => 'https://images.pexels.com/photos/105934/pexels-photo-105934.jpeg?auto=compress&cs=tinysrgb&w=600', 'thumb' => 0]; 
    $gallery[] = ['image' => 'https://images.pexels.com/photos/1743229/pexels-photo-1743229.jpeg?auto=compress&cs=tinysrgb&w=600', 'thumb' => 0]; 
    $gallery[] = ['image' => 'https://images.pexels.com/photos/2034335/pexels-photo-2034335.jpeg?auto=compress&cs=tinysrgb&w=600', 'thumb' => 0]; 
} else if ($type === 'delux') {
    $gallery[] = ['image' => 'https://images.pexels.com/photos/164595/pexels-photo-164595.jpeg?auto=compress&cs=tinysrgb&w=600', 'thumb' => 0]; 
    $gallery[] = ['image' => 'https://images.pexels.com/photos/279746/pexels-photo-279746.jpeg?auto=compress&cs=tinysrgb&w=600', 'thumb' => 0]; 
    $gallery[] = ['image' => 'https://images.pexels.com/photos/1034584/pexels-photo-1034584.jpeg?auto=compress&cs=tinysrgb&w=600', 'thumb' => 0]; 
} else {
    $gallery[] = ['image' => 'https://images.pexels.com/photos/271624/pexels-photo-271624.jpeg?auto=compress&cs=tinysrgb&w=600', 'thumb' => 0]; 
    $gallery[] = ['image' => 'https://images.pexels.com/photos/271618/pexels-photo-271618.jpeg?auto=compress&cs=tinysrgb&w=600', 'thumb' => 0]; 
    $gallery[] = ['image' => 'https://images.pexels.com/photos/1643389/pexels-photo-1643389.jpeg?auto=compress&cs=tinysrgb&w=600', 'thumb' => 0]; 
}

$room_js['images'] = $gallery;
echo 'const room = ' . json_encode($room_js, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . ';';
echo 'const phpLoggedIn = ' . ($php_logged ? 'true' : 'false') . ';';
echo 'const phpIsAdmin = ' . ($php_admin ? 'true' : 'false') . ';';
?>

function isUserLoggedIn() {
    return phpLoggedIn;
}

function handleBookClick() {
    if (room.status !== 'available') {
        alert('This room is not available for booking.');
        return;
    }
    if (phpIsAdmin) {
        alert('Please use a guest account to make bookings.');
        return;
    }
    if(isUserLoggedIn()) {
        window.location.href = `booking.php?room=${room.id}&roomName=${encodeURIComponent(room.name)}&roomPrice=${room.price}`;
    } else {
        var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
        loginModal.show();
    }
}

function handleLoginFromRoom() {
    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;
    const errorDiv = document.getElementById('loginError');
    errorDiv.style.display = 'none';
    fetch('api/login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            window.location.href = `booking.php?room=${room.id}&roomName=${encodeURIComponent(room.name)}&roomPrice=${room.price}`;
        } else {
            errorDiv.style.display = 'block';
            errorDiv.innerHTML = data.message || 'Login failed.';
        }
    })
    .catch(() => {
        errorDiv.style.display = 'block';
        errorDiv.innerHTML = 'Network error.';
    });
}

// Display room details
$(document).ready(function() {
    let mainImage = room.images.find(img => img.thumb === 1) || room.images[0];
    
    let featuresHtml = '';
    room.features.forEach(f => {
        featuresHtml += `<span class="feature-badge"><i class="bi bi-check-circle me-1"></i>${f}</span>`;
    });
    
    let facilitiesHtml = '';
    room.facilities.forEach(f => {
        facilitiesHtml += `<span class="facility-badge"><i class="bi bi-check-circle-fill text-success me-1"></i>${f}</span>`;
    });
    
    let thumbnailsHtml = '';
    room.images.forEach((img, index) => {
        thumbnailsHtml += `
            <img src="${img.image}" 
                 class="thumbnail-img ${img.thumb === 1 ? 'active' : ''}" 
                 onclick="changeImage('${img.image}', this)">
        `;
    });
    
    // Check if user is logged in to show appropriate button
    const isLoggedIn = isUserLoggedIn();
    
    let buttonHtml = '';
    if (room.status !== 'available') {
        buttonHtml = `<button class="btn-login-book" disabled>Not available for booking</button>`;
    } else if(isLoggedIn) {
        buttonHtml = `<a href="booking.php?room=${room.id}&roomName=${encodeURIComponent(room.name)}&roomPrice=${room.price}" 
                       class="btn-book">
                        <i class="bi bi-calendar-check me-2"></i>Book This Room
                      </a>`;
    } else {
        buttonHtml = `<button class="btn-login-book" onclick="handleBookClick()">
                        <i class="bi bi-lock me-2"></i>Login to Book This Room
                      </button>`;
    }
    
    let html = `
        <div class="row">
            <div class="col-lg-7">
                <img src="${mainImage.image}" class="room-image mb-3" id="mainImage">
                <div class="d-flex gap-2 flex-wrap">
                    ${thumbnailsHtml}
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card shadow p-4">
                    <h2>${room.name}</h2>
                    <h4 class="text-primary mb-3">₹${room.price} <small class="text-muted fs-6">/ night</small></h4>
                    
                    <p class="text-muted">${room.description}</p>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <h5>Features</h5>
                        ${featuresHtml}
                    </div>
                    
                    <div class="mb-3">
                        <h5>Facilities</h5>
                        ${facilitiesHtml}
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <strong>Area:</strong> ${room.area} sq.ft
                        </div>
                        <div class="col-6">
                            <strong>Guests:</strong> ${room.adult} Adults, ${room.children} Children
                        </div>
                    </div>
                    
                    <hr>
                    
                    ${buttonHtml}
                    
                    <a href="rooms.php" class="btn btn-outline-dark mt-3 w-100">
                        <i class="bi bi-arrow-left me-2"></i>Back to Rooms
                    </a>
                </div>
            </div>
        </div>
    `;
    
    $('#roomDetails').html(html);
});

function changeImage(imageSrc, element) {
    $('#mainImage').attr('src', imageSrc);
    $('.thumbnail-img').removeClass('active');
    $(element).addClass('active');
}
</script>
</body>
</html>