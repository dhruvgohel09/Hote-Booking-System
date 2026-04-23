<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - The Imperial Crown Hotel</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-box {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo img {
            width: 80px;
            margin-bottom: 10px;
        }
        .logo h3 {
            color: #2c3e50;
            font-weight: 700;
        }
        .user-type {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
        }
        .type-btn {
            flex: 1;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            background: white;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
        }
        .type-btn i {
            font-size: 22px;
            display: block;
            margin-bottom: 5px;
        }
        .type-btn.active {
            background: #2c3e50;
            color: white;
            border-color: #2c3e50;
        }
        .type-btn.active i {
            color: white;
        }
        .form-control {
            height: 50px;
            border-radius: 12px;
            border: 2px solid #e0e0e0;
            padding-left: 45px;
            margin-bottom: 15px;
            font-size: 15px;
        }
        .form-control:focus {
            border-color: #2c3e50;
            box-shadow: none;
        }
        .input-group {
            position: relative;
        }
        .input-group i {
            position: absolute;
            left: 15px;
            top: 17px;
            color: #666;
            z-index: 10;
            font-size: 18px;
        }
        .btn-login {
            background: #2c3e50;
            color: white;
            height: 50px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            width: 100%;
            border: none;
            margin: 20px 0;
            transition: all 0.3s;
        }
        .btn-login:hover {
            background: #1a252f;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(44,62,80,0.3);
        }
        .demo-box {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 15px;
            font-size: 14px;
            border-left: 5px solid #2c3e50;
            margin: 20px 0;
        }
        .demo-box i {
            color: #2c3e50;
            margin-right: 5px;
        }
        .links {
            text-align: center;
            margin-top: 15px;
        }
        .links a {
            color: #666;
            text-decoration: none;
            margin: 0 10px;
        }
        .links a:hover {
            color: #2c3e50;
        }
        .badge-user {
            background: #27ae60;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            margin-left: 5px;
        }
        .badge-admin {
            background: #e74c3c;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            margin-left: 5px;
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
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="login-box">
                    <!-- Logo -->
                    <div class="logo">
                        <img src="https://cdn-icons-png.flaticon.com/512/1041/1041916.png" alt="Hotel Logo">
                        <h3>The Imperial Crown</h3>
                        <p class="text-muted">Welcome back! Please login</p>
                    </div>

                    <!-- User Type Selection -->
                    <div class="user-type">
                        <div class="type-btn active" onclick="setUserType('user')" id="userBtn">
                            <i class="bi bi-person-circle"></i>
                            User
                            <span class="badge-user">Customer</span>
                        </div>
                        <div class="type-btn" onclick="setUserType('admin')" id="adminBtn">
                            <i class="bi bi-shield-lock"></i>
                            Admin
                            <span class="badge-admin">Staff</span>
                        </div>
                    </div>

                    <!-- Login Form -->
                    <form onsubmit="return false;">
                        <!-- User Fields (Default Visible) -->
                        <div id="userFields">
                            <div class="input-group">
                                <i class="bi bi-envelope-fill"></i>
                                <input type="email" class="form-control" id="userEmail" placeholder="Email address" value="user@demo.com">
                            </div>
                            <div class="input-group">
                                <i class="bi bi-lock-fill"></i>
                                <input type="password" class="form-control" id="userPass" placeholder="Password" value="123456">
                            </div>
                        </div>

                        <!-- Admin Fields (Hidden by Default) -->
                        <div id="adminFields" style="display: none;">
                            <div class="input-group">
                                <i class="bi bi-envelope-fill"></i>
                                <input type="email" class="form-control" id="adminEmail" placeholder="Admin Email" value="admin@demo.com">
                            </div>
                            <div class="input-group">
                                <i class="bi bi-lock-fill"></i>
                                <input type="password" class="form-control" id="adminPass" placeholder="Password" value="admin123">
                            </div>
                            <div class="input-group">
                                <i class="bi bi-shield-check"></i>
                                <input type="password" class="form-control" id="adminCode" placeholder="Security Code" value="ADMIN123">
                            </div>
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>
                            <a href="../forgot_password.php" class="text-decoration-none">Forgot Password?</a>
                        </div>

                        <!-- Login Button -->
                        <button class="btn-login" onclick="login()">
                            <i class="bi bi-box-arrow-in-right me-2"></i>LOGIN
                        </button>
                    </form>

                    <!-- Demo Credentials Box -->
                    <div class="demo-box" id="demoInfo">
                        <i class="bi bi-info-circle-fill"></i>
                        <strong>Demo User:</strong> user@demo.com / 123456
                    </div>

                    <!-- Links -->
                    <div class="links">
                        <a href="register.php"><i class="bi bi-person-plus"></i> Register</a>
                        <span class="text-muted">|</span>
                        <a href="../forgot_password.php"><i class="bi bi-key"></i> Forgot Password</a>
                        <span class="text-muted">|</span>
                        <a href="index.php"><i class="bi bi-house"></i> Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Current selected user type
        let currentType = 'user';

        // Function to switch between User and Admin
        function setUserType(type) {
            currentType = type;
            
            if (type === 'user') {
                // User selected
                document.getElementById('userBtn').classList.add('active');
                document.getElementById('adminBtn').classList.remove('active');
                document.getElementById('userFields').style.display = 'block';
                document.getElementById('adminFields').style.display = 'none';
                document.getElementById('demoInfo').innerHTML = '<i class="bi bi-info-circle-fill"></i> <strong>Demo User:</strong> user@demo.com / 123456';
            } else {
                // Admin selected
                document.getElementById('userBtn').classList.remove('active');
                document.getElementById('adminBtn').classList.add('active');
                document.getElementById('userFields').style.display = 'none';
                document.getElementById('adminFields').style.display = 'block';
                document.getElementById('demoInfo').innerHTML = '<i class="bi bi-info-circle-fill"></i> <strong>Demo Admin:</strong> admin@demo.com / admin123 / ADMIN123';
            }
        }

        function clearErrors() {
            document.querySelectorAll('.field-error').forEach(el => el.remove());
            document.querySelectorAll('.error-border').forEach(el => el.classList.remove('error-border'));
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

        function validateCurrentType() {
            clearErrors();
            let ok = true;
            if (currentType === 'user') {
                const email = document.getElementById('userEmail').value.trim();
                const pass = document.getElementById('userPass').value;
                if (!email) { setFieldError('userEmail', 'Please enter your email'); ok = false; }
                else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { setFieldError('userEmail', 'Please enter a valid email'); ok = false; }
                if (!pass) { setFieldError('userPass', 'Please enter your password'); ok = false; }
            } else {
                const email = document.getElementById('adminEmail').value.trim();
                const pass = document.getElementById('adminPass').value;
                const code = document.getElementById('adminCode').value.trim();
                if (!email) { setFieldError('adminEmail', 'Please enter admin email'); ok = false; }
                else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { setFieldError('adminEmail', 'Please enter a valid email'); ok = false; }
                if (!pass) { setFieldError('adminPass', 'Please enter your password'); ok = false; }
                if (!code) { setFieldError('adminCode', 'Please enter security code'); ok = false; }
            }
            return ok;
        }

        // Login function
        function login() {
            if (!validateCurrentType()) return;
            if (currentType === 'user') {
                // User login check
                const email = document.getElementById('userEmail').value;
                const pass = document.getElementById('userPass').value;
                
                if (email === 'user@demo.com' && pass === '123456') {
                    // Save to localStorage
                    localStorage.setItem('isLoggedIn', 'true');
                    localStorage.setItem('userType', 'user');
                    localStorage.setItem('userEmail', email);
                    localStorage.setItem('userName', 'Demo User');
                    
                    alert('✅ Login Successful! Welcome back!');
                    window.location.href = 'index.php';  // Redirect to Home
                } else {
                    alert('❌ Invalid credentials! Use: user@demo.com / 123456');
                }
            } else {
                // Admin login check
                const email = document.getElementById('adminEmail').value;
                const pass = document.getElementById('adminPass').value;
                const code = document.getElementById('adminCode').value;
                
                if (email === 'admin@demo.com' && pass === 'admin123' && code === 'ADMIN123') {
                    // Save to localStorage
                    localStorage.setItem('isLoggedIn', 'true');
                    localStorage.setItem('userType', 'admin');
                    localStorage.setItem('userEmail', email);
                    localStorage.setItem('userName', 'Admin');
                    
                    alert('✅ Admin Login Successful!');
                    window.location.href = 'admin_dashboard.php';  // Redirect to Admin Panel
                } else {
                    alert('❌ Invalid admin credentials! Use: admin@demo.com / admin123 / ADMIN123');
                }
            }
        }

        // Check if already logged in
        window.onload = function() {
            if (localStorage.getItem('isLoggedIn') === 'true') {
                const userType = localStorage.getItem('userType');
                if (userType === 'admin') {
                    window.location.href = 'admin_dashboard.php';
                } else {
                    window.location.href = 'index.php';
                }
            }
        }
    </script>
</body>
</html>