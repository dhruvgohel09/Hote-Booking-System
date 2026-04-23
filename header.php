<!-- Navbar - Dynamic for Guest/User -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-lg-3 py-lg-2">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="index.php">
      <i class="bi bi-building"></i> The Imperial Crown Hotel
    </a>
    <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto" id="mainNav">
        <li class="nav-item">
          <a class="nav-link active" href="index.php">Home</a>
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

        <?php
        if (session_status() === PHP_SESSION_NONE) {
          session_start();
        }
        $hp_logged_in = isset($_SESSION['user_id']);
        $hp_user_name = $_SESSION['user_name'] ?? '';
        ?>
        <!-- Guest Links (Show when not logged in) -->
        <li class="nav-item guest-links" style="<?php echo $hp_logged_in ? 'display: none;' : ''; ?>">
          <a class="nav-link" href="register.php">Register</a>
        </li>
        <li class="nav-item guest-links" style="<?php echo $hp_logged_in ? 'display: none;' : ''; ?>">
          <a class="nav-link" href="login.php">Login</a>
        </li>

        <!-- User Links (Show when logged in) -->
        <li class="nav-item user-links" style="<?php echo $hp_logged_in ? '' : 'display: none;'; ?>">
          <a class="nav-link" href="#"><i class="bi bi-person-circle me-1"></i> Welcome, <span id="userName">
              <?php echo htmlspecialchars($hp_user_name); ?>
            </span></a>
        </li>
        <li class="nav-item user-links" style="<?php echo $hp_logged_in ? '' : 'display: none;'; ?>">
          <a class="nav-link text-warning" href="#" onclick="logout()"><i class="bi bi-box-arrow-right me-1"></i> LOG
            OUT</a>
        </li>
      </ul>
    </div>
  </div>
</nav>


<!-- Bootstrap Icons (Add in head section) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
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