<?php
require_once __DIR__ . '/includes/init.php';
if (!empty($_SESSION['user_id'])) {
    if (($_SESSION['user_role'] ?? '') === 'admin') {
        header('Location: admin/index.php');
    } else {
        header('Location: index.php');
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - The Imperial Crown Hotel</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- jQuery & jQuery Validation -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    
    <style>
        body {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://dq5r178u4t83b.cloudfront.net/wp-content/uploads/sites/125/2020/06/15182916/Sofitel-Dubai-Wafi-Luxury-Room-Bedroom-Skyline-View-Image1_WEB.jpg');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-container {
            max-width: 450px;
            margin: 0 auto;
            width: 100%;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            background: rgba(255,255,255,0.95);
        }
        .card-header {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
            text-align: center;
            border-radius: 15px 15px 0 0 !important;
            padding: 25px;
        }
        .card-header h3 {
            margin: 0;
            font-weight: 600;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #ddd;
        }
        .form-control:focus {
            border-color: #2c3e50;
            box-shadow: 0 0 0 0.2rem rgba(44,62,80,0.25);
        }
        .btn-login {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .btn-back {
            background: #6c757d;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s;
            margin-top: 10px;
        }
        .btn-back:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }
        .error {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 5px;
            display: block;
        }
        .error-border {
            border: 2px solid #dc3545 !important;
        }
        .alert {
            border-radius: 8px;
            padding: 12px;
            margin-top: 15px;
            display: none;
        }
        .alert.show {
            display: block;
        }
        .demo-box {
            background: #e9ecef;
            border-radius: 8px;
            padding: 12px;
            margin-top: 20px;
            border-left: 4px solid #2c3e50;
        }
    </style>
</head>
<body>

<div class="container login-container">
    <div class="card">
        <div class="card-header">
            <h3><i class="bi bi-box-arrow-in-right me-2"></i>Login</h3>
            <p class="mb-0">Welcome back to Imperial Crown Hotel</p>
        </div>
        <div class="card-body p-4">
            <?php if (!empty($_GET['expired'])): ?>
            <div class="alert alert-warning py-2 mb-3" role="alert">
                <i class="bi bi-clock-history me-1"></i> Your session expired due to inactivity. Please sign in again.
            </div>
            <?php endif; ?>
            

            
            <!-- Login Form -->
            <form id="loginForm">
                <div class="mb-3">
                    <label class="form-label fw-bold">Email Address</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email">
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Password</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password">
                </div>
                
                <button type="submit" class="btn-login">
                    <i class="bi bi-box-arrow-in-right me-2"></i>LOGIN
                </button>
                
                <!-- Back to Website Button -->
                <button type="button" class="btn-back mb-3" onclick="window.location.href='index.php'">
                    <i class="bi bi-arrow-left me-2"></i>Back to Website
                </button>
                
                <p class="text-center mb-2 mt-2">
                    Don't have an Account? <a href="register.php" class="text-decoration-none">Register here</a>
                </p>
                <p class="text-center mb-0">
                    <a href="forgot_password.php" class="text-decoration-none text-secondary">Forgot password?</a>
                </p>

                
                <div class="alert alert-danger" id="loginAlert"></div>
            </form>
            
           
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $("#loginForm").validate({
        rules: {
            email: { required: true, email: true, maxlength: 100 },
            password: { required: true, minlength: 1, maxlength: 128 }
        },
        messages: {
            email: { required: "Please enter your email", email: "Please enter a valid email", maxlength: "Email is too long" },
            password: { required: "Please enter your password", maxlength: "Password is too long" }
        },
        errorElement: "span",
        errorClass: "error",
        highlight: function(element) { $(element).addClass("error-border"); },
        unhighlight: function(element) { $(element).removeClass("error-border"); },
        submitHandler: function() {
            const email = $("#email").val();
            const password = $("#password").val();
            $("#loginAlert").removeClass("alert-success").addClass("alert-danger").hide();
            fetch("api/login.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ email, password })
            })
            .then(r => {
                if (r.status === 401) {
                    window.location.href = "login.php?expired=1";
                    return null;
                }
                return r.json();
            })
            .then(data => {
                if (data === null) return;
                if (data.success) {
                    if (data.user) {
                        localStorage.setItem('isLoggedIn', 'true');
                        localStorage.setItem('userType', data.user.role);
                        localStorage.setItem('userEmail', data.user.email);
                        localStorage.setItem('userName', data.user.name);
                    }
                    $("#loginAlert").removeClass("alert-danger").addClass("alert-success").html("Login successful! Redirecting...").show();
                    window.location.href = data.redirect || "index.php";
                } else {
                    $("#loginAlert").removeClass("alert-success").addClass("alert-danger").html(data.message || "Invalid credentials").show();
                }
            })
            .catch(() => {
                $("#loginAlert").removeClass("alert-success").addClass("alert-danger").html("Network error.").show();
            });
        }
    });
});
</script>

</body>
</html>