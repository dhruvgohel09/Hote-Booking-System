<?php
require_once __DIR__ . '/includes/init.php';
if (!empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
$email = trim((string)($_GET['email'] ?? ''));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - The Imperial Crown Hotel</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <style>
        body {
            background: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)), url('https://images.pexels.com/photos/164595/pexels-photo-164595.jpeg?auto=compress&cs=tinysrgb&w=1920');
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
<div class="container" style="max-width:560px;">
    <div class="card">
        <div class="card-header">
            <h3 class="m-0"><i class="bi bi-key me-2"></i>Reset Password</h3>
            <p class="mb-0">Enter OTP and new password (OTP valid for 30 minutes)</p>
        </div>
        <div class="card-body p-4">
            <form id="resetForm">
                <div class="mb-3">
                    <label class="form-label fw-bold">Email Address</label>
                    <input type="email" class="form-control" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Enter your email">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">OTP</label>
                    <input type="text" class="form-control" name="otp" id="otp" maxlength="6" placeholder="6-digit OTP">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">New Password</label>
                    <input type="password" class="form-control" name="new_password" id="new_password" placeholder="New password">
                    <div class="form-text">8-128 characters, include letters and numbers.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Confirm New Password</label>
                    <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Confirm new password">
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-check2-circle me-2"></i>Update Password
                </button>
                <a href="forgot_password.php" class="btn btn-outline-secondary w-100 mt-2">
                    <i class="bi bi-arrow-left me-2"></i>Back
                </a>
                <button type="button" class="btn btn-outline-primary w-100 mt-2" id="resendOtpBtn">
                    <i class="bi bi-arrow-repeat me-2"></i>Resend OTP
                </button>
                <div class="form-text text-center mt-2" id="resendInfo"></div>
                <div class="alert alert-success mt-3" id="okAlert" style="display:none;"></div>
                <div class="alert alert-danger mt-3" id="errAlert" style="display:none;"></div>
            </form>
        </div>
    </div>
</div>

<script>
$(function(){
    let resendTimer = null;

    function normalizeEmail(value) {
        return String(value || "")
            .replace(/[\u0000-\u0020\u00A0]+/g, "")
            .toLowerCase();
    }

    $("#email").on("blur", function() {
        $(this).val(normalizeEmail($(this).val()));
    });

    function startResendCountdown(seconds) {
        let left = Math.max(0, parseInt(seconds || 0, 10));
        const btn = $("#resendOtpBtn");
        const info = $("#resendInfo");
        if (resendTimer) {
            clearInterval(resendTimer);
            resendTimer = null;
        }
        if (left <= 0) {
            btn.prop("disabled", false);
            info.text("");
            return;
        }
        btn.prop("disabled", true);
        info.text("You can resend OTP in " + left + "s");
        resendTimer = setInterval(function() {
            left -= 1;
            if (left <= 0) {
                clearInterval(resendTimer);
                resendTimer = null;
                btn.prop("disabled", false);
                info.text("");
            } else {
                info.text("You can resend OTP in " + left + "s");
            }
        }, 1000);
    }

    $("#resetForm").validate({
        rules: {
            email: { required: true, email: true, maxlength: 100 },
            otp: { required: true, digits: true, minlength: 6, maxlength: 6 },
            new_password: { required: true, minlength: 8, maxlength: 128 },
            confirm_password: { required: true, equalTo: "#new_password" }
        },
        messages: {
            email: { required: "Please enter your email", email: "Please enter a valid email" },
            otp: { required: "Please enter OTP", digits: "OTP must be digits", minlength: "OTP must be 6 digits", maxlength: "OTP must be 6 digits" },
            new_password: { required: "Please enter new password", minlength: "Password must be at least 8 characters" },
            confirm_password: { required: "Please confirm your password", equalTo: "Passwords do not match" }
        },
        errorElement: "span",
        errorClass: "error",
        highlight: function(el){ $(el).addClass("error-border"); },
        unhighlight: function(el){ $(el).removeClass("error-border"); },
        submitHandler: function() {
            const email = normalizeEmail($("#email").val());
            const otp = $("#otp").val();
            const new_password = $("#new_password").val();
            $("#email").val(email);
            $("#okAlert").hide(); $("#errAlert").hide();

            fetch("api/reset_password.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ email, otp, new_password })
            })
            .then(r => r.json())
            .then(data => {
                if (data && data.success) {
                    $("#okAlert").text(data.message || "Password updated.").show();
                    setTimeout(() => window.location.href = data.redirect || "login.php", 900);
                } else {
                    $("#errAlert").text((data && data.message) ? data.message : "Failed.").show();
                }
            })
            .catch(() => $("#errAlert").text("Network error.").show());
        }
    });

    $("#resendOtpBtn").on("click", function() {
        const email = normalizeEmail($("#email").val());
        $("#email").val(email);
        $("#okAlert").hide();
        $("#errAlert").hide();

        if (!email) {
            $("#errAlert").text("Please enter your email first.").show();
            return;
        }
        if (!$("#email")[0].checkValidity()) {
            $("#errAlert").text("Please enter a valid email.").show();
            return;
        }

        fetch("api/forgot_password.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ email })
        })
        .then(r => r.json())
        .then(data => {
            if (data && data.success) {
                let msg = data.message || "OTP sent.";
                if (data.debug_otp) {
                    msg += " OTP: " + data.debug_otp;
                }
                if (data.debug_note) {
                    msg += " (" + data.debug_note + ")";
                }
                $("#okAlert").text(msg).show();
                const waitSec = parseInt((data && data.resend_wait_seconds) ? data.resend_wait_seconds : 30, 10);
                startResendCountdown(waitSec);
            } else {
                $("#errAlert").text((data && data.message) ? data.message : "Failed to resend OTP.").show();
            }
        })
        .catch(() => $("#errAlert").text("Network error while resending OTP.").show());
    });
});
</script>
</body>
</html>

