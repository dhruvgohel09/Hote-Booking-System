<?php require_once __DIR__ . '/includes/init.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Imperial Crown Hotel - Facilities</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

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

        .facility-card {
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
            border-radius: 15px;
            overflow: hidden;
            height: 100%;
        }

        .facility-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15) !important;
        }

        .facility-icon {
            width: 100px;
            height: 100px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            border-radius: 50%;
            color: white;
            font-size: 3rem;
        }

        .badge {
            font-weight: 400;
            padding: 8px 16px;
            border-radius: 20px;
        }

        .card-title {
            color: #2c3e50;
            font-weight: 600;
        }

        .card-text {
            font-size: 0.95rem;
            line-height: 1.6;
            color: #666;
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
        <h2 class="fw-bold h-font text-center">OUR FACILITIES</h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3 text-muted">Experience world-class comfort and premium hospitality at The
            Imperial Crown Hotel. We provide modern amenities and luxurious facilities to ensure a relaxing and
            memorable stay for every guest.</p>
    </div>

    <!-- Facilities Container -->
    <div class="container my-5">
        <div class="row g-4" id="facilitiesContainer">
            <!-- Facilities will be loaded here via JavaScript -->
        </div>
    </div>
    </div>

    <?php require('footer.php'); ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Facilities data
        const facilitiesData = [
            {
                id: 16,
                name: "Geyser",
                icon: "bi-droplet",
                description: "Lorem ipsum dolor, sit amet consectetur adipiscing elit. Aut id inventore consequuntur voluptas error, fugit fuga aspernatur at est ab."
            },
            {
                id: 17,
                name: "Television",
                icon: "bi-tv",
                description: "Lorem ipsum dolor, sit amet consectetur adipiscing elit. Aut id inventore consequuntur voluptas error, fugit fuga aspernatur at est ab."
            },
            {
                id: 18,
                name: "Wifi",
                icon: "bi-wifi",
                description: "Lorem ipsum dolor, sit amet consectetur adipiscing elit. Aut id inventore consequuntur voluptas error, fugit fuga aspernatur at est ab."
            },
            {
                id: 19,
                name: "Air Conditioning",
                icon: "bi-snow",
                description: "Lorem ipsum dolor, sit amet consectetur adipiscing elit. Aut id inventore consequuntur voluptas error, fugit fuga aspernatur at est ab."
            },
            {
                id: 20,
                name: "Room Heater",
                icon: "bi-thermometer-sun",
                description: "Lorem ipsum dolor, sit amet consectetur adipiscing elit. Aut id inventore consequuntur voluptas error, fugit fuga aspernatur at est ab."
            },
            {
                id: 22,
                name: "Spa",
                icon: "bi-flower1",
                description: "Lorem ipsum dolor, sit amet consectetur adipiscing elit. Aut id inventore consequuntur voluptas error, fugit fuga aspernatur at est ab."
            }
        ];

        // Display facilities
        $(document).ready(function () {
            let html = '';

            facilitiesData.forEach(facility => {
                html += `
            <div class="col-lg-4 col-md-6">
                <div class="card facility-card shadow">
                    <div class="card-body text-center p-4">
                        <div class="facility-icon">
                            <i class="bi ${facility.icon}"></i>
                        </div>
                        <h4 class="card-title mb-3">${facility.name}</h4>
                        <p class="card-text">${facility.description}</p>
                        <span class="badge bg-primary mt-3">
                            <i class="bi bi-check-circle me-1"></i> Available
                        </span>
                    </div>
                </div>
            </div>
        `;
            });

            $('#facilitiesContainer').html(html);
        });
    </script>

</body>

</html>