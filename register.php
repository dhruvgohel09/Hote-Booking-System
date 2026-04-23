<?php
require_once __DIR__ . '/includes/init.php';
if (!empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Imperial Crown Hotel - Register</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- jQuery Validation -->
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .register-container {
            max-width: 500px;
            margin: 50px auto;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .card-header {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
            text-align: center;
            border-radius: 15px 15px 0 0 !important;
            padding: 20px;
        }
        .card-header h3 {
            margin: 0;
            font-weight: 600;
        }
        .form-control, .form-select {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid #ddd;
        }
        .form-control:focus, .form-select:focus {
            border-color: #2c3e50;
            box-shadow: 0 0 0 0.2rem rgba(44,62,80,0.25);
        }
        .btn-register {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
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
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
        .login-link a {
            color: #3498db;
            text-decoration: none;
            font-weight: 600;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container register-container">
    <div class="card">
        <div class="card-header">
            <h3><i class="bi bi-person-plus me-2"></i>Create Account</h3>
            <p class="mb-0">Join The Imperial Crown Hotel</p>
        </div>
        <div class="card-body p-4">
            
            <!-- Success Message -->
            <div class="success-message" id="successMessage">
                <i class="bi bi-check-circle me-2"></i>
                Please verify your email.
            </div>
            
            <form id="registerForm">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">First Name *</label>
                        <input type="text" class="form-control" name="first_name" id="first_name" placeholder="Enter first name">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Last Name *</label>
                        <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Enter last name">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Email Address *</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email">
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Password *</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Create password">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Confirm Password *</label>
                        <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Confirm password">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Phone Number *</label>
                        <input type="text" class="form-control" name="phone" id="phone" placeholder="10 digit mobile number">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Date of Birth</label>
                        <input type="date" class="form-control" name="dob" id="dob">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Address</label>
                    <textarea class="form-control" name="address" id="address" rows="2" placeholder="Enter your address"></textarea>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">City</label>
                        <input type="text" class="form-control" name="city" id="city" placeholder="City">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">State</label>
                        <input type="text" class="form-control" name="state" id="state" placeholder="State">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Pincode</label>
                        <input type="text" class="form-control" name="pincode" id="pincode" placeholder="Pincode">
                    </div>
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" name="terms" id="terms">
                    <label class="form-check-label" for="terms">I agree to the <a href="#">Terms & Conditions</a></label>
                </div>
                
                <button type="submit" class="btn-register">
                    <i class="bi bi-person-check me-2"></i>Register Now
                </button>
            </form>
            
            <div class="login-link">
                Already have an account? <a href="login.php">Login here</a>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $.validator.addMethod("strongPassword", function(value, element) {
        if (this.optional(element)) return true;
        return value.length >= 8 && value.length <= 128 &&
            /[A-Za-z]/.test(value) && /[0-9]/.test(value);
    }, "Use 8–128 characters with at least one letter and one number.");

    $.validator.addMethod("pincodeIn", function(value, element) {
        if (this.optional(element) || $.trim(value) === "") return true;
        return /^[0-9]{6}$/.test(value);
    }, "Enter a 6-digit pincode or leave empty.");

    $.validator.addMethod("lastNameOk", function(value, element) {
        var t = $.trim(value);
        return t.length >= 2 && t.length <= 60 && /^[A-Za-z]+(?: [A-Za-z]+)*$/.test(t);
    }, "Use letters only (e.g. Kumar or Kumar Singh).");

    $("#registerForm").validate({
        rules: {
            first_name: {
                required: true,
                minlength: 2,
                maxlength: 50,
                pattern: /^[A-Za-z]+$/
            },
            last_name: {
                required: true,
                lastNameOk: true
            },
            email: {
                required: true,
                email: true,
                maxlength: 100
            },
            password: {
                required: true,
                strongPassword: true
            },
            confirm_password: {
                required: true,
                equalTo: "#password"
            },
            phone: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10
            },
            pincode: {
                pincodeIn: true
            },
            terms: {
                required: true
            }
        },
        messages: {
            first_name: {
                required: "First name is required",
                minlength: "Minimum 2 characters",
                pattern: "Letters only (A–Z)"
            },
            last_name: {
                required: "Last name is required"
            },
            email: {
                required: "Email is required",
                email: "Enter a valid email"
            },
            password: {
                required: "Password is required"
            },
            confirm_password: {
                required: "Confirm password",
                equalTo: "Passwords do not match"
            },
            phone: {
                required: "Phone number required",
                digits: "Only digits allowed",
                minlength: "Must be 10 digits",
                maxlength: "Must be 10 digits"
            },
            terms: "You must agree to terms"
        },
        errorElement: "span",
        errorClass: "error",
        highlight: function(element) {
            $(element).addClass("error-border");
        },
        unhighlight: function(element) {
            $(element).removeClass("error-border");
        },
        submitHandler: function(form) {
            const payload = {
                first_name: $('#first_name').val(),
                last_name: $('#last_name').val(),
                email: $('#email').val(),
                password: $('#password').val(),
                phone: $('#phone').val(),
                address: $('#address').val(),
                city: $('#city').val(),
                state: $('#state').val(),
                pincode: $('#pincode').val()
            };
            fetch('api/register.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const requiresVerification = !!data.requires_verification;
                    const msg = data.message || 'Account created.';

                    if (requiresVerification) {
                        $('#successMessage').html(
                            '<i class="bi bi-check-circle me-2"></i>' + msg +
                            '<div style="margin-top:10px"><a href="login.php" class="btn btn-primary btn-sm">Go to Login</a></div>'
                        );
                        $('#successMessage').fadeIn();
                        $('#registerForm')[0].reset();
                        return;
                    }

                    $('#successMessage').html('<i class="bi bi-check-circle me-2"></i>' + msg);
                    $('#successMessage').fadeIn();
                    $('#registerForm')[0].reset();
                    setTimeout(function() { window.location.href = 'login.php'; }, 2000);
                } else {
                    alert(data.message || 'Registration failed.');
                }
            })
            .catch(() => alert('Network error.'));
        }
    });
});
</script>

</body>
</html>