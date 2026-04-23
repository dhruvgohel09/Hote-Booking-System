<?php
require_once __DIR__ . '/includes/init.php';

if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') === 'admin') {
    header('Location: index.php');
    exit;
}

$uid = (int) $_SESSION['user_id'];
$message = '';
$error = '';

$stmt = $mysqli->prepare('SELECT first_name, last_name, email, phone, address FROM users WHERE id = ? LIMIT 1');
$stmt->bind_param('i', $uid);
$stmt->execute();
$urow = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$urow) {
    header('Location: index.php');
    exit;
}

$username = trim($urow['first_name'] . ' ' . $urow['last_name']);
$email = $urow['email'];
$phone = $urow['phone'] ?? '';
$address = $urow['address'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = trim($_POST['username'] ?? '');
    $new_email = trim($_POST['email'] ?? '');
    $new_phone = preg_replace('/\D/', '', $_POST['phone'] ?? '');
    $new_address = trim($_POST['address'] ?? '');

    if ($new_username === '' || $new_email === '') {
        $error = 'Name and Email are required!';
    } elseif (!preg_match('/^[a-zA-Z\s]+$/', $new_username)) {
        $error = 'Name must contain letters only.';
    } elseif (!empty($new_phone) && strlen($new_phone) !== 10) {
        $error = 'Enter a valid 10-digit phone number.';
    } else {
        $parts = preg_split('/\s+/', $new_username, 2);
        $fn = $parts[0];
        $ln = $parts[1] ?? '';

        $upd = $mysqli->prepare('UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, address = ? WHERE id = ?');
        $upd->bind_param('sssssi', $fn, $ln, $new_email, $new_phone, $new_address, $uid);
        if ($upd->execute()) {
            $_SESSION['user_name'] = trim($fn . ' ' . $ln);
            $_SESSION['user_email'] = $new_email;
            $username = $_SESSION['user_name'];
            $email = $new_email;
            $phone = $new_phone;
            $address = $new_address;
            $message = 'Profile updated successfully!';
        } else {
            $error = 'Could not update (email may already be in use).';
        }
        $upd->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - The Imperial Crown Hotel</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding-top: 100px;
            padding-bottom: 50px;
        }
        
        .profile-card {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            animation: fadeInUp 0.5s ease;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .profile-header {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .profile-header i {
            font-size: 70px;
            margin-bottom: 15px;
        }
        
        .profile-header h2 {
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        .profile-header p {
            opacity: 0.9;
            margin-bottom: 0;
        }
        
        .profile-body {
            padding: 35px;
        }
        
        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #ddd;
        }
        
        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52,152,219,0.25);
        }
        
        .btn-update {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: bold;
            font-size: 1rem;
            width: 100%;
            transition: all 0.3s;
        }
        
        .btn-update:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52,152,219,0.4);
        }
        
        .alert {
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .info-box {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 10px;
            margin-top: 20px;
            text-align: center;
            border: 1px solid #eee;
        }
    </style>
</head>
<body>

<!-- Include Navbar -->
<?php include 'navbar.php'; ?>

<div class="container">
    <div class="profile-card">
        <div class="profile-header">
            <i class="bi bi-person-circle"></i>
            <h2>Edit Profile</h2>
            <p>Update your personal information</p>
        </div>
        <div class="profile-body">
            
            <?php if($message): ?>
                <div class="alert alert-success">
                    <i class="bi bi-check-circle-fill me-2"></i> <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <?php if($error): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        <i class="bi bi-person"></i> Name
                    </label>
                    <input type="text" name="username" class="form-control" 
                           value="<?php echo htmlspecialchars($username); ?>">
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        <i class="bi bi-envelope"></i> Email
                    </label>
                    <input type="email" name="email" class="form-control" 
                           value="<?php echo htmlspecialchars($email); ?>">
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        <i class="bi bi-telephone"></i> Phone
                    </label>
                    <input type="tel" name="phone" class="form-control" 
                           value="<?php echo htmlspecialchars($phone); ?>" placeholder="Enter your phone number">
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        <i class="bi bi-geo-alt"></i> Address
                    </label>
                    <textarea name="address" class="form-control" rows="3" placeholder="Enter your address"><?php echo htmlspecialchars($address); ?></textarea>
                </div>
                
                <button type="submit" class="btn btn-update btn-primary">
                    <i class="bi bi-save me-2"></i> Update Profile
                </button>
            </form>
            
            <div class="info-box">
                <small class="text-muted">
                    <i class="bi bi-info-circle"></i> Profile is saved to your account in the database.
                </small>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- jQuery Validation -->
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script>
$(document).ready(function() {
    $.validator.addMethod("lettersOnly", function(value, element) {
        return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
    }, "Name must contain letters only.");

    $("form").validate({
        rules: {
            username: {
                required: true,
                lettersOnly: true
            },
            email: {
                required: true,
                email: true
            },
            phone: {
                digits: true,
                minlength: 10,
                maxlength: 10
            }
        },
        messages: {
            username: {
                required: "Name is required!",
                lettersOnly: "Name must contain letters only."
            },
            email: {
                required: "Email is required!",
                email: "Please enter a valid email address."
            },
            phone: {
                digits: "Please enter only digits.",
                minlength: "Enter a valid 10-digit phone number.",
                maxlength: "Enter a valid 10-digit phone number."
            }
        },
        errorElement: "span",
        errorClass: "text-danger small mt-1 d-block",
        highlight: function(element) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function(element) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        }
    });
});
</script>

</body>
</html>