<?php require_once __DIR__ . '/includes/init.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Imperial Crown Hotel - About Us</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.pexels.com/photos/258154/pexels-photo-258154.jpeg?auto=compress&cs=tinysrgb&w=1920');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            position: relative;
            padding-bottom: 1rem;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: #2c3e50;
        }
        
        .about-image {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .feature-box {
            text-align: center;
            padding: 30px;
            border-radius: 10px;
            transition: all 0.3s;
        }
        
        .feature-box:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            background: #2c3e50;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 20px;
        }
        
        .team-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s;
        }
        
        .team-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }
        
        .team-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 20px auto;
            border: 5px solid #2c3e50;
        }
        
        .stats-section {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
            padding: 60px 0;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
        }
        
        .stat-label {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
        .custom-bg {
            background-color: #2c3e50;
        }
        
        .custom-bg:hover {
            background-color: #1a252f;
        }
        
        .nav-link.active {
            font-weight: 600;
            border-bottom: 2px solid white;
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

<?php require('navbar.php'); ?>

<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <h1 class="display-3 fw-bold">About Imperial Crown Hotel</h1>
        <p class="lead">Experience luxury, comfort, and exceptional hospitality since 2010</p>
    </div>
</div>

<!-- Our Story -->
<div class="container my-5 py-5">
    <h2 class="section-title text-center">Our Story</h2>
    <div class="row align-items-center mt-5">
        <div class="col-lg-6 mb-4">
            <img src="https://images.pexels.com/photos/164595/pexels-photo-164595.jpeg?auto=compress&cs=tinysrgb&w=1200" class="about-image img-fluid" alt="Hotel Story">
        </div>
        <div class="col-lg-6">
            <h3 class="mb-4">A Legacy of Excellence</h3>
            <p class="text-muted">Founded in 2010, The Imperial Crown Hotel has been a beacon of luxury and comfort in the heart of the city. What started as a small boutique hotel has grown into one of the most prestigious addresses in town, known for its exceptional service and attention to detail.</p>
            <p class="text-muted">Over the years, we have welcomed guests from all over the world, providing them with unforgettable experiences and memories that last a lifetime. Our commitment to excellence and continuous improvement has earned us numerous awards and recognition in the hospitality industry.</p>
            <p class="text-muted">Today, we continue to uphold our founding principles of quality, integrity, and hospitality, ensuring that every guest feels like royalty from the moment they step through our doors.</p>
        </div>
    </div>
</div>

<!-- Our Mission & Vision -->
<div class="bg-light py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-5">
                        <div class="feature-icon">
                            <i class="bi bi-bullseye"></i>
                        </div>
                        <h3 class="mb-3">Our Mission</h3>
                        <p class="text-muted">To provide exceptional hospitality experiences that exceed our guests' expectations, creating lasting memories through personalized service, luxurious amenities, and attention to every detail.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-5">
                        <div class="feature-icon">
                            <i class="bi bi-eye"></i>
                        </div>
                        <h3 class="mb-3">Our Vision</h3>
                        <p class="text-muted">To be the premier luxury hotel destination, recognized globally for our unwavering commitment to excellence, innovation in hospitality, and creating a home away from home for every guest.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Our Values -->
<div class="container my-5 py-5">
    <h2 class="section-title text-center">Our Core Values</h2>
    <div class="row mt-5">
        <div class="col-md-3 mb-4">
            <div class="feature-box bg-white shadow-sm">
                <div class="feature-icon">
                    <i class="bi bi-star"></i>
                </div>
                <h4>Excellence</h4>
                <p class="text-muted">Striving for excellence in everything we do, from service to amenities.</p>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="feature-box bg-white shadow-sm">
                <div class="feature-icon">
                    <i class="bi bi-heart"></i>
                </div>
                <h4>Hospitality</h4>
                <p class="text-muted">Every guest is treated with warmth, care, and genuine hospitality.</p>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="feature-box bg-white shadow-sm">
                <div class="feature-icon">
                    <i class="bi bi-shield"></i>
                </div>
                <h4>Integrity</h4>
                <p class="text-muted">Operating with honesty, transparency, and ethical practices.</p>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="feature-box bg-white shadow-sm">
                <div class="feature-icon">
                    <i class="bi bi-people"></i>
                </div>
                <h4>Teamwork</h4>
                <p class="text-muted">Working together to create exceptional experiences for our guests.</p>
            </div>
        </div>
    </div>
</div>

<!-- Management Team -->
<div class="bg-light py-5">
    <div class="container">
        <h2 class="section-title text-center">Our Management Team</h2>
        <div class="row mt-5">
            <div class="col-md-4 mb-4">
                <div class="card team-card shadow">
                    <img src="https://images.pexels.com/photos/2379004/pexels-photo-2379004.jpeg?auto=compress&cs=tinysrgb&w=600" class="team-img" alt="John Doe">
                    <div class="card-body text-center">
                        <h4 class="mb-2">John Doe</h4>
                        <p class="text-primary mb-3">General Manager</p>
                        <p class="text-muted">15+ years of experience in luxury hospitality management.</p>
                        <div class="mt-3">
                            <a href="#" class="text-dark me-2"><i class="bi bi-linkedin"></i></a>
                            <a href="#" class="text-dark"><i class="bi bi-twitter"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card team-card shadow">
                    <img src="https://images.pexels.com/photos/3760263/pexels-photo-3760263.jpeg?auto=compress&cs=tinysrgb&w=600" class="team-img" alt="Jane Smith">
                    <div class="card-body text-center">
                        <h4 class="mb-2">Jane Smith</h4>
                        <p class="text-primary mb-3">Operations Director</p>
                        <p class="text-muted">Ensuring smooth daily operations and guest satisfaction.</p>
                        <div class="mt-3">
                            <a href="#" class="text-dark me-2"><i class="bi bi-linkedin"></i></a>
                            <a href="#" class="text-dark"><i class="bi bi-twitter"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card team-card shadow">
                    <img src="https://images.pexels.com/photos/3778680/pexels-photo-3778680.jpeg?auto=compress&cs=tinysrgb&w=600" class="team-img" alt="Mike Johnson">
                    <div class="card-body text-center">
                        <h4 class="mb-2">Mike Johnson</h4>
                        <p class="text-primary mb-3">Head Chef</p>
                        <p class="text-muted">Culinary expert with international experience in fine dining.</p>
                        <div class="mt-3">
                            <a href="#" class="text-dark me-2"><i class="bi bi-linkedin"></i></a>
                            <a href="#" class="text-dark"><i class="bi bi-twitter"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics -->
<div class="stats-section">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-6 mb-4">
                <div class="stat-item">
                    <div class="stat-number">5000+</div>
                    <div class="stat-label">Happy Guests</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="stat-item">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Luxury Rooms</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="stat-item">
                    <div class="stat-number">15+</div>
                    <div class="stat-label">Years of Service</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Guest Support</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Call to Action -->
<div class="container my-5 py-5 text-center">
    <h2 class="mb-4">Ready to Experience Luxury?</h2>
    <p class="lead mb-4">Book your stay today and enjoy world-class amenities and service.</p>
    <a href="rooms.php" class="btn custom-bg text-white btn-lg px-5 py-3">
        <i class="bi bi-calendar-check me-2"></i>Book Now
    </a>
</div>
<?php require('footer.php'); ?>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>