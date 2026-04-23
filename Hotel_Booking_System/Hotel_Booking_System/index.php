<?php require_once __DIR__ . '/includes/init.php'; ?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Imperial Crown Hotel - Home</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    
    <style>  
        .availability-form {
            margin-top: -50px;
            z-index: 2;
            position: relative;
        }

        .custom-bg {
            background-color: #2c3e50;
        }
        
        .custom-bg:hover {
            background-color: #1a252f;
        }

        @media screen and (max-width: 575px) {
            .availability-form {
                margin-top: 25px;
                padding: 0 35px;
            } 
        }

        .card {
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2) !important;
        }
        
        .badge {
            font-weight: 400;
            padding: 6px 12px;
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
        
        /* Welcome Hero Section */
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.pexels.com/photos/258154/pexels-photo-258154.jpeg?auto=compress&cs=tinysrgb&w=1920');
            background-size: cover;
            background-position: center;
            color: white;
            text-align: center;
            padding: 120px 0;
            margin-top: 0;
        }
        
        .hero-section h1 {
            font-size: 4rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }
        
        .hero-section p {
            font-size: 1.8rem;
            margin-bottom: 30px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        }
        
        .btn-hero {
            background: #ffc107;
            color: #2c3e50;
            padding: 15px 50px;
            font-size: 1.3rem;
            font-weight: 600;
            border: none;
            border-radius: 50px;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-hero:hover {
            background: white;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
        }

        /* Guest Alert Styles */
        .guest-alert {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 12px 20px;
            margin-bottom: 20px;
        }
        
        .guest-alert a {
            color: white;
            text-decoration: underline;
            font-weight: 600;
        }
        
        .guest-alert a:hover {
            color: #ffd700;
        }
    </style>
</head>
<body class="bg-light">

<?php require('navbar.php'); ?>

<!-- Hero Section with Welcome Message -->
<div class="hero-section">
    <div class="container">
        <h1 id="heroTitle">Experience Royal Luxury & Comfort</h1>
        <p id="heroSubtitle">Book Your Stay Today</p>
        <a href="rooms.php" class="btn-hero">Book Your Stay</a>
    </div>
</div>

<!-- Carousel -->
<div class="container-fluid px-lg-4 mt-4">
    <div class="swiper Swiper-container">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <img src="https://images.pexels.com/photos/258154/pexels-photo-258154.jpeg?auto=compress&cs=tinysrgb&w=1200" class="w-100 d-block" style="height: 500px; object-fit: cover;" alt="Hotel Exterior"/>
            </div>
            <div class="swiper-slide">
                <img src="https://images.pexels.com/photos/164595/pexels-photo-164595.jpeg?auto=compress&cs=tinysrgb&w=1200" class="w-100 d-block" style="height: 500px; object-fit: cover;" alt="Hotel Lobby"/>
            </div>
            <div class="swiper-slide">
                <img src="https://images.pexels.com/photos/261102/pexels-photo-261102.jpeg?auto=compress&cs=tinysrgb&w=1200" class="w-100 d-block" style="height: 500px; object-fit: cover;" alt="Luxury Room"/>
            </div>
            <div class="swiper-slide">
                <img src="https://images.pexels.com/photos/941861/pexels-photo-941861.jpeg?auto=compress&cs=tinysrgb&w=1200" class="w-100 d-block" style="height: 500px; object-fit: cover;" alt="Restaurant"/>
            </div>
            <div class="swiper-slide">
                <img src="https://images.pexels.com/photos/261101/pexels-photo-261101.jpeg?auto=compress&cs=tinysrgb&w=1200" class="w-100 d-block" style="height: 500px; object-fit: cover;" alt="Swimming Pool"/>
            </div>
            <div class="swiper-slide">
                <img src="https://images.pexels.com/photos/271624/pexels-photo-271624.jpeg?auto=compress&cs=tinysrgb&w=1200" class="w-100 d-block" style="height: 500px; object-fit: cover;" alt="Deluxe Room"/>
            </div>
        </div>
    </div>
</div>

<!-- Check availability form -->
<div class="container availability-form">
    <div class="row">
        <div class="col-lg-12 bg-white shadow p-4 rounded">
            <h5 class="mb-4">Check Booking Availability</h5>
            <form id="availabilityForm" onsubmit="return false;">
                <div class="row align-items-end">
                    <div class="col-lg-3 mb-3">
                        <label class="form-label" style="font-weight: 500;">Check-in</label>
                        <input type="date" class="form-control shadow-none" id="checkin">
                    </div>
                    <div class="col-lg-3 mb-3">
                        <label class="form-label" style="font-weight: 500;">Check-out</label>
                        <input type="date" class="form-control shadow-none" id="checkout">
                    </div>
                    <div class="col-lg-3 mb-3">
                        <label class="form-label" style="font-weight: 500;">Adult</label>
                        <select class="form-select shadow-none" id="adult">
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                            <option value="4">Four</option>
                        </select>
                    </div>
                    <div class="col-lg-2 mb-3">
                        <label class="form-label" style="font-weight: 500;">Children</label>
                        <select class="form-select shadow-none" id="children">
                            <option value="0">Zero</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                    <div class="col-lg-1 mb-lg-3 mt-2">
                        <button type="button" class="btn text-white shadow-none custom-bg" onclick="checkAvailability()">Submit</button>
                    </div>
                </div>
            </form>
            <div id="availabilityMessage" class="mt-3"></div>
        </div>
    </div>
</div>

<!-- Our Rooms -->
<h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">OUR ROOMS</h2>

<div class="container">
    <div class="row">
        <!-- Room 1 - Simple Room -->
        <div class="col-lg-4 col-md-6 my-3">           
            <div class="card border-0 shadow" style="max-width: 350px; margin: auto;">
                <img src="https://images.pexels.com/photos/271624/pexels-photo-271624.jpeg?auto=compress&cs=tinysrgb&w=600" class="card-img-top" style="height: 220px; object-fit: cover;" alt="Simple Room">           
                <div class="card-body">
                    <h5>Simple Room : 12</h5>
                    <h6 class="mb-4">₹300 per night</h6>
                    <div class="features mb-4">
                        <h6 class="mb-1">Features</h6>
                        <span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>bedroom</span>
                    </div>
                    <div class="facilities mb-4">
                        <h6 class="mb-1">Facilities</h6>
                        <span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>Wifi</span>
                        <span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>Television</span>
                    </div>
                    <div class="guests mb-4">
                        <h6 class="mb-1">Guests</h6>
                        <span class="badge rounded-pill bg-light text-dark text-wrap">2 Adults</span>
                        <span class="badge rounded-pill bg-light text-dark text-wrap">2 children</span>
                    </div>
                    <div class="rating mb-4">
                        <h6 class="mb-1">Rating</h6>
                        <span class="badge rounded-pill bg-light">
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-half text-warning"></i>
                        </span>
                    </div>
                    <div class="d-flex justify-content-evenly mb-2">
                        <button class="btn btn-sm text-white custom-bg shadow-none" onclick="handleBookNow(1)">Book Now</button>
                        <a href="room_details.php?id=1" class="btn btn-sm btn-outline-dark shadow-none">More details</a>
                    </div>
                </div>
            </div>       
        </div>

        <!-- Room 2 - Delux Room -->
        <div class="col-lg-4 col-md-6 my-3">           
            <div class="card border-0 shadow" style="max-width: 350px; margin: auto;">
                <img src="https://images.pexels.com/photos/164595/pexels-photo-164595.jpeg?auto=compress&cs=tinysrgb&w=600" class="card-img-top" style="height: 220px; object-fit: cover;" alt="Delux Room">           
                <div class="card-body">
                    <h5>Delux Room : 104</h5>
                    <h6 class="mb-4">₹400 per night</h6>
                    <div class="features mb-4">
                        <h6 class="mb-1">Features</h6>
                        <span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>bedroom</span>
                        <span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>balcony</span>
                    </div>
                    <div class="facilities mb-4">
                        <h6 class="mb-1">Facilities</h6>
                        <span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>Geyser</span>
                        <span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>Television</span>
                        <span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>Wifi</span>
                    </div>
                    <div class="guests mb-4">
                        <h6 class="mb-1">Guests</h6>
                        <span class="badge rounded-pill bg-light text-dark text-wrap">2 Adults</span>
                        <span class="badge rounded-pill bg-light text-dark text-wrap">2 children</span>
                    </div>
                    <div class="rating mb-4">
                        <h6 class="mb-1">Rating</h6>
                        <span class="badge rounded-pill bg-light">
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                        </span>
                    </div>
                    <div class="d-flex justify-content-evenly mb-2">
                        <button class="btn btn-sm text-white custom-bg shadow-none" onclick="handleBookNow(2)">Book Now</button>
                        <a href="room_details.php?id=2" class="btn btn-sm btn-outline-dark shadow-none">More details</a>
                    </div>
                </div>
            </div>       
        </div>

        <!-- Room 3 - Luxury Room -->
        <div class="col-lg-4 col-md-6 my-3">           
            <div class="card border-0 shadow" style="max-width: 350px; margin: auto;">
                <img src="https://images.pexels.com/photos/261102/pexels-photo-261102.jpeg?auto=compress&cs=tinysrgb&w=600" class="card-img-top" style="height: 220px; object-fit: cover;" alt="Luxury Room">           
                <div class="card-body">
                    <h5>Luxury Room: 215</h5>
                    <h6 class="mb-4">₹700 per night</h6>
                    <div class="features mb-4">
                        <h6 class="mb-1">Features</h6>
                        <span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>bedroom</span>
                        <span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>balcony</span>
                        <span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>kitchen</span>
                    </div>
                    <div class="facilities mb-4">
                        <h6 class="mb-1">Facilities</h6>
                        <span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>Geyser</span>
                        <span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>Television</span>
                        <span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>Wifi</span>
                        <span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>AC</span>
                    </div>
                    <div class="guests mb-4">
                        <h6 class="mb-1">Guests</h6>
                        <span class="badge rounded-pill bg-light text-dark text-wrap">2 Adults</span>
                        <span class="badge rounded-pill bg-light text-dark text-wrap">2 children</span>
                    </div>
                    <div class="rating mb-4">
                        <h6 class="mb-1">Rating</h6>
                        <span class="badge rounded-pill bg-light">
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                        </span>
                    </div>
                    <div class="d-flex justify-content-evenly mb-2">
                        <button class="btn btn-sm text-white custom-bg shadow-none" onclick="handleBookNow(3)">Book Now</button>
                        <a href="room_details.php?id=3" class="btn btn-sm btn-outline-dark shadow-none">More details</a>
                    </div>
                </div>
            </div>       
        </div>
    
        <div class="col-lg-12 text-center mt-5">
            <a href="rooms.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none px-4 py-2">More Rooms >>></a>
        </div>
    </div>
</div>

<!-- Our Facilities -->
<h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">OUR FACILITIES</h2>

<div class="container">
    <div class="row justify-content-evenly px-lg-0 px-md-0 px-5">
        <div class="col-lg-2 col-md-3 col-6 text-center bg-white rounded shadow py-4 my-3 mx-2">
            <img src="https://cdn-icons-png.flaticon.com/512/1041/1041916.png" width="60px" alt="Wifi">
            <h5 class="mt-3">Wifi</h5>
        </div>
        <div class="col-lg-2 col-md-3 col-6 text-center bg-white rounded shadow py-4 my-3 mx-2">
            <img src="https://cdn-icons-png.flaticon.com/512/3105/3105830.png" width="60px" alt="Geyser">
            <h5 class="mt-3">Geyser</h5>
        </div>
        <div class="col-lg-2 col-md-3 col-6 text-center bg-white rounded shadow py-4 my-3 mx-2">
            <img src="https://cdn-icons-png.flaticon.com/512/477/477176.png" width="60px" alt="Television">
            <h5 class="mt-3">Television</h5>
        </div>
        <div class="col-lg-2 col-md-3 col-6 text-center bg-white rounded shadow py-4 my-3 mx-2">
            <img src="https://cdn-icons-png.flaticon.com/512/3226/3226410.png" width="60px" alt="AC">
            <h5 class="mt-3">AC</h5>
        </div>
        <div class="col-lg-2 col-md-3 col-6 text-center bg-white rounded shadow py-4 my-3 mx-2">
            <img src="https://cdn-icons-png.flaticon.com/512/3227/3227141.png" width="60px" alt="Heater">
            <h5 class="mt-3">Room Heater</h5>
        </div>
        <div class="col-lg-12 text-center mt-5">
            <a href="facilities.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none px-4 py-2">More Facilities >>></a>
        </div>
    </div>
</div>

<!-- Testimonials -->
<h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">TESTIMONIALS</h2>

<div class="container mt-5">
    <div class="swiper swiper-testimonials">
        <div class="swiper-wrapper mb-5">
            <div class="swiper-slide bg-white p-4">
                <div class="profile d-flex align-items-center mb-3">
                    <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" width="40px" alt="User">
                    <h6 class="m-0 ms-2">Rahul Sharma</h6>
                </div>
                <p>Amazing experience! The room was very clean and comfortable. The booking process was smooth and staff was very helpful.</p>
                <div class="rating">
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                </div>
            </div>
            <div class="swiper-slide bg-white p-4">
                <div class="profile d-flex align-items-center mb-3">
                    <img src="https://cdn-icons-png.flaticon.com/512/3135/3135789.png" width="40px" alt="User">
                    <h6 class="m-0 ms-2">Priya Patel</h6>
                </div>
                <p>Good hotel with nice ambience. Rooms were well maintained and booking confirmation was quick. Recommended!</p>
                <div class="rating">
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-half text-warning"></i>
                </div>
            </div>
            <div class="swiper-slide bg-white p-4">
                <div class="profile d-flex align-items-center mb-3">
                    <img src="https://cdn-icons-png.flaticon.com/512/3135/3135823.png" width="40px" alt="User">
                    <h6 class="m-0 ms-2">Amit Kumar</h6>
                </div>
                <p>Excellent service and great location. Food was delicious and room service was prompt. Will visit again!</p>
                <div class="rating">
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                </div>
            </div>
        </div>
        <div class="swiper-pagination"></div>
    </div>
    <div class="col-lg-12 text-center mt-5">
        <a href="about.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none px-4 py-2">Know More >>></a>
    </div>
</div>

<!-- Reach Us -->
<h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">REACH US</h2>

<div class="container">
    <div class="row">
        <div class="col-lg-8 col-md-8 p-4 mb-lg-0 mb-3 bg-white rounded">
            <iframe class="w-100 rounded" height="320px" 
                src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d448182.50738077075!2d77.0932634!3d28.6469655!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3959b46477b75f8b%3A0x8cbae52fb37adb10!2sRajkot%2C%20Gujarat!5e0!3m2!1sen!2sin!4v1734576515441!5m2!1sen!2sin" 
                loading="lazy" allowfullscreen>
            </iframe>
        </div>
        <div class="col-lg-4 col-md-4">
            <div class="bg-white p-4 rounded mb-4">
                <h5>Call us</h5>
                <a href="tel:+91912345675800" class="d-inline-block mb-2 text-decoration-none text-dark">
                    <i class="bi bi-telephone-fill"></i> +91 912345675800
                </a>
                <br>
                <a href="tel:+919919987989800" class="d-inline-block mb-2 text-decoration-none text-dark">
                    <i class="bi bi-telephone-fill"></i> +91 9919987989800
                </a>
            </div>
            
            <div class="bg-white p-4 rounded">
                <h5>Address</h5>
                <p class="mb-0">
                    <i class="bi bi-geo-alt-fill"></i> 
                    XYZ, Rajkot, Gujarat, India<br>Pincode: 360001
                </p>
            </div>
        </div>
    </div>
</div>

<?php require('footer.php'); ?>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    const phpLoggedIn = <?php echo !empty($_SESSION['user_id']) ? 'true' : 'false'; ?>;
    const phpUserName = <?php echo json_encode($_SESSION['user_name'] ?? ''); ?>;
    $(document).ready(function() {
        if (phpLoggedIn) {
            $('.guest-links').hide();
            $('.user-links').show();
            $('#userName').text(phpUserName);
            $('#guestAlert').hide();
            $('#heroTitle').text('Welcome Back, ' + phpUserName + '!');
            $('#heroSubtitle').text('Your Luxury Stay Awaits');
        } else {
            $('.guest-links').show();
            $('.user-links').hide();
            $('#guestAlert').show();
            $('#heroTitle').text('Experience Royal Luxury & Comfort');
            $('#heroSubtitle').text('Book Your Stay Today');
        }

        // Set minimum dates for check-in/out
        const today = new Date().toISOString().split('T')[0];
        $('#checkin').attr('min', today);
        $('#checkout').attr('min', today);
        
        $('#checkin').on('change', function() {
            $('#checkout').attr('min', $(this).val());
        });

        // Swiper initialization
        var swiper1 = new Swiper(".Swiper-container", {
            spaceBetween: 30,
            effect: "fade",
            loop: true,
            autoplay: {
                delay: 3500,
                disableOnInteraction: false,
            }
        });

        var swiper2 = new Swiper(".swiper-testimonials", {
            effect: "coverflow",
            grabCursor: true,
            centeredSlides: true,
            slidesPerView: "auto",
            loop: true,
            coverflowEffect: {
                rotate: 50,
                stretch: 0,
                depth: 100,
                modifier: 1,
                slideShadows: true,
            },
            pagination: {
                el: ".swiper-pagination",
            },
        });
    });

    // ==================== HANDLE BOOK NOW CLICK ====================
    function handleBookNow(roomId) {
        if (phpLoggedIn) {
            window.location.href = 'booking.php?room_id=' + roomId;
        } else {
            window.location.href = 'login.php?redirect=booking&room=' + roomId;
        }
    }

    // ==================== CHECK AVAILABILITY ====================
    function checkAvailability() {
        const checkin = $('#checkin').val();
        const checkout = $('#checkout').val();
        const adult = $('#adult').val();
        const children = $('#children').val();
        
        if(!checkin || !checkout) {
            alert('Please select check-in and check-out dates');
            return;
        }
        
        // Check if user is logged in
        if (phpLoggedIn) {
            window.location.href = `rooms.php?checkin=${checkin}&checkout=${checkout}&adult=${adult}&children=${children}`;
        } else {
            window.location.href = `login.php?redirect=search&checkin=${checkin}&checkout=${checkout}&adult=${adult}&children=${children}`;
        }
    }

    // ==================== LOGOUT FUNCTION ====================
    function logout() {
        if (confirm('Are you sure you want to logout?')) {
            window.location.href = 'logout.php';
        }
    }



</script>

</body>
</html>