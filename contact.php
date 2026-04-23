<?php require_once __DIR__ . '/includes/init.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Imperial Crown Hotel - Contact Us</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <style>
        .custom-bg {
            background-color: #2c3e50;
        }

        .custom-bg:hover {
            background-color: #1a252f;
        }

        .h-line {
            width: 150px;
            margin: 0 auto;
            height: 1.7px;
            background-color: #2c3e50;
        }

        .contact-info-card {
            transition: transform 0.3s;
            border: none;
            border-radius: 15px;
            overflow: hidden;
            height: 100%;
        }

        .contact-info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15) !important;
        }

        .contact-icon {
            width: 70px;
            height: 70px;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            border-radius: 50%;
            color: white;
            font-size: 2rem;
        }

        .map-container {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .form-control,
        .form-select {
            border: 1px solid #ced4da;
            border-radius: 8px;
            padding: 10px 15px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #2c3e50;
            box-shadow: 0 0 0 0.2rem rgba(44, 62, 80, 0.25);
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
            text-align: center;
        }

        .submit-btn {
            background-color: #2c3e50;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .submit-btn:hover {
            background-color: #1a252f;
            transform: translateY(-2px);
        }

        /* Social Media Icons Grid */
        .social-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 10px;
            margin-top: 15px;
        }

        .social-item {
            text-align: center;
        }

        .social-item a {
            display: inline-block;
            width: 40px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: white;
            transition: all 0.3s;
        }

        .social-item a:hover {
            background: #ffc107;
            color: #2c3e50;
            transform: translateY(-3px);
        }

        .social-item span {
            display: block;
            font-size: 0.7rem;
            margin-top: 5px;
            color: #aaa;
        }

        /* Footer always at bottom */
        html,
        body {
            height: 100%;
            margin: 0;
        }

        body {
            display: flex;
            flex-direction: column;
        }

        .content-wrapper {
            flex: 1 0 auto;
        }

        footer {
            flex-shrink: 0;
        }

        .h-font {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            letter-spacing: 1px;
        }

        .navbar-brand {
            font-size: 1.5rem;
        }

        .nav-link {
            font-size: 1.1rem;
            margin: 0 5px;
        }

        .nav-link.active {
            font-weight: 600;
            border-bottom: 2px solid white;
        }
    </style>
</head>

<body class="bg-light">

    <!-- Header -->
    <?php require('navbar.php'); ?>

    <!-- Main Content Wrapper -->
    <div class="content-wrapper">
        <!-- Spacer for fixed navbar -->
        <div style="height: 120px;"></div>

        <!-- Page Title -->
        <div class="mb-5 px-4">
            <h2 class="fw-bold h-font text-center">CONTACT US</h2>
            <div class="h-line bg-dark"></div>
            <p class="text-center mt-3 text-muted">We're here to help and answer any questions you might have</p>
        </div>

        <!-- Contact Info Cards -->
        <div class="container my-5">
            <div class="row g-4">
                <!-- Address Card -->
                <div class="col-lg-4 col-md-6">
                    <div class="card contact-info-card shadow">
                        <div class="card-body text-center p-4">
                            <div class="contact-icon">
                                <i class="bi bi-geo-alt"></i>
                            </div>
                            <h5 class="card-title mb-3">Our Address</h5>
                            <p class="card-text text-muted mb-0">
                                <?= htmlspecialchars($GLOBAL_SETTINGS['contact_address'] ?? 'Upleta Dhoraji Road, Jetpur, Gujarat - 360360') ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Phone Card -->
                <div class="col-lg-4 col-md-6">
                    <div class="card contact-info-card shadow">
                        <div class="card-body text-center p-4">
                            <div class="contact-icon">
                                <i class="bi bi-telephone"></i>
                            </div>
                            <h5 class="card-title mb-3">Phone Numbers</h5>
                            <p class="card-text text-muted mb-1">
                                <?= htmlspecialchars($GLOBAL_SETTINGS['contact_phone1'] ?? '+91 91234 56789') ?>
                            </p>
                            <p class="card-text text-muted">
                                <?= htmlspecialchars($GLOBAL_SETTINGS['contact_phone2'] ?? '+91 99123 45678') ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Email Card -->
                <div class="col-lg-4 col-md-6">
                    <div class="card contact-info-card shadow">
                        <div class="card-body text-center p-4">
                            <div class="contact-icon">
                                <i class="bi bi-envelope"></i>
                            </div>
                            <h5 class="card-title mb-3">Email Us</h5>
                            <p class="card-text text-muted mb-1">
                                <?= htmlspecialchars($GLOBAL_SETTINGS['contact_email1'] ?? 'info@imperialcrown.com') ?>
                            </p>
                            <p class="card-text text-muted">
                                <?= htmlspecialchars($GLOBAL_SETTINGS['contact_email2'] ?? 'support@imperialcrown.com') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Map and Contact Form -->
        <div class="container my-5">
            <div class="row">
                <!-- Google Map -->
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="map-container">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3737279.1!2d68.0!3d21.0!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3959b46477b75f8b%3A0x8cbae52fb37adb10!2sJetpur%2C%20Gujarat!5e0!3m2!1sen!2sin!4v1234567890!5m2!1sen!2sin"
                            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy">
                        </iframe>
                    </div>
                    <div class="mt-2 text-muted small">
                        <i class="bi bi-info-circle"></i> kalawad road , Rajkot, Gujarat • Map data ©2026
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="col-lg-6">
                    <div class="card shadow border-0 p-4">
                        <h4 class="mb-4"><i class="bi bi-envelope-paper me-2 text-primary"></i>Send us a Message</h4>

                        <!-- Success Message -->
                        <div class="success-message" id="successMessage">
                            <i class="bi bi-check-circle me-2"></i>
                            Send Message Successfully
                        </div>

                        <form id="contactForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label fw-bold">Full Name *</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Enter your name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label fw-bold">Email Address *</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Enter your email">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="subject" class="form-label fw-bold">Subject *</label>
                                <input type="text" class="form-control" id="subject" name="subject"
                                    placeholder="Enter subject">
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label fw-bold">Message *</label>
                                <textarea class="form-control" id="message" name="message" rows="5"
                                    placeholder="Type your message here..."></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label fw-bold">Phone Number (Optional)</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    placeholder="Enter your phone number">
                            </div>

                            <button type="submit" class="btn btn-dark w-100 py-3">
                                <i class="bi bi-send me-2"></i>Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require('footer.php'); ?>
    <script>
        $(document).ready(function () {
            // Custom rule for letters and spaces
            $.validator.addMethod("lettersOnly", function (value, element) {
                return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
            }, "Name must contain letters only.");

            // jQuery Validation
            $("#contactForm").validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 3,
                        maxlength: 50,
                        lettersOnly: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    subject: {
                        required: true,
                        minlength: 5,
                        maxlength: 100
                    },
                    message: {
                        required: true,
                        minlength: 20,
                        maxlength: 500
                    },
                    phone: {
                        digits: true,
                        minlength: 10,
                        maxlength: 10
                    }
                },
                messages: {
                    name: {
                        required: "Please enter your name",
                        minlength: "Name must be at least 3 characters"
                    },
                    email: {
                        required: "Please enter your email",
                        email: "Please enter a valid email address"
                    },
                    subject: {
                        required: "Please enter a subject",
                        minlength: "Subject must be at least 5 characters"
                    },
                    message: {
                        required: "Please enter your message",
                        minlength: "Message must be at least 20 characters"
                    },
                    phone: {
                        digits: "Please enter only digits",
                        minlength: "Phone number must be 10 digits",
                        maxlength: "Phone number must be 10 digits"
                    }
                },
                errorElement: "span",
                errorClass: "error",
                highlight: function (element) {
                    $(element).addClass("error-border");
                },
                unhighlight: function (element) {
                    $(element).removeClass("error-border");
                },
                submitHandler: function (form) {
                    const payload = {
                        name: $('#name').val(),
                        email: $('#email').val(),
                        subject: $('#subject').val(),
                        message: $('#message').val(),
                        phone: $('#phone').val()
                    };
                    fetch('api/contact_submit.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload)
                    })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                alert('Send Message Successfully');
                                $('#successMessage').fadeIn();
                                $('#contactForm')[0].reset();
                                setTimeout(function () { $('#successMessage').fadeOut(); }, 5000);
                            } else {
                                alert(data.message || 'Could not send.');
                            }
                        })
                        .catch(() => alert('Network error.'));
                }
            });
        });
    </script>
</body>

</html>