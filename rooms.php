<?php
require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/includes/room_helpers.php';

$filter_checkin = $_GET['checkin'] ?? '';
$filter_checkout = $_GET['checkout'] ?? '';

$rooms_js = hotel_rooms_for_js($mysqli, $filter_checkin, $filter_checkout);
$php_user_logged = !empty($_SESSION['user_id']);
$php_is_admin = (($_SESSION['user_role'] ?? '') === 'admin');
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Imperial Crown Hotel - ROOMS</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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

        .badge {
            font-weight: 400;
            padding: 6px 12px;
        }

        .room-card {
            transition: transform 0.3s;
        }

        .room-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2) !important;
        }

        /* Filter Section Styles */
        .filter-section {
            position: sticky;
            top: 20px;
        }

        @media (max-width: 768px) {
            .filter-section {
                position: static;
            }
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

        /* Modal Styles */
        .modal-content {
            border-radius: 20px;
        }

        .modal-header {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            border-radius: 20px 20px 0 0;
        }

        .booking-summary {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin: 15px 0;
        }

        .btn-confirm {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            border: none;
            padding: 12px;
            font-weight: bold;
            width: 100%;
            border-radius: 10px;
        }

        .btn-confirm:hover {
            transform: translateY(-2px);
        }
    </style>
</head>

<body class="bg-light">

    <!-- Header -->
    <?php require('navbar.php'); ?>

    <!-- Spacer for fixed navbar -->
    <div style="height: 120px;"></div>

    <!-- Page Title -->
    <div class="px-4 mb-5">
        <h2 class="fw-bold h-font text-center">OUR ROOMS</h2>
        <div class="h-line bg-dark"></div>
    </div>

    <!-- Main Content with Filter -->
    <div class="container-fluid">
        <div class="row">
            <!-- FILTER SECTION - LEFT SIDE -->
            <div class="col-lg-3 col-md-12 mb-4 ps-4">
                <div class="filter-section">
                    <nav class="navbar navbar-expand-lg navbar-light bg-white rounded shadow">
                        <div class="container-fluid flex-lg-column align-items-stretch">
                            <h4 class="mt-2"><i class="bi bi-funnel"></i> FILTERS</h4>
                            <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse"
                                data-bs-target="#filterDropdown">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse flex-column align-items-stretch mt-2"
                                id="filterDropdown">

                                <!-- Check Availability Filter -->
                                <div class="border bg-light p-3 rounded mb-3">
                                    <h5 class="mb-3" style="font-size:18px;"><i class="bi bi-calendar-check"></i> CHECK
                                        AVAILABILITY</h5>
                                    <label class="form-label">Check-in</label>
                                    <input type="date" class="form-control shadow-none mb-3" id="checkin">
                                    <label class="form-label">Check-out</label>
                                    <input type="date" class="form-control shadow-none mb-3" id="checkout">
                                    <label class="form-label">Adults</label>
                                    <select class="form-select shadow-none mb-3" id="adults">
                                        <option value="1">1 Adult</option>
                                        <option value="2" selected>2 Adults</option>
                                        <option value="3">3 Adults</option>
                                        <option value="4">4 Adults</option>
                                    </select>
                                    <label class="form-label">Children</label>
                                    <select class="form-select shadow-none mb-3" id="children">
                                        <option value="0">0 Children</option>
                                        <option value="1">1 Child</option>
                                        <option value="2">2 Children</option>
                                        <option value="3">3 Children</option>
                                    </select>
                                    <button class="btn w-100 text-white custom-bg shadow-none"
                                        onclick="checkAvailability()">
                                        <i class="bi bi-search"></i> Check Availability
                                    </button>
                                </div>

                                <!-- Price Range Filter -->
                                <div class="border bg-light p-3 rounded mb-3">
                                    <h5 class="mb-3" style="font-size:18px;"><i class="bi bi-currency-rupee"></i> PRICE
                                        RANGE</h5>
                                    <div class="mb-3">
                                        <label class="form-label">Min Price (₹)</label>
                                        <input type="number" class="form-control shadow-none" id="minPrice" value="0"
                                            min="0">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Max Price (₹)</label>
                                        <input type="number" class="form-control shadow-none" id="maxPrice" value="5000"
                                            min="100">
                                    </div>
                                    <button class="btn w-100 btn-outline-dark shadow-none" onclick="filterByPrice()">
                                        Apply Price Filter
                                    </button>
                                </div>

                                <!-- Room Type Filter -->
                                <div class="border bg-light p-3 rounded mb-3">
                                    <h5 class="mb-3" style="font-size:18px;"><i class="bi bi-door-open"></i> ROOM TYPE
                                    </h5>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="typeSimple" checked>
                                        <label class="form-check-label" for="typeSimple">Simple Rooms</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="typeDelux" checked>
                                        <label class="form-check-label" for="typeDelux">Delux Rooms</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="typeLuxury" checked>
                                        <label class="form-check-label" for="typeLuxury">Luxury Rooms</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="typeExecutive" checked>
                                        <label class="form-check-label" for="typeExecutive">Executive Rooms</label>
                                    </div>
                                </div>

                                <!-- Reset Button -->
                                <button class="btn btn-outline-secondary w-100" onclick="resetFilters()">
                                    <i class="bi bi-arrow-repeat"></i> Reset All Filters
                                </button>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>

            <!-- ROOMS SECTION - RIGHT SIDE -->
            <div class="col-lg-9 col-md-12 px-4">
                <div class="row" id="roomsContainer">
                    <!-- Rooms will be loaded here via JavaScript -->
                </div>
            </div>
        </div>
    </div>



    <?php require('footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const roomsData = <?php echo json_encode($rooms_js, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
        const phpUserLoggedIn = <?php echo $php_user_logged ? 'true' : 'false'; ?>;
        const phpIsAdmin = <?php echo $php_is_admin ? 'true' : 'false'; ?>;

        // Store filtered rooms
        let filteredRooms = [...roomsData];
        let currentRoom = null;

        // Display rooms function
        function displayRooms(rooms) {
            let html = '';

            rooms.forEach(room => {
                let featuresHtml = '';
                room.features.forEach(f => {
                    featuresHtml += `<span class="badge rounded-pill bg-light text-dark me-1 mb-1">${f}</span>`;
                });

                let facilitiesHtml = '';
                room.facilities.forEach(f => {
                    facilitiesHtml += `<span class="badge rounded-pill bg-light text-dark me-1 mb-1">${f}</span>`;
                });

                html += `
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card room-card h-100">
                    <img src="${room.image}" class="card-img-top" style="height: 220px; object-fit: cover;" 
                         onerror="this.src='https://images.pexels.com/photos/279746/pexels-photo-279746.jpeg?auto=compress&cs=tinysrgb&w=600'">
                    <div class="card-body">
                        <h5 class="card-title">${room.name}</h5>
                        <h6 class="text-primary mb-3">₹${room.price} / night</h6>
                        
                        <div class="mb-2">
                            <strong>Features:</strong><br>
                            ${featuresHtml}
                        </div>
                        
                        <div class="mb-2">
                            <strong>Facilities:</strong><br>
                            ${facilitiesHtml}
                        </div>
                        
                        <div class="mb-3">
                            <strong>Guests:</strong><br>
                            <span class="badge bg-secondary">${room.adult} Adults</span>
                            <span class="badge bg-secondary">${room.children} Children</span>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-sm custom-bg text-white" onclick="openBookingModal(${room.id})" ${room.status !== 'available' ? 'disabled title="Not available"' : ''}>Book Now</button>
                            <a href="room_details.php?id=${room.id}" class="btn btn-sm btn-outline-dark">Details</a>
                        </div>
                    </div>
                </div>
            </div>
        `;
            });

            if (rooms.length === 0) {
                html = '<div class="col-12 text-center py-5"><h4>No rooms match your filters</h4></div>';
            }

            $('#roomsContainer').html(html);
        }

        // Open Booking Page
        function openBookingModal(roomId) {
            const room = roomsData.find(r => r.id === roomId);
            if (!room) return;
            if (room.status !== 'available') {
                alert('This room is not available for booking.');
                return;
            }
            if (!phpUserLoggedIn) {
                alert('Please login to book this room');
                window.location.href = 'login.php';
                return;
            }
            if (phpIsAdmin) {
                alert('Please use a guest account to make bookings.');
                return;
            }

            // Get current filter params if available
            const checkin = document.getElementById('checkin').value;
            const checkout = document.getElementById('checkout').value;
            const adults = document.getElementById('adults').value;
            const children = document.getElementById('children').value;

            let url = `booking.php?room=${room.id}&roomName=${encodeURIComponent(room.name)}&roomPrice=${room.price}`;
            if (checkin && checkout) {
                url += `&checkin=${encodeURIComponent(checkin)}&checkout=${encodeURIComponent(checkout)}`;
            }
            if (adults) url += `&adults=${encodeURIComponent(adults)}`;
            if (children) url += `&children=${encodeURIComponent(children)}`;

            window.location.href = url;
        }



        // Filter functions
        function filterByPrice() {
            const minPrice = parseInt(document.getElementById('minPrice').value) || 0;
            const maxPrice = parseInt(document.getElementById('maxPrice').value) || 5000;

            filteredRooms = roomsData.filter(room => room.price >= minPrice && room.price <= maxPrice);
            filterByType();
        }

        function filterByType() {
            const showSimple = document.getElementById('typeSimple')?.checked ?? true;
            const showDelux = document.getElementById('typeDelux')?.checked ?? true;
            const showLuxury = document.getElementById('typeLuxury')?.checked ?? true;
            const showExecutive = document.getElementById('typeExecutive')?.checked ?? true;

            let typeFiltered = filteredRooms.filter(room => {
                if (room.type === 'simple' && !showSimple) return false;
                if (room.type === 'delux' && !showDelux) return false;
                if (room.type === 'luxury' && !showLuxury) return false;
                if (room.type === 'executive' && !showExecutive) return false;
                return true;
            });

            displayRooms(typeFiltered);
        }

        function resetFilters() {
            document.getElementById('minPrice').value = 0;
            document.getElementById('maxPrice').value = 5000;
            document.getElementById('typeSimple').checked = true;
            document.getElementById('typeDelux').checked = true;
            document.getElementById('typeLuxury').checked = true;
            document.getElementById('typeExecutive').checked = true;
            document.getElementById('checkin').value = '';
            document.getElementById('checkout').value = '';

            filteredRooms = [...roomsData];
            displayRooms(roomsData);
        }

        function checkAvailability() {
            const checkin = document.getElementById('checkin').value;
            const checkout = document.getElementById('checkout').value;
            const adults = document.getElementById('adults').value;
            const children = document.getElementById('children').value;

            if (!checkin || !checkout) {
                alert('Please select check-in and check-out dates');
                return;
            }

            if (new Date(checkin) >= new Date(checkout)) {
                alert('Check-out date must be after check-in date');
                return;
            }

            window.location.href = `rooms.php?checkin=${encodeURIComponent(checkin)}&checkout=${encodeURIComponent(checkout)}&adult=${encodeURIComponent(adults)}&children=${encodeURIComponent(children)}`;
        }



        $(document).ready(function () {
            displayRooms(roomsData);

            $('#minPrice, #maxPrice').on('change', filterByPrice);
            $('#typeSimple, #typeDelux, #typeLuxury, #typeExecutive').on('change', function () {
                filteredRooms = [...roomsData];
                filterByPrice();
            });

            // Set min checkout date
            $('#modal_check_in').on('change', function () {
                const checkIn = $(this).val();
                if (checkIn) {
                    const nextDay = new Date(checkIn);
                    nextDay.setDate(nextDay.getDate() + 1);
                    $('#modal_check_out').attr('min', nextDay.toISOString().split('T')[0]);
                }
            });

            // Parse URL parameters systematically
            const urlParams = new URLSearchParams(window.location.search);
            const checkin = urlParams.get('checkin');
            const checkout = urlParams.get('checkout');
            const adult = urlParams.get('adult');
            const children = urlParams.get('children');
            const bookRoomId = urlParams.get('book');

            if (checkin) $('#checkin').val(checkin);
            if (checkout) $('#checkout').val(checkout);
            if (adult) $('#adults').val(adult);
            if (children) $('#children').val(children);

            if ((checkin && checkout) || adult || children) {
                // Here we could filter strictly based on adult / children matching, but the static JSON handles it by filterByPrice currently.
                // Let's filter rooms dynamically by capacity if adult/children params are set.
                const reqAdult = parseInt(adult, 10) || 1;
                const reqChildren = parseInt(children, 10) || 0;

                filteredRooms = roomsData.filter(room => {
                    const roomAdult = parseInt(room.adult, 10) || 0;
                    const roomChildren = parseInt(room.children, 10) || 0;
                    return roomAdult >= reqAdult && roomChildren >= reqChildren;
                });
                filterByType(); // Also respects price & type

                // Remove empty URL params without refreshing to maintain clean URL
                window.history.replaceState({}, document.title, window.location.pathname);
            }

            if (bookRoomId) {
                // Open the booking modal systematically 
                setTimeout(() => {
                    openBookingModal(parseInt(bookRoomId, 10));
                }, 500); // Wait for potential modals/UI to settle
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        });
    </script>

</body>

</html>