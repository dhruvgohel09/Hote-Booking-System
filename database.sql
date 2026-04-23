-- =============================================
-- HOTEL BOOKING SYSTEM DATABASE
-- Database Name: hotelbooking
-- Import in phpMyAdmin or: mysql -u root < database.sql
-- =============================================

CREATE DATABASE IF NOT EXISTS hotelbooking;
USE hotelbooking;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS feedback;
DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS offers;
DROP TABLE IF EXISTS contact;
DROP TABLE IF EXISTS password_resets;
DROP TABLE IF EXISTS email_verifications;
DROP TABLE IF EXISTS rooms;
DROP TABLE IF EXISTS users;

SET FOREIGN_KEY_CHECKS = 1;

-- =============================================
-- 1. USERS TABLE
-- =============================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(15),
    dob DATE,
    address TEXT,
    city VARCHAR(50),
    state VARCHAR(50),
    pincode VARCHAR(10),
    role ENUM('user', 'admin') DEFAULT 'user',
    is_active TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =============================================
-- 2. ROOMS TABLE
-- =============================================
CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_name VARCHAR(100) NOT NULL,
    room_type VARCHAR(50) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    capacity INT DEFAULT 2,
    description TEXT,
    image VARCHAR(500),
    status ENUM('available', 'booked', 'maintenance') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =============================================
-- 3. BOOKINGS TABLE
-- =============================================
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    room_id INT NOT NULL,
    guests INT NOT NULL DEFAULT 2,
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    bill_number VARCHAR(40) NULL,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE,

    CHECK (check_out > check_in),
    UNIQUE KEY uk_bill_number (bill_number)
);

-- =============================================
-- 4. PAYMENTS TABLE
-- =============================================
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50),
    payment_status ENUM('paid', 'unpaid') DEFAULT 'unpaid',
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
);

-- =============================================
-- 5. CONTACT TABLE
-- =============================================
CREATE TABLE contact (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(150),
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =============================================
-- 5.5. OFFERS TABLE (DISCOUNTS)
-- =============================================
CREATE TABLE offers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    discount_percentage INT NOT NULL DEFAULT 0,
    target_rooms VARCHAR(255) NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =============================================
-- 6. PASSWORD RESETS (OTP) TABLE
-- =============================================
CREATE TABLE password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    email VARCHAR(100) NOT NULL,
    otp_hash VARCHAR(255) NOT NULL,
    attempts INT NOT NULL DEFAULT 0,
    expires_at DATETIME NOT NULL,
    used_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_email (email),
    INDEX idx_user (user_id),
    INDEX idx_expires (expires_at),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- =============================================
-- 6.5. FEEDBACK TABLE (Reviews)
-- =============================================
CREATE TABLE feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comments TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
);

-- =============================================
-- 7. EMAIL VERIFICATIONS (registration button)
-- =============================================
CREATE TABLE email_verifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    email VARCHAR(100) NOT NULL,
    token_hash VARCHAR(64) NOT NULL,
    expires_at DATETIME NOT NULL,
    used_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_email (email),
    INDEX idx_user (user_id),
    INDEX idx_expires (expires_at),

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- =============================================
-- 6. SEED USERS (bcrypt — use: admin123 / user123)
-- =============================================
INSERT INTO users (first_name, last_name, email, password, phone, role, is_active) VALUES
('Admin', 'User', 'admin@hotel.com', '$2y$10$eNxXtAbuKO1Kok8csTWzouTrnBhs8EiURcpDJI.OwQdVE1IsfK6Se', '9999999999', 'admin', 1),
('Demo', 'User', 'user@hotel.com', '$2y$10$aM3c5ul9w0oA7VNjIAzzc.REkMLgbg9BcxvxROsbZILKMjissed4a', '9876543210', 'user', 1);

-- =============================================
-- 8. SEED ROOMS (image URLs for display)
-- =============================================
INSERT INTO rooms (room_name, room_type, price, capacity, description, image, status) VALUES
('Simple Room 12', 'simple', 300, 2, 'Basic cozy room with essential amenities including wifi and television. Perfect for budget travelers.', 'https://images.pexels.com/photos/237272/pexels-photo-237272.jpeg?auto=compress&cs=tinysrgb&w=600', 'available'),
('Delux Room 104', 'delux', 400, 2, 'Spacious deluxe room with private balcony. Includes geyser, television, and high-speed wifi.', 'https://images.pexels.com/photos/1743227/pexels-photo-1743227.jpeg?auto=compress&cs=tinysrgb&w=600', 'available'),
('Luxury Room 215', 'luxury', 700, 2, 'Premium luxury room with kitchenette and AC. Features modern amenities and elegant decor.', 'https://images.pexels.com/photos/3201761/pexels-photo-3201761.jpeg?auto=compress&cs=tinysrgb&w=600', 'available'),
('Simple Large Room 15', 'simple', 400, 3, 'Large simple room with extra space. Ideal for families on a budget.', 'https://images.pexels.com/photos/1643389/pexels-photo-1643389.jpeg?auto=compress&cs=tinysrgb&w=600', 'available'),
('Delux Large Room 120', 'delux', 800, 3, 'Spacious deluxe room with separate living area. Perfect for families.', 'https://images.pexels.com/photos/635041/pexels-photo-635041.jpeg?auto=compress&cs=tinysrgb&w=600', 'maintenance'),
('Large Luxury Room 225', 'luxury', 1200, 4, 'Luxurious large room with all premium facilities. Includes kitchen, AC, and entertainment system.', 'https://images.pexels.com/photos/1643383/pexels-photo-1643383.jpeg?auto=compress&cs=tinysrgb&w=600', 'available'),
('Super Luxury Room 305', 'luxury', 1800, 4, 'Ultra-premium suite with spa facilities. Includes jacuzzi, mini bar, and private terrace.', 'https://images.pexels.com/photos/271618/pexels-photo-271618.jpeg?auto=compress&cs=tinysrgb&w=600', 'booked'),
('Executive Room 502', 'executive', 2500, 2, 'Executive class room with business amenities. Perfect for corporate travelers.', 'https://images.pexels.com/photos/262048/pexels-photo-262048.jpeg?auto=compress&cs=tinysrgb&w=600', 'available');

-- =============================================
-- 8. SAMPLE BOOKINGS (demo user id = 2)
-- =============================================
INSERT INTO bookings (user_id, room_id, guests, check_in, check_out, total_price, bill_number, status) VALUES
(2, 1, 2, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 3 DAY), 900.00, 'INV-2026-000001', 'confirmed'),
(2, 3, 2, DATE_ADD(CURDATE(), INTERVAL 5 DAY), DATE_ADD(CURDATE(), INTERVAL 8 DAY), 2100.00, 'INV-2026-000002', 'pending'),
(2, 2, 2, DATE_SUB(CURDATE(), INTERVAL 2 DAY), DATE_ADD(CURDATE(), INTERVAL 2 DAY), 1600.00, 'INV-2026-000003', 'confirmed');

INSERT INTO payments (booking_id, amount, payment_method, payment_status) VALUES
(1, 900.00, 'credit_card', 'paid'),
(3, 1600.00, 'upi', 'paid');

INSERT INTO contact (name, email, subject, message) VALUES
('Rahul Sharma', 'rahul@email.com', 'Room Availability', 'Is the deluxe room available for next weekend?'),
('Priya Patel', 'priya@email.com', 'Booking Inquiry', 'I need to book 2 rooms for a family gathering. Please contact me.');

-- =============================================
-- 9. SEED OFFERS (Discounts)
-- =============================================
INSERT INTO offers (title, description, discount_percentage, target_rooms, status) VALUES
('Flash Deal: 50% OFF', 'Massive 50% discount on Simple Room 12 and Delux Room 104', 50, '1,2', 'active');

-- =============================================
-- 10. SEED FEEDBACK (Testimonials)
-- =============================================
INSERT INTO feedback (booking_id, rating, comments) VALUES
(1, 5, 'Amazing experience! The room was very clean and comfortable. The booking process was smooth and staff was very helpful.'),
(2, 4, 'Good hotel with nice ambience. Rooms were well maintained and booking confirmation was quick. Recommended!'),
(3, 5, 'Excellent service and great location. Food was delicious and room service was prompt. Will visit again!');
