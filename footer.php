<!-- footer.php - Ek var banavo, badha ma use karo -->
<footer class="bg-dark text-white pt-5 pb-4 mt-5">
    <div class="container">
        <div class="row text-center text-md-start">
            <!-- Hotel Info -->
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-building text-warning me-2"></i><?= htmlspecialchars($GLOBAL_SETTINGS['site_title'] ?? 'Imperial Crown') ?>
                </h5>
                <p class="small text-secondary"><?= htmlspecialchars($GLOBAL_SETTINGS['site_about'] ?? 'Experience luxury and comfort at its best. Book your stay with us for unforgettable memories.') ?></p>
                <div class="mt-3">
                    <a href="https://www.facebook.com" target="_blank" class="text-white me-3"><i class="bi bi-facebook fs-5"></i></a>
                    <a href="https://www.instagram.com" target="_blank" class="text-white me-3"><i class="bi bi-instagram fs-5"></i></a>
                    <a href="https://twitter.com" target="_blank" class="text-white me-3"><i class="bi bi-twitter fs-5"></i></a>
                    <a href="https://wa.me/919123456789" target="_blank" class="text-white"><i class="bi bi-whatsapp fs-5"></i></a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-link text-warning me-2"></i>Quick Links</h5>
                <ul class="list-unstyled">
                    <?php $footer_logged_in = isset($_SESSION['user_id']); ?>
                    <li class="mb-2"><a href="index.php" class="text-white text-decoration-none"><i class="bi bi-chevron-right me-2 small"></i>Home</a></li>
                    <li class="mb-2"><a href="rooms.php" class="text-white text-decoration-none"><i class="bi bi-chevron-right me-2 small"></i>Rooms</a></li>
                    <li class="mb-2"><a href="facilities.php" class="text-white text-decoration-none"><i class="bi bi-chevron-right me-2 small"></i>Facilities</a></li>
                    <li class="mb-2"><a href="contact.php" class="text-white text-decoration-none"><i class="bi bi-chevron-right me-2 small"></i>Contact</a></li>
                    <li class="mb-2"><a href="about.php" class="text-white text-decoration-none"><i class="bi bi-chevron-right me-2 small"></i>About</a></li>
                    <?php if (!$footer_logged_in): ?>
                    <li class="mb-2"><a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" onclick="showRegisterForm()" class="text-white text-decoration-none"><i class="bi bi-chevron-right me-2 small"></i>Register</a></li>
                    <li class="mb-2"><a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" onclick="showLoginForm()" class="text-white text-decoration-none"><i class="bi bi-chevron-right me-2 small"></i>Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-telephone text-warning me-2"></i>Contact</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="bi bi-geo-alt text-warning me-2"></i><?= htmlspecialchars($GLOBAL_SETTINGS['contact_address'] ?? 'Rajkot, Gujarat') ?></li>
                    <li class="mb-2"><i class="bi bi-telephone text-warning me-2"></i><?= htmlspecialchars($GLOBAL_SETTINGS['contact_phone1'] ?? '+91 91234 56789') ?></li>
                    <li class="mb-2"><i class="bi bi-envelope text-warning me-2"></i><?= htmlspecialchars($GLOBAL_SETTINGS['contact_email1'] ?? 'info@imperialcrown.com') ?></li>
                </ul>
                <hr class="bg-secondary">
                <p class="small mb-0"><i class="bi bi-clock text-warning me-2"></i>24/7 Customer Support</p>
            </div>
        </div>

        <!-- Copyright -->
        <div class="row">
            <div class="col-12 text-center pt-3">
                <hr class="bg-secondary">
                <p class="small mb-0">&copy; 2026 The Imperial Crown Hotel. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>