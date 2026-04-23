 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Success</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #001f3f 0%, #006064 50%, #00bfa5 100%);
            overflow: hidden;
        }

        .success-container {
            width: 90%;
            max-width: 400px;
            text-align: center;
            padding: 40px 20px;
            position: relative;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .success-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: white;
            text-transform: uppercase;
            margin-bottom: 2rem;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
            letter-spacing: 2px;
            position: relative;
        }

        .success-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: #00bcd4;
            border-radius: 2px;
        }

        .checkmark-wrapper {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto 2rem;
            animation: scaleIn 0.5s ease-out 0.3s both;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
        }

        .checkmark-circle {
            width: 100%;
            height: 100%;
            background: #00bcd4;
            border-radius: 50%;
            box-shadow: 0 4px 20px rgba(0,188,212,0.4);
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .checkmark-circle::before {
            content: '';
            position: absolute;
            top: -10%;
            left: -10%;
            width: 120%;
            height: 120%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1));
            animation: shine 2s infinite;
        }

        @keyframes shine {
            from {
                transform: translateX(-100%) translateY(-100%);
            }
            to {
                transform: translateX(100%) translateY(100%);
            }
        }

        .checkmark {
            width: 40%;
            height: 60%;
            border-right: 6px solid white;
            border-bottom: 6px solid white;
            transform: rotate(45deg) translate(-5%, -10%);
        }

        .room-button {
            display: inline-block;
            padding: 15px 30px;
            color: white;
            text-decoration: none;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 1px;
            background: black;
            border: 2px solid black;
            border-radius: 30px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .room-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background: rgba(255,255,255,0.1);
            transition: width 0.3s ease;
            z-index: -1;
        }

        .room-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .room-button:hover::before {
            width: 100%;
        }

        .room-button:active {
            transform: translateY(0);
        }

        /* Floating particles animation */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        .particle {
            position: absolute;
            width: 6px;
            height: 6px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            animation: float 4s infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) translateX(0);
            }
            50% {
                transform: translateY(-20px) translateX(10px);
            }
        }

        /* Add multiple particles with different positions and animations */
        .particle:nth-child(1) { top: 20%; left: 20%; animation-delay: 0s; }
        .particle:nth-child(2) { top: 60%; left: 80%; animation-delay: 0.5s; }
        .particle:nth-child(3) { top: 40%; left: 40%; animation-delay: 1s; }
        .particle:nth-child(4) { top: 80%; left: 60%; animation-delay: 1.5s; }
        .particle:nth-child(5) { top: 30%; left: 70%; animation-delay: 2s; }

        @media (max-width: 480px) {
            .success-title {
                font-size: 1.8rem;
            }

            .checkmark-wrapper {
                width: 100px;
                height: 100px;
            }

            .room-button {
                padding: 12px 24px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>
    
    <div class="success-container">
        <h1 class="success-title">Booked Successfully</h1>
        <div class="checkmark-wrapper">
            <div class="checkmark-circle">
                <div class="checkmark"></div>
            </div>
        </div>
        <a href="rooms.php" class="room-button">Move to Room Page</a>
    </div>
</body>
</html>

