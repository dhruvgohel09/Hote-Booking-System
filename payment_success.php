<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success - The Imperial Crown Hotel</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('images/hotel_room_bg.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 40px 0;
        }
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .receipt-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        .receipt-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .receipt-header h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .receipt-body {
            padding: 40px;
            background: white;
        }
        .success-icon {
            width: 80px;
            height: 80px;
            background: white;
            color: #28a745;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            margin: 0 auto 20px;
        }
        .payment-badge {
            background: #28a745;
            color: white;
            padding: 8px 20px;
            border-radius: 25px;
            display: inline-block;
            font-weight: 600;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }
        .detail-label {
            font-weight: 600;
            color: #6c757d;
        }
        .detail-value {
            font-weight: 600;
            color: #2c3e50;
        }
        .total-row {
            font-size: 1.4rem;
            font-weight: 700;
            color: #28a745;
            background: #e8f5e9;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
        }
        .btn-home {
            background: #2c3e50;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }
        .btn-home:hover {
            background: #1a252f;
            transform: translateY(-2px);
            color: white;
        }
        .btn-print {
            background: #6c757d;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-print:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

<div class="container receipt-container">
    <div class="receipt-card">
        <div class="receipt-header">
            <div class="success-icon mx-auto">
                <i id="headerIcon" class="bi bi-credit-card"></i>
            </div>
            <h2 id="pageTitle">Payment Successful!</h2>
            <p class="mb-0" id="pageSubtitle">Your transaction has been completed</p>
        </div>
        
        <div class="receipt-body">
            <div class="text-center mb-4">
                <span class="payment-badge" id="paymentBadge">
                    <i class="bi bi-wifi me-2"></i>Online Payment
                </span>
            </div>
            
            <div class="receipt-details" id="receiptDetails">
                <!-- Details will be filled by JavaScript -->
            </div>
            
            <div class="text-center mt-4">
                <p class="text-muted">Transaction ID: <strong id="transactionId"></strong></p>
                <button class="btn-print me-2" onclick="window.print()">
                    <i class="bi bi-printer me-2"></i>Print Receipt
                </button>
                <a href="index.php" class="btn-home">
                    <i class="bi bi-house me-2"></i>Back to Home
                </a>
            </div>

            <!-- Feedback Section -->
            <hr class="mt-5 mb-4">
            <div class="feedback-section text-center" id="feedbackContainer">
                <h5 class="fw-bold">How was your booking experience?</h5>
                <p class="text-muted small">Please rate your experience with us</p>
                <div class="star-rating fs-3 text-warning mb-3" id="starRatingContainer" style="cursor: pointer;">
                    <i class="bi bi-star" data-rating="1"></i>
                    <i class="bi bi-star" data-rating="2"></i>
                    <i class="bi bi-star" data-rating="3"></i>
                    <i class="bi bi-star" data-rating="4"></i>
                    <i class="bi bi-star" data-rating="5"></i>
                </div>
                <textarea id="feedbackComment" class="form-control mb-3" rows="2" placeholder="Tell us more about your experience (optional)"></textarea>
                <button id="submitFeedbackBtn" class="btn btn-primary w-100" style="font-weight: 600; padding: 10px;">Submit Feedback</button>
                <div id="feedbackMessage" style="display:none;" class="alert alert-success mt-3 mb-0"></div>
            </div>
        </div>
    </div>
</div>

<script>
// Generate random transaction ID
function generateTransactionId() {
    return 'TXN' + Math.floor(Math.random() * 1000000000).toString().padStart(9, '0');
}

// Get booking ID from URL
const urlParams = new URLSearchParams(window.location.search);
const bookingId = urlParams.get('booking_id');

const bookings = JSON.parse(localStorage.getItem('bookings')) || [];
const booking = bookings.find(b => b.id == bookingId) || bookings[bookings.length - 1];

if (booking) {
    if (booking.payment_method === 'cash') {
        document.getElementById('pageTitle').textContent = 'Booking Confirmed!';
        document.getElementById('pageSubtitle').textContent = 'Please pay the final amount at the hotel counter upon check-in';
        document.getElementById('headerIcon').className = 'bi bi-check-circle';
        document.getElementById('paymentBadge').innerHTML = '<i class="bi bi-cash-stack me-2"></i>Pay at Hotel';
        document.getElementById('paymentBadge').className = 'payment-badge bg-primary';
    }

    const transactionId = booking.payment_method === 'cash' ? 'PENDING-CASH' : (booking.transaction_id || generateTransactionId());
    document.getElementById('transactionId').textContent = transactionId;
    
    const receiptHtml = `
        <div class="detail-row">
            <span class="detail-label">Booking ID:</span>
            <span class="detail-value">#${booking.id}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Transaction ID:</span>
            <span class="detail-value">${transactionId}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Room:</span>
            <span class="detail-value">${booking.room_name}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Guest Name:</span>
            <span class="detail-value">${booking.full_name}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Email:</span>
            <span class="detail-value">${booking.email}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Phone:</span>
            <span class="detail-value">${booking.phone}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Check-in:</span>
            <span class="detail-value">${booking.check_in}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Check-out:</span>
            <span class="detail-value">${booking.check_out}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Nights:</span>
            <span class="detail-value">${booking.nights}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Payment Method:</span>
            <span class="detail-value text-capitalize">${booking.payment_method}</span>
        </div>
        <div class="total-row d-flex justify-content-between">
            <span>Amount Paid:</span>
            <span>${booking.total_amount}</span>
        </div>
        <div class="text-center mt-3 text-muted small">
            Payment Date: ${new Date().toLocaleString()}
        </div>
    `;
    
    document.getElementById('receiptDetails').innerHTML = receiptHtml;
}

// Feedback Logic
let currentRating = 0;
const stars = document.querySelectorAll('#starRatingContainer i');

stars.forEach(star => {
    star.addEventListener('mouseover', function() {
        const rating = this.getAttribute('data-rating');
        highlightStars(rating);
    });

    star.addEventListener('mouseout', function() {
        highlightStars(currentRating);
    });

    star.addEventListener('click', function() {
        currentRating = this.getAttribute('data-rating');
        highlightStars(currentRating);
    });
});

function highlightStars(rating) {
    stars.forEach(star => {
        if (star.getAttribute('data-rating') <= rating) {
            star.classList.remove('bi-star');
            star.classList.add('bi-star-fill');
        } else {
            star.classList.remove('bi-star-fill');
            star.classList.add('bi-star');
        }
    });
}

document.getElementById('submitFeedbackBtn').addEventListener('click', function() {
    if (currentRating === 0) {
        alert('Please select a star rating first.');
        return;
    }

    const comments = document.getElementById('feedbackComment').value;
    const btn = this;
    
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Submitting...';

    fetch('api/feedback_submit.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            booking_id: bookingId,
            rating: currentRating,
            comments: comments
        })
    })
    .then(r => r.json())
    .then(data => {
        const msgDiv = document.getElementById('feedbackMessage');
        msgDiv.style.display = 'block';
        if (data.success) {
            msgDiv.className = 'alert alert-success mt-3 mb-0';
            msgDiv.innerHTML = '<i class="bi bi-check-circle me-1"></i> ' + data.message;
            // Hide input elements
            document.getElementById('starRatingContainer').style.display = 'none';
            document.getElementById('feedbackComment').style.display = 'none';
            btn.style.display = 'none';
        } else {
            msgDiv.className = 'alert alert-danger mt-3 mb-0';
            msgDiv.innerHTML = '<i class="bi bi-exclamation-triangle me-1"></i> ' + data.message;
            btn.disabled = false;
            btn.innerHTML = 'Submit Feedback';
        }
    })
    .catch(err => {
        alert('Network error while saving feedback.');
        btn.disabled = false;
        btn.innerHTML = 'Submit Feedback';
    });
});
</script>

</body>
</html>