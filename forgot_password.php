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
    <title>Forgot Password - The Imperial Crown Hotel</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <style>
        body {
            background: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)), url('https://images.pexels.com/photos/258154/pexels-photo-258154.jpeg?auto=compress&cs=tinysrgb&w=1920');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.25);
            background: rgba(255,255,255,0.96);
        }
        .card-header {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
            text-align: center;
            border-radius: 16px 16px 0 0 !important;
            padding: 22px;
        }
        .error { color: #dc3545; font-size: .875rem; margin-top: 5px; display: block; }
        .error-border { border: 2px solid #dc3545 !important; }
    </style>
</head>
<body>
<div class="container" style="max-width:520px;">
    <div class="card">
        <div class="card-header">
            <h3 class="m-0"><i class="bi bi-shield-lock me-2"></i>Forgot Password</h3>
            <p class="mb-0">We will send an OTP to your email</p>
        </div>
        <div class="card-body p-4">
            <form id="forgotForm">
                <div class="mb-3">
                    <label class="form-label fw-bold">Email Address</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email">
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-send me-2"></i>Send OTP
                </button>
                <a href="login.php" class="btn btn-outline-secondary w-100 mt-2">
                    <i class="bi bi-arrow-left me-2"></i>Back to Login
                </a>
                <div class="alert alert-success mt-3" id="okAlert" style="display:none;"></div>
                <div class="alert alert-danger mt-3" id="errAlert" style="display:none;"></div>
            </form>
        </div>
    </div>
</div>

<script>
$(function(){
    function normalizeEmail(value) {
        return String(value || "")
            .replace(/[\u0000-\u0020\u00A0]+/g, "")
            .toLowerCase();
    }

    $("#email").on("blur", function() {
        $(this).val(normalizeEmail($(this).val()));
    });

    $("#forgotForm").validate({
        rules: {
            email: {
                required: true,
                email: true,
                maxlength: 100,
                normalizer: function(value) {
                    return normalizeEmail(value);
                }
            }
        },
        messages: { email: { required: "Please enter your email", email: "Please enter a valid email" } },
        errorElement: "span",
        errorClass: "error",
        highlight: function(el){ $(el).addClass("error-border"); },
        unhighlight: function(el){ $(el).removeClass("error-border"); },
        submitHandler: function() {
            const email = normalizeEmail($("#email").val());
            $("#email").val(email);
            $("#okAlert").hide(); $("#errAlert").hide();

            fetch("api/forgot_password.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ email })
            })
            .then(r => r.json())
            .then(data => {
                if (data && data.success) {
                    $("#okAlert").text(data.message || "OTP sent.").show();
                    setTimeout(() => {
                        window.location.href = "reset_password.php?email=" + encodeURIComponent(email);
                    }, 900);
                } else {
                    $("#errAlert").text((data && data.message) ? data.message : "Failed.").show();
                }
            })
            .catch(() => $("#errAlert").text("Network error.").show());
        }
    });
});
</script>
</body>
</html>

