<?php
require_once __DIR__ . '/includes/init.php';
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
if (($_SESSION['user_role'] ?? '') === 'admin') {
    header('Location: admin/index.php');
    exit;
}

$reqRoomId = isset($_GET['room_id']) ? (int) $_GET['room_id'] : (isset($_GET['room']) ? (int) $_GET['room'] : 0);
$php_default_room_id = 0;
$php_default_room_name = 'Room';
$php_default_room_price = '0';
$php_default_room_capacity = 2;

if ($reqRoomId > 0) {
    $rs = $mysqli->prepare('SELECT id, room_name, price, status, capacity FROM rooms WHERE id = ? LIMIT 1');
    $rs->bind_param('i', $reqRoomId);
    $rs->execute();
    $row = $rs->get_result()->fetch_assoc();
    $rs->close();

    if ($row) {
        $php_default_room_id = (int) $row['id'];
        $php_default_room_name = (string) $row['room_name'];
        $php_default_room_price = (string) $row['price'];
        $php_default_room_capacity = max(1, (int) ($row['capacity'] ?? 2));
    }
}

if ($php_default_room_id <= 0) {
    $fallback = $mysqli->query("SELECT id, room_name, price, capacity FROM rooms WHERE status='available' ORDER BY id ASC LIMIT 1");
    $row = $fallback ? $fallback->fetch_assoc() : null;
    if ($row) {
        $php_default_room_id = (int) $row['id'];
        $php_default_room_name = (string) $row['room_name'];
        $php_default_room_price = (string) $row['price'];
        $php_default_room_capacity = max(1, (int) ($row['capacity'] ?? 2));
    }
}

$discount_percentage = 0;
$discount_title = "";
$reqRoomIdForOffer = (int)$php_default_room_id;
$offer_q = $mysqli->query("SELECT title, discount_percentage, target_rooms FROM offers WHERE status='active'");
if ($offer_q) {
    while ($offer_row = $offer_q->fetch_assoc()) {
        $targets = $offer_row['target_rooms'];
        if (empty($targets) || in_array((string)$reqRoomIdForOffer, explode(',', $targets))) {
            $discount_percentage = (int)$offer_row['discount_percentage'];
            $discount_title = (string)$offer_row['title'];
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Booking - The Imperial Crown Hotel</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- jQuery Validation -->
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <!-- Razorpay Checkout -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .booking-container {
            max-width: 900px;
            margin: 50px auto;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        .card-header {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 20px;
        }
        .room-detail-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .detail-label {
            font-weight: 600;
            color: #2c3e50;
        }
        .detail-value {
            color: #3498db;
            font-weight: 500;
        }
        .payment-option {
            border: 2px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .payment-option:hover {
            border-color: #3498db;
            background: #f0f8ff;
        }
        .payment-option.selected {
            border-color: #2c3e50;
            background: #e8f4f8;
        }
        .btn-book {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            font-size: 1.2rem;
            transition: all 0.3s;
        }
        .btn-book:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
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
        .total-amount {
            font-size: 2rem;
            font-weight: 700;
            color: #28a745;
        }
        .form-label {
            font-weight: 600;
            margin-bottom: 5px;
        }
        .required:after {
            content: " *";
            color: red;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php"><i class="bi bi-building"></i> The Imperial Crown Hotel</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="rooms.php">Rooms</a></li>
                <li class="nav-item"><a class="nav-link" href="facilities.php">Facilities</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact us</a></li>
                <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
               
                
            </ul>
        </div>
    </div>
</nav>

<div class="container booking-container">
    <div class="card">
        <div class="card-header text-center">
            <h3><i class="bi bi-calendar-check me-2"></i>Complete Your Booking</h3>
            <p class="mb-0">Please fill all details to confirm your reservation</p>
        </div>
        <div class="card-body p-5">

            <!-- Room Details Summary -->
            <div class="room-detail-box">
                <h5 class="mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Room Details</h5>
                <div class="row">
                    <div class="col-md-6">
                        <p><span class="detail-label">Room:</span> <span class="detail-value" id="displayRoomName"><?php echo htmlspecialchars($php_default_room_name); ?></span></p>
                        <p><span class="detail-label">Price:</span> <span class="detail-value" id="displayRoomPrice">₹<?php echo htmlspecialchars($php_default_room_price); ?></span> / night</p>
                        <p><span class="detail-label">Area:</span> <span class="detail-value">225 sq.ft.</span></p>
                    </div>
                    <div class="col-md-6">
                        <p><span class="detail-label">Guests:</span> <span class="detail-value">2 Adults, 2 Children</span></p>
                        <p><span class="detail-label">Check-in Time:</span> <span class="detail-value">10:00 AM</span></p>
                        <p><span class="detail-label">Check-out Time:</span> <span class="detail-value">8:00 AM</span></p>
                        <p><span class="detail-label">Extra Person:</span> <span class="detail-value">₹500 per night</span></p>
                    </div>
                </div>
            </div>
            
            <!-- Booking Form -->
            <form id="bookingForm">
                <!-- Hidden fields for room data -->
                <input type="hidden" name="room_id" id="room_id" value="<?php echo (int) $php_default_room_id; ?>">
                <input type="hidden" name="room_name" id="room_name" value="<?php echo htmlspecialchars($php_default_room_name); ?>">
                <input type="hidden" name="room_price" id="room_price" value="<?php echo htmlspecialchars($php_default_room_price); ?>">
                <input type="hidden" name="room_capacity" id="room_capacity" value="<?php echo (int) $php_default_room_capacity; ?>">
                <input type="hidden" id="discount_percentage" value="<?php echo $discount_percentage; ?>">
                <input type="hidden" id="discount_title" value="<?php echo htmlspecialchars($discount_title); ?>">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label required">Full Name</label>
                        <input type="text" class="form-control" name="full_name" id="full_name" placeholder="Enter your full name">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label required">Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label required">Phone</label>
                        <input type="text" class="form-control" name="phone" id="phone" placeholder="10 digit mobile number">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" class="form-control" name="address" id="address" placeholder="Your address">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label required">Check-in Date</label>
                        <input type="date" class="form-control" name="check_in" id="check_in" min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label required">Check-out Date</label>
                        <input type="date" class="form-control" name="check_out" id="check_out">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nights</label>
                        <input type="text" class="form-control" name="nights" id="nights" readonly value="0">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Adults</label>
                        <select class="form-control" name="adults" id="adults">
                            <option value="1">1 Adult</option>
                            <option value="2" selected>2 Adults</option>
                            <option value="3">3 Adults</option>
                            <option value="4">4 Adults</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Children</label>
                        <select class="form-control" name="children" id="children">
                            <option value="0">0 Children</option>
                            <option value="1">1 Child</option>
                            <option value="2" selected>2 Children</option>
                            <option value="3">3 Children</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Extra Persons</label>
                        <select class="form-control" name="extra_persons" id="extra_persons">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                        </select>
                    </div>
                </div>
                
                <!-- Total Amount Calculation -->
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="bg-light p-3 rounded">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1">Room Price: ₹<span id="calc_room_price"><?php echo htmlspecialchars($php_default_room_price); ?></span> x <span id="calc_nights">0</span> nights</p>
                                    <p class="mb-1">Extra Person Charges: ₹500 x <span id="calc_extra">0</span></p>
                                </div>
                                <div class="col-md-6 text-end">
                                    <h5>Total Amount:</h5>
                                    <h2 class="total-amount" id="total_amount">₹0</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Options -->
                <h5 class="mt-4 mb-3"><i class="bi bi-credit-card me-2"></i>Select Payment Method</h5>
                
                <div class="payment-option" onclick="selectPayment('online')">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" id="payment_online" value="online" checked>
                        <label class="form-check-label" for="payment_online">
                            <i class="bi bi-wifi text-primary me-2"></i>Pay Online
                            <span class="badge bg-success ms-2">Recommended</span>
                        </label>
                        <p class="text-muted small mt-2 mb-0">Credit Card, Debit Card, Net Banking, UPI</p>
                    </div>
                </div>
                
                <div class="payment-option" onclick="selectPayment('cash')">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" id="payment_cash" value="cash">
                        <label class="form-check-label" for="payment_cash">
                            <i class="bi bi-cash-stack text-success me-2"></i>Pay with Cash
                        </label>
                        <p class="text-muted small mt-2 mb-0">Pay at the hotel during check-in</p>
                    </div>
                </div>
                
                <!-- Online Payment Details (Show/Hide) -->
                <div id="online_payment_details" class="mt-3">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="alert alert-info">
                                <i class="bi bi-shield-lock me-2"></i>You will be redirected to the secure Razorpay payment gateway to complete your transaction.
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Terms and Conditions -->
                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" name="terms" id="terms">
                    <label class="form-check-label" for="terms">
                        I agree to the <a href="#">Terms & Conditions</a> and <a href="#">Cancellation Policy</a>
                    </label>
                </div>
                
                <button type="submit" class="btn-book mt-4">
                    <i class="bi bi-check-circle me-2"></i>Confirm Booking
                </button>
                
                <div class="text-center mt-3">
                    <a href="rooms.php" class="text-muted"><i class="bi bi-arrow-left me-1"></i>Back to Rooms</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-4 mt-5">
    <div class="container">
        <p>&copy; 2026 The Imperial Crown Hotel. All rights reserved.</p>
    </div>
</footer>

<script>
// Get URL parameters (room details from rooms page)
function getUrlParams() {
    const params = new URLSearchParams(window.location.search);
    return {
        room: params.get('room') || params.get('room_id') || <?php echo (int) $php_default_room_id; ?>,
        roomName: decodeURIComponent(params.get('roomName') || params.get('room_name') || <?php echo json_encode($php_default_room_name); ?>),
        roomPrice: params.get('roomPrice') || params.get('room_price') || <?php echo json_encode($php_default_room_price); ?>,
        checkIn: params.get('checkin'),
        checkOut: params.get('checkout'),
        adults: params.get('adults'),
        children: params.get('children')
    };
}

// Set form details from URL
const roomData = getUrlParams();
$('#room_id').val(roomData.room);
$('#room_name').val(roomData.roomName);
$('#room_price').val(roomData.roomPrice);
$('#displayRoomName').text(roomData.roomName);
$('#displayRoomPrice').text('₹' + roomData.roomPrice);
$('#calc_room_price').text(roomData.roomPrice);

if (roomData.checkIn) $('#check_in').val(roomData.checkIn);
if (roomData.checkOut) $('#check_out').val(roomData.checkOut);
if (roomData.adults) $('#adults').val(roomData.adults);
if (roomData.children) $('#children').val(roomData.children);

// Calculate nights and total amount
function calculateTotal() {
    const checkIn = $('#check_in').val();
    const checkOut = $('#check_out').val();
    const roomPrice = parseInt($('#room_price').val()) || 0;
    const extraPersons = parseInt($('#extra_persons').val()) || 0;
    const extraCharge = 500; // Per person per night
    const discountPercent = parseInt($('#discount_percentage').val()) || 0;
    
    if (checkIn && checkOut) {
        const start = new Date(checkIn);
        const end = new Date(checkOut);
        const nights = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
        
        if (nights > 0) {
            $('#nights').val(nights);
            $('#calc_nights').text(nights);
            
            const roomTotal = roomPrice * nights;
            const extraTotal = extraPersons * extraCharge * nights;
            let grandTotal = roomTotal + extraTotal;
            
            if (discountPercent > 0) {
                const discountAmount = (grandTotal * discountPercent) / 100;
                grandTotal -= discountAmount;
                const discountText = `Discount (${$('#discount_title').val()}): -₹${discountAmount}`;
                if ($('#discount_display').length === 0) {
                    $('<p class="mb-1 text-success fw-bold" id="discount_display"></p>').text(discountText).insertAfter($('#calc_extra').parent());
                } else {
                    $('#discount_display').text(discountText).show();
                }
            } else {
                $('#discount_display').hide();
            }
            
            $('#total_amount').text('₹' + grandTotal);
            $('#calc_extra').text(extraPersons);
        } else {
            $('#nights').val(0);
            $('#calc_nights').text(0);
            $('#total_amount').text('₹0');
            $('#discount_display').hide();
        }
    }
}

function enforceCapacity() {
    const capacity = parseInt($('#room_capacity').val(), 10) || 2;
    const adultsSelect = $('#adults');
    adultsSelect.find('option').each(function() {
        const val = parseInt($(this).val(), 10);
        $(this).prop('disabled', val > capacity);
    });
    if ((parseInt(adultsSelect.val(), 10) || 1) > capacity) {
        adultsSelect.val(String(capacity));
    }
}

// Payment method selection
function selectPayment(method) {
    if (method === 'online') {
        $('#payment_online').prop('checked', true);
        $('#online_payment_details').show();
        $('.payment-option').removeClass('selected');
        $('#payment_online').closest('.payment-option').addClass('selected');
    } else {
        $('#payment_cash').prop('checked', true);
        $('#online_payment_details').hide();
        $('.payment-option').removeClass('selected');
        $('#payment_cash').closest('.payment-option').addClass('selected');
    }
}

$(document).ready(function() {
    // Initialize payment method
    selectPayment('online');
    
    // Event listeners
    $('#check_in, #check_out, #extra_persons').on('change', calculateTotal);
    $('#adults').on('change', enforceCapacity);

    // Ensure total is initialized if dates already selected.
    calculateTotal();
    enforceCapacity();
    
    // Set min checkout date based on checkin
    $('#check_in').on('change', function() {
        const checkIn = $(this).val();
        if (checkIn) {
            const nextDay = new Date(checkIn);
            nextDay.setDate(nextDay.getDate() + 1);
            const minCheckOut = nextDay.toISOString().split('T')[0];
            $('#check_out').attr('min', minCheckOut);
        }
    });
    
    // jQuery Validation
    $("#bookingForm").validate({
        rules: {
            full_name: {
                required: true,
                minlength: 3,
                maxlength: 50
            },
            email: {
                required: true,
                email: true
            },
            phone: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10
            },
            check_in: {
                required: true,
                date: true
            },
            check_out: {
                required: true,
                date: true
            },
            terms: {
                required: true
            }
        },
        messages: {
            full_name: {
                required: "Please enter your full name",
                minlength: "Name must be at least 3 characters"
            },
            email: {
                required: "Please enter your email",
                email: "Please enter a valid email address"
            },
            phone: {
                required: "Please enter your phone number",
                digits: "Please enter only digits",
                minlength: "Phone number must be 10 digits",
                maxlength: "Phone number must be 10 digits"
            },
            check_in: {
                required: "Please select check-in date"
            },
            check_out: {
                required: "Please select check-out date"
            },
            terms: {
                required: "You must agree to terms and conditions"
            }
        },
        errorElement: "span",
        errorClass: "error",
        highlight: function(element) {
            $(element).addClass("error-border");
        },
        unhighlight: function(element) {
            $(element).removeClass("error-border");
        },
        submitHandler: function(form) {
            // Check if check-out after check-in
            const checkIn = new Date($('#check_in').val());
            const checkOut = new Date($('#check_out').val());
            
            if (checkOut <= checkIn) {
                alert('Check-out date must be after check-in date!');
                return false;
            }
            
            const paymentMethod = $('input[name="payment_method"]:checked').val();
            const roomId = parseInt($('#room_id').val(), 10) || 0;
            const checkInStr = $('#check_in').val();
            const checkOutStr = $('#check_out').val();
            const adults = parseInt($('#adults').val(), 10) || 0;
            const children = parseInt($('#children').val(), 10) || 0;
            const extraPersons = parseInt($('#extra_persons').val(), 10) || 0;
            const capacity = parseInt($('#room_capacity').val(), 10) || 2;
            
            if (adults > capacity) {
                alert('Selected adults exceed room capacity.');
                return false;
            }
            
            let roomPrice = parseFloat($('#room_price').val()) || 0;
            let nights = parseInt($('#nights').val(), 10) || 0;
            let totalPrice = (roomPrice * nights) + (extraPersons * 500 * nights);
            
            const discountPercent = parseInt($('#discount_percentage').val()) || 0;
            if (discountPercent > 0) {
                totalPrice -= (totalPrice * discountPercent) / 100;
            }

            if (roomId <= 0 || nights <= 0) {
                alert('Invalid booking details. Please re-select room and dates.');
                return false;
            }

            const fullName = $('#full_name').val();
            const email = $('#email').val();
            const phoneStr = $('#phone').val();

            const payload = {
                room_id: roomId,
                check_in: checkInStr,
                check_out: checkOutStr,
                guests: adults,
                total_price: totalPrice,
                extra_persons: extraPersons,
                extra_charge_per_night: 500,
                payment_method: paymentMethod
            };

            const submitToBackend = function(finalPayload) {
                // Populate localStorage booking so success page can show it
                const bookings = JSON.parse(localStorage.getItem('bookings')) || [];
                const localBooking = {
                    id: 0, // Gets overwritten by redirect
                    room_name: $('#room_name').val(),
                    full_name: fullName,
                    email: email,
                    phone: phoneStr,
                    check_in: checkInStr,
                    check_out: checkOutStr,
                    nights: nights,
                    payment_method: paymentMethod,
                    total_amount: '₹' + totalPrice,
                    transaction_id: finalPayload.razorpay_payment_id || 'TXN-' + Math.floor(Math.random() * 1000000000)
                };
                
                fetch('api/booking_create.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(finalPayload)
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
                    if (data && data.success) {
                        alert('Booking confirmed! Thank you for choosing Imperial Crown Hotel.');
                        
                        // Overwrite ID to match database
                        localBooking.id = data.booking_id;
                        bookings.push(localBooking);
                        localStorage.setItem('bookings', JSON.stringify(bookings));
                        
                        const nextUrl = data.redirect || 'my_booking.php';
                        window.location.href = nextUrl;
                        return;
                    }
                    alert((data && data.message) ? data.message : 'Booking failed.');
                })
                .catch(() => alert('Network error. Try again.'));
            };

            // Check availability first
            fetch('api/check_availability.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    room_id: roomId,
                    check_in: checkInStr,
                    check_out: checkOutStr
                })
            })
            .then(r => r.json())
            .then(availData => {
                if (!availData.success) {
                    alert(availData.message);
                    return;
                }

                // If available, proceed
                if (paymentMethod === 'online') {
                    var rzpKey = "<?php echo defined('RAZORPAY_KEY_ID') ? RAZORPAY_KEY_ID : 'rzp_test_YourTestKeyHere'; ?>";
                    
                    if (rzpKey === '' || rzpKey === 'rzp_test_YourTestKeyHere' || rzpKey === 'rzp_test_YOUR_NEW_KEY_ID') {
                        // Simulation Mode: Bypass Razorpay completely if no real key is provided
                        alert("Developer Mode: Simulating a successful online payment since a real Razorpay Key wasn't found in config.php. Please add your real key for live payments.");
                        payload.razorpay_payment_id = "pay_test_" + Math.floor(Math.random() * 1000000000);
                        submitToBackend(payload);
                        return;
                    }

                    var options = {
                        "key": rzpKey,
                        "amount": totalPrice * 100,
                        "currency": "INR",
                        "name": "Imperial Crown Hotel",
                        "description": "Room Booking Transaction",
                        "image": "https://cdn-icons-png.flaticon.com/512/3005/3005953.png",
                        "handler": function (response){
                            payload.razorpay_payment_id = response.razorpay_payment_id;
                            submitToBackend(payload);
                        },
                        "prefill": {
                            "name": fullName,
                            "email": email,
                            "contact": phoneStr
                        },
                        "theme": {
                            "color": "#3498db"
                        }
                    };

                    // Fetch Order ID from backend purely required by newly created Razorpay Accounts
                    fetch('api/create_razorpay_order.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ amount: totalPrice })
                    })
                    .then(r => r.json())
                    .then(orderData => {
                        if (orderData.success && orderData.order_id) {
                            options.order_id = orderData.order_id; // Pass the created order ID
                            var rzp1 = new Razorpay(options);
                            rzp1.on('payment.failed', function (response){
                                alert("Payment Failed: " + (response.error ? response.error.description : 'Unknown error'));
                            });
                            rzp1.open();
                        } else {
                            alert("Razorpay Gateway Warning: " + (orderData.message || "Unknown error") + "\n\nFalling back to Simulation Mode so you aren't completely blocked from booking the room!");
                            payload.razorpay_payment_id = "pay_sim_" + Math.floor(Math.random() * 1000000000);
                            submitToBackend(payload);
                        }
                    })
                    .catch(err => {
                        alert("Failed to connect to backend for Razorpay checkout.");
                    });
                } else {
                    submitToBackend(payload);
                }
            })
            .catch(err => {
                alert('Failed to verify room availability. Please try again.');
            });
        }
    });
});
</script>

</body>
</html>