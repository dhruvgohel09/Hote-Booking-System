<?php
if (session_status() === PHP_SESSION_NONE) {
    require_once __DIR__ . '/includes/init.php';
}
$hp_logged_in = isset($_SESSION['user_id']);
$hp_user_name = $_SESSION['user_name'] ?? '';
$hp_user_role = $_SESSION['user_role'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Navbar Active</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>

/* Navbar underline effect */
.nav-link{
position:relative;
font-weight:500;
}

.nav-link::after{
content:"";
position:absolute;
left:0;
bottom:-3px;
width:0%;
height:2px;
background:#ffc107;
transition:0.3s;
}

.nav-link:hover::after{
width:100%;
}

.nav-link.active::after{
width:100%;
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

.btn-modal:hover {
    transform: translateY(-2px);
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

.switch-link a:hover {
    text-decoration: underline;
}

.demo-box {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 10px;
    margin-top: 15px;
    text-align: center;
    font-size: 0.8rem;
}

.password-strength {
    font-size: 0.75rem;
    margin-top: 5px;
}

.form-control {
    border-radius: 10px;
    padding: 10px 15px;
}

.form-control:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 0.2rem rgba(52,152,219,0.25);
}

.field-error {
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 5px;
    display: block;
}

.error-border {
    border: 2px solid #dc3545 !important;
}

.profile-pic {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #2c3e50, #3498db);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
}

.profile-pic i {
    font-size: 40px;
    color: white;
}
</style>

</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-lg-3 py-lg-2 fixed-top">
<div class="container-fluid">

<a class="navbar-brand fw-bold" href="index.php">
<i class="bi bi-building"></i> The Imperial Crown Hotel
</a>

<button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
<span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="navbarNav">

<ul class="navbar-nav ms-auto">

<li class="nav-item">
<a class="nav-link" href="index.php">Home</a>
</li>

<li class="nav-item">
<a class="nav-link" href="rooms.php">Rooms</a>
</li>

<li class="nav-item">
<a class="nav-link" href="facilities.php">Facilities</a>
</li>

<li class="nav-item">
<a class="nav-link" href="contact.php">Contact us</a>
</li>

<li class="nav-item">
<a class="nav-link" href="about.php">About</a>
</li>

<!-- Guest Links - Show when not logged in -->
<li class="nav-item guest-links" style="<?php echo $hp_logged_in ? 'display:none' : ''; ?>">
<a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Register</a>
</li>

<li class="nav-item guest-links" style="<?php echo $hp_logged_in ? 'display:none' : ''; ?>">
<a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a>
</li>

<!-- User Links - Show when logged in -->
<li class="nav-item user-links" style="<?php echo $hp_logged_in ? '' : 'display:none'; ?>">
    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#editProfileModal">
        <i class="bi bi-person-circle me-1"></i>
        Welcome, <span id="userName"><?php echo htmlspecialchars($hp_user_name); ?></span>
    </a>
</li>

<li class="nav-item user-links" style="<?php echo $hp_logged_in ? '' : 'display:none'; ?>">
    <a class="nav-link" href="my_booking.php">
        <i class="bi bi-calendar-check me-1"></i> My Bookings
    </a>
</li>

<li class="nav-item user-links" style="<?php echo $hp_logged_in ? '' : 'display:none'; ?>">
    <a class="nav-link text-warning" href="logout.php">
        <i class="bi bi-box-arrow-right me-1"></i> LOG OUT
    </a>
</li>

</ul>
</div>
</div>
</nav>

<!-- Login & Register Modal -->
<div class="modal fade" id="loginModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"><i class="bi bi-box-arrow-in-right"></i> Login</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Login Form -->
                <div id="loginForm">
                    <form id="loginFormSubmit" onsubmit="return false;">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email Address</label>
                            <input type="email" id="loginEmail" class="form-control" placeholder="Enter your email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Password</label>
                            <input type="password" id="loginPassword" class="form-control" placeholder="Enter your password" required>
                        </div>
                        <div id="loginError" class="alert alert-danger" style="display: none;"></div>
                        <button type="submit" class="btn btn-modal btn-primary" onclick="handleLogin()">
                            <i class="bi bi-box-arrow-in-right me-2"></i> Login
                        </button>
                    </form>
                    <div class="switch-link" style="margin-top:8px">
                        <p class="mb-0"><a href="forgot_password.php">Forgot password?</a></p>
                    </div>
                    <div class="demo-box">
                        <small><strong>Demo (database):</strong></small><br>
                        <small>user@hotel.com / user123</small><br>
                        <small>admin@hotel.com / admin123</small>
                    </div>
                    <div class="switch-link">
                        <p>Don't have an account? <a onclick="showRegisterForm()">Register here</a></p>
                    </div>
                </div>
                
                <!-- Register Form -->
                <div id="registerForm" style="display: none;">
                    <form id="registerFormSubmit" onsubmit="return false;">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Full Name</label>
                            <input type="text" id="regName" class="form-control" placeholder="Enter your full name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email Address</label>
                            <input type="email" id="regEmail" class="form-control" placeholder="Enter your email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Password</label>
                            <input type="password" id="regPassword" class="form-control" placeholder="Create a password" required>
                            <div class="password-strength text-muted" id="regPasswordStrength"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Confirm Password</label>
                            <input type="password" id="regConfirmPassword" class="form-control" placeholder="Confirm your password" required>
                        </div>
                        <div id="registerError" class="alert alert-danger" style="display: none;"></div>
                        <div id="registerSuccess" class="alert alert-success" style="display: none;"></div>
                        <button type="submit" class="btn btn-modal btn-primary" onclick="handleRegister()">
                            <i class="bi bi-person-plus me-2"></i> Register
                        </button>
                    </form>
                    <div class="switch-link">
                        <p>Already have an account? <a onclick="showLoginForm()">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil-square"></i> Edit Profile</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <div class="profile-pic">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <h5 id="profileName" class="mt-2"></h5>
                    <p id="profileEmail" class="text-muted"></p>
                </div>
                
                <form id="editProfileForm" onsubmit="return false;">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-person"></i> Full Name
                        </label>
                        <input type="text" id="editName" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-envelope"></i> Email Address
                        </label>
                        <input type="email" id="editEmail" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-telephone"></i> Phone Number
                        </label>
                        <input type="tel" id="editPhone" class="form-control" placeholder="Enter your phone number">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-geo-alt"></i> Address
                        </label>
                        <textarea id="editAddress" class="form-control" rows="2" placeholder="Enter your address"></textarea>
                    </div>
                    
                    <div id="editProfileError" class="alert alert-danger" style="display: none;"></div>
                    <div id="editProfileSuccess" class="alert alert-success" style="display: none;"></div>
                    
                    <button type="submit" class="btn btn-modal btn-primary" onclick="updateProfile()">
                        <i class="bi bi-save me-2"></i> Update Profile
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>

// ============ GLOBAL FUNCTION TO CLOSE MODAL PROPERLY ============
function closeModalProperly(modalId) {
    var modalElement = document.getElementById(modalId);
    if(modalElement) {
        var modal = bootstrap.Modal.getInstance(modalElement);
        if(modal) {
            modal.hide();
        }
    }
    // Remove backdrop
    setTimeout(function() {
        var backdrop = document.querySelectorAll('.modal-backdrop');
        backdrop.forEach(function(el) {
            el.remove();
        });
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    }, 100);
}

function showSuccessAndClose(modalId, message) {
    var successDiv = document.getElementById('editProfileSuccess');
    if(successDiv) {
        successDiv.style.display = 'block';
        successDiv.innerHTML = message;
    }
    setTimeout(function() {
        closeModalProperly(modalId);
    }, 1500);
}

function clearFormErrors(formId) {
    const form = document.getElementById(formId);
    if (!form) return;
    form.querySelectorAll('.field-error').forEach((node) => node.remove());
    form.querySelectorAll('.error-border').forEach((node) => node.classList.remove('error-border'));
}

function setFieldError(inputId, message) {
    const input = document.getElementById(inputId);
    if (!input) return;
    input.classList.add('error-border');
    const error = document.createElement('span');
    error.className = 'field-error';
    error.textContent = message;
    input.insertAdjacentElement('afterend', error);
}

// Show Edit Profile Modal with current data
function showEditProfileModal() {
    const userName = localStorage.getItem('userName') || '';
    const userEmail = localStorage.getItem('userEmail') || '';
    const userPhone = localStorage.getItem('userPhone') || '';
    const userAddress = localStorage.getItem('userAddress') || '';
    
    document.getElementById('profileName').innerText = userName;
    document.getElementById('profileEmail').innerText = userEmail;
    document.getElementById('editName').value = userName;
    document.getElementById('editEmail').value = userEmail;
    document.getElementById('editPhone').value = userPhone;
    document.getElementById('editAddress').value = userAddress;
}

// Update Profile
function updateProfile() {
    const newName = document.getElementById('editName').value;
    const newEmail = document.getElementById('editEmail').value;
    const newPhone = document.getElementById('editPhone').value;
    const newAddress = document.getElementById('editAddress').value;
    const errorDiv = document.getElementById('editProfileError');
    const successDiv = document.getElementById('editProfileSuccess');
    
    errorDiv.style.display = 'none';
    successDiv.style.display = 'none';
    
    if(!newName || !newEmail) {
        errorDiv.style.display = 'block';
        errorDiv.innerHTML = 'Name and Email are required!';
        return;
    }
    
    // Update localStorage
    localStorage.setItem('userName', newName);
    localStorage.setItem('userEmail', newEmail);
    localStorage.setItem('userPhone', newPhone);
    localStorage.setItem('userAddress', newAddress);
    
    // Update profile display in modal
    document.getElementById('profileName').innerText = newName;
    document.getElementById('profileEmail').innerText = newEmail;
    
    // Update navbar user name
    const userNameSpan = document.getElementById('userName');
    if(userNameSpan) {
        userNameSpan.innerText = newName;
    }
    
    // Show success and close modal
    showSuccessAndClose('editProfileModal', 'Profile updated successfully!');
}

// Handle Login (server session via api/login.php)
function handleLogin() {
    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;
    const errorDiv = document.getElementById('loginError');
    
    errorDiv.style.display = 'none';
    clearFormErrors('loginFormSubmit');

    let hasError = false;
    if (!email.trim()) {
        setFieldError('loginEmail', 'Please enter your email address');
        hasError = true;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.trim())) {
        setFieldError('loginEmail', 'Please enter a valid email address');
        hasError = true;
    }
    if (!password) {
        setFieldError('loginPassword', 'Please enter your password');
        hasError = true;
    }
    if (hasError) return;
    
    fetch('api/login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password })
    })
    .then(r => {
        if (r.status === 401) {
            window.location.href = 'login.php?expired=1';
            return null;
        }
        return r.json();
    })
    .then(data => {
        if (data === null) return;
        if (data.success) {
            closeModalProperly('loginModal');
            window.location.href = data.redirect || 'index.php';
        } else {
            errorDiv.style.display = 'block';
            errorDiv.innerHTML = data.message || 'Login failed.';
        }
    })
    .catch(() => {
        errorDiv.style.display = 'block';
        errorDiv.innerHTML = 'Network error. Try again.';
    });
}

// Handle Register (api/register.php)
function handleRegister() {
    const name = document.getElementById('regName').value.trim();
    const email = document.getElementById('regEmail').value.trim();
    const password = document.getElementById('regPassword').value;
    const confirmPassword = document.getElementById('regConfirmPassword').value;
    const errorDiv = document.getElementById('registerError');
    const successDiv = document.getElementById('registerSuccess');
    
    errorDiv.style.display = 'none';
    successDiv.style.display = 'none';
    clearFormErrors('registerFormSubmit');

    let hasError = false;
    if (!name) {
        setFieldError('regName', 'Please enter your full name');
        hasError = true;
    } else if (!/^[A-Za-z]+(?: [A-Za-z]+)+$/.test(name)) {
        setFieldError('regName', 'Please enter first and last name');
        hasError = true;
    }
    if (!email) {
        setFieldError('regEmail', 'Please enter your email address');
        hasError = true;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        setFieldError('regEmail', 'Please enter a valid email address');
        hasError = true;
    }
    if (!password) {
        setFieldError('regPassword', 'Please enter your password');
        hasError = true;
    } else if (password.length < 8 || password.length > 128 || !/[A-Za-z]/.test(password) || !/[0-9]/.test(password)) {
        setFieldError('regPassword', 'Use 8-128 chars with letters and numbers');
        hasError = true;
    }
    if (!confirmPassword) {
        setFieldError('regConfirmPassword', 'Please confirm your password');
        hasError = true;
    } else if (password !== confirmPassword) {
        setFieldError('regConfirmPassword', 'Passwords do not match');
        hasError = true;
    }
    if (hasError) return;
    
    const parts = name.split(/\s+/).filter(Boolean);
    const first_name = parts[0] || name;
    const last_name = parts.length > 1 ? parts.slice(1).join(' ') : first_name;
    
    fetch('api/register.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ first_name, last_name, email, password, phone: '9876543210', address: '', city: '', state: '', pincode: '' })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            successDiv.style.display = 'block';
            successDiv.innerHTML = data.message || 'Registration successful! Please login.';
            document.getElementById('regName').value = '';
            document.getElementById('regEmail').value = '';
            document.getElementById('regPassword').value = '';
            document.getElementById('regConfirmPassword').value = '';
            const requiresVerification = !!data.requires_verification;
            if (!requiresVerification) {
                setTimeout(function() {
                    showLoginForm();
                    successDiv.style.display = 'none';
                }, 2000);
            }
        } else {
            errorDiv.style.display = 'block';
            errorDiv.innerHTML = data.message || 'Registration failed.';
        }
    })
    .catch(() => {
        errorDiv.style.display = 'block';
        errorDiv.innerHTML = 'Network error. Try again.';
    });
}

function showLoginForm() {
    document.getElementById('loginForm').style.display = 'block';
    document.getElementById('registerForm').style.display = 'none';
    document.getElementById('modalTitle').innerHTML = '<i class="bi bi-box-arrow-in-right"></i> Login';
}

function showRegisterForm() {
    document.getElementById('loginForm').style.display = 'none';
    document.getElementById('registerForm').style.display = 'block';
    document.getElementById('modalTitle').innerHTML = '<i class="bi bi-person-plus"></i> Register';
}

// Password strength checker
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('regPassword');
    if(passwordInput) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strengthDiv = document.getElementById('regPasswordStrength');
            
            if(password.length === 0) {
                strengthDiv.innerHTML = '';
                return;
            }
            
            let strength = '';
            let color = '';
            
            if(password.length < 4) {
                strength = 'Weak';
                color = 'red';
            } else if(password.length >= 4 && password.length < 8) {
                strength = 'Medium';
                color = 'orange';
            } else {
                strength = 'Strong';
                color = 'green';
            }
            
            strengthDiv.innerHTML = `<span style="color: ${color};">Password Strength: ${strength}</span>`;
        });
    }
    
    // Set up edit profile modal event
    const editProfileModal = document.getElementById('editProfileModal');
    if(editProfileModal) {
        editProfileModal.addEventListener('show.bs.modal', function() {
            showEditProfileModal();
        });
    }
});

// ============ AUTO REMOVE BACKDROP ON ANY MODAL CLOSE ============
document.addEventListener('DOMContentLoaded', function() {
    var modals = document.querySelectorAll('.modal');
    modals.forEach(function(modal) {
        modal.addEventListener('hidden.bs.modal', function() {
            setTimeout(function() {
                var backdrop = document.querySelectorAll('.modal-backdrop');
                backdrop.forEach(function(el) {
                    el.remove();
                });
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
            }, 100);
        });
    });
});

/* ACTIVE PAGE UNDERLINE */
document.addEventListener("DOMContentLoaded",function(){

let currentPage = location.pathname.split("/").pop();
let navLinks = document.querySelectorAll(".nav-link");

navLinks.forEach(link=>{
let linkPage = link.getAttribute("href");
if(linkPage === currentPage){
link.classList.add("active");
}
});

});

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>