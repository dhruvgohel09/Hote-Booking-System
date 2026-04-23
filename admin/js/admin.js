// admin/js/admin.js - Common Admin Functions

// ============================================
// AUTHENTICATION FUNCTIONS
// ============================================

// Check if admin is logged in
function checkAdminAuth() {
    const isLoggedIn = localStorage.getItem('isLoggedIn');
    const userType = localStorage.getItem('userType');
    
    console.log('Admin Auth Check:', { isLoggedIn, userType });
    
    if (!isLoggedIn || isLoggedIn !== 'true' || userType !== 'admin') {
        alert('⚠️ Please login as admin to access this page');
        window.location.href = '../login.php';
        return false;
    }
    
    // Update admin name in sidebar
    const adminName = localStorage.getItem('userName') || 'Admin';
    const adminNameElements = document.querySelectorAll('#adminName, .admin-name');
    adminNameElements.forEach(el => {
        if (el) el.textContent = adminName;
    });
    
    return true;
}

// Logout function
function logout() {
    if (confirm('Are you sure you want to logout?')) {
        localStorage.removeItem('isLoggedIn');
        localStorage.removeItem('userType');
        localStorage.removeItem('userEmail');
        localStorage.removeItem('userName');
        alert('✅ Logged out successfully!');
        window.location.href = '../logout.php';
    }
}

// ============================================
// UI FUNCTIONS
// ============================================

// Format date
function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('en-US', options);
}

// Show loading spinner
function showLoading(show = true) {
    let spinner = document.getElementById('loadingSpinner');
    if (!spinner) {
        spinner = document.createElement('div');
        spinner.id = 'loadingSpinner';
        spinner.innerHTML = `
            <div style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `;
        document.body.appendChild(spinner);
    }
    spinner.style.display = show ? 'block' : 'none';
}

// Show notification
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
    notification.style.zIndex = '9999';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// ============================================
// ROOMS MANAGEMENT FUNCTIONS
// ============================================

// Default rooms data
const defaultRooms = [
    {
        id: 11,
        name: "Simple Room : 12",
        type: "simple",
        price: 300,
        adult: 2,
        children: 2,
        features: ["bedroom"],
        facilities: ["Wifi", "Television"],
        image: "https://images.pexels.com/photos/271624/pexels-photo-271624.jpeg?auto=compress&cs=tinysrgb&w=600",
        status: "available"
    },
    {
        id: 12,
        name: "Delux Room : 104",
        type: "delux",
        price: 400,
        adult: 2,
        children: 2,
        features: ["bedroom", "balcony"],
        facilities: ["Geyser", "Television", "Wifi"],
        image: "https://images.pexels.com/photos/164595/pexels-photo-164595.jpeg?auto=compress&cs=tinysrgb&w=600",
        status: "booked"
    },
    {
        id: 13,
        name: "Luxury Room: 215",
        type: "luxury",
        price: 700,
        adult: 2,
        children: 2,
        features: ["bedroom", "balcony", "kitchen"],
        facilities: ["Geyser", "Television", "Wifi", "AC"],
        image: "https://images.pexels.com/photos/261102/pexels-photo-261102.jpeg?auto=compress&cs=tinysrgb&w=600",
        status: "available"
    },
    {
        id: 14,
        name: "Simple Large Room: 15",
        type: "simple",
        price: 400,
        adult: 2,
        children: 2,
        features: ["bedroom"],
        facilities: ["Wifi"],
        image: "https://images.pexels.com/photos/279746/pexels-photo-279746.jpeg?auto=compress&cs=tinysrgb&w=600",
        status: "available"
    },
    {
        id: 16,
        name: "Delux Large Room : 120",
        type: "delux",
        price: 800,
        adult: 2,
        children: 2,
        features: ["bedroom", "balcony"],
        facilities: ["Geyser", "Television", "Wifi", "AC"],
        image: "https://images.pexels.com/photos/2507033/pexels-photo-2507033.jpeg?auto=compress&cs=tinysrgb&w=600",
        status: "maintenance"
    },
    {
        id: 17,
        name: "Large Luxury Room : 225",
        type: "luxury",
        price: 1200,
        adult: 2,
        children: 2,
        features: ["bedroom", "balcony", "kitchen"],
        facilities: ["Geyser", "Television", "Wifi", "AC", "Room Heater"],
        image: "https://images.pexels.com/photos/2356045/pexels-photo-2356045.jpeg?auto=compress&cs=tinysrgb&w=600",
        status: "available"
    },
    {
        id: 18,
        name: "Super Luxury Room : 305",
        type: "luxury",
        price: 1800,
        adult: 2,
        children: 2,
        features: ["bedroom", "balcony", "kitchen"],
        facilities: ["Geyser", "Television", "Wifi", "AC", "Room Heater", "Spa"],
        image: "https://images.pexels.com/photos/338504/pexels-photo-338504.jpeg?auto=compress&cs=tinysrgb&w=600",
        status: "booked"
    },
    {
        id: 20,
        name: "Executive Room : 502",
        type: "executive",
        price: 2500,
        adult: 2,
        children: 2,
        features: ["bedroom", "balcony", "kitchen"],
        facilities: ["Geyser", "Television", "Wifi", "AC", "Room Heater", "Spa"],
        image: "https://images.pexels.com/photos/1134176/pexels-photo-1134176.jpeg?auto=compress&cs=tinysrgb&w=600",
        status: "available"
    }
];

// Get rooms from localStorage or use default
function getRooms() {
    const saved = localStorage.getItem('adminRooms');
    return saved ? JSON.parse(saved) : defaultRooms;
}

// Save rooms to localStorage
function saveRooms(rooms) {
    localStorage.setItem('adminRooms', JSON.stringify(rooms));
}

// Get status badge class
function getStatusClass(status) {
    switch(status) {
        case 'available': return 'badge-available';
        case 'booked': return 'badge-booked';
        case 'maintenance': return 'badge-maintenance';
        default: return 'badge-available';
    }
}

// Get status text
function getStatusText(status) {
    return status.charAt(0).toUpperCase() + status.slice(1);
}

// ============================================
// BOOKINGS MANAGEMENT FUNCTIONS
// ============================================

// Default bookings data
const defaultBookings = [
    {
        id: 'B001',
        customer: 'Rahul Sharma',
        email: 'rahul@email.com',
        phone: '9876543210',
        room: 'Delux Room 104',
        roomId: 12,
        checkIn: '2026-03-15',
        checkOut: '2026-03-18',
        adults: 2,
        children: 1,
        total: 1200,
        status: 'confirmed'
    },
    {
        id: 'B002',
        customer: 'Priya Patel',
        email: 'priya@email.com',
        phone: '9876543211',
        room: 'Luxury Room 215',
        roomId: 13,
        checkIn: '2026-03-16',
        checkOut: '2026-03-20',
        adults: 2,
        children: 2,
        total: 2800,
        status: 'pending'
    },
    {
        id: 'B003',
        customer: 'Amit Kumar',
        email: 'amit@email.com',
        phone: '9876543212',
        room: 'Simple Room 12',
        roomId: 11,
        checkIn: '2026-03-14',
        checkOut: '2026-03-16',
        adults: 2,
        children: 0,
        total: 600,
        status: 'confirmed'
    }
];

// Get bookings from localStorage or use default
function getBookings() {
    const saved = localStorage.getItem('adminBookings');
    return saved ? JSON.parse(saved) : defaultBookings;
}

// Save bookings to localStorage
function saveBookings(bookings) {
    localStorage.setItem('adminBookings', JSON.stringify(bookings));
}

// Get booking status class
function getBookingStatusClass(status) {
    switch(status) {
        case 'confirmed': return 'badge-confirmed';
        case 'pending': return 'badge-pending';
        case 'cancelled': return 'badge-cancelled';
        default: return 'badge-pending';
    }
}

// ============================================
// USERS MANAGEMENT FUNCTIONS
// ============================================

// Default users data
const defaultUsers = [
    {
        id: 1,
        name: 'Rahul Sharma',
        email: 'rahul.sharma@email.com',
        phone: '9876543210',
        role: 'user',
        status: 'active',
        joined: '2026-01-15',
        lastActive: '2026-03-02',
        bookings: 5
    },
    {
        id: 2,
        name: 'Priya Patel',
        email: 'priya.patel@email.com',
        phone: '9876543211',
        role: 'user',
        status: 'active',
        joined: '2026-01-20',
        lastActive: '2026-03-02',
        bookings: 3
    },
    {
        id: 3,
        name: 'Amit Kumar',
        email: 'amit.kumar@email.com',
        phone: '9876543212',
        role: 'admin',
        status: 'active',
        joined: '2025-12-10',
        lastActive: '2026-03-02',
        bookings: 0
    }
];

// Get users from localStorage or use default
function getUsers() {
    const saved = localStorage.getItem('adminUsers');
    return saved ? JSON.parse(saved) : defaultUsers;
}

// Save users to localStorage
function saveUsers(users) {
    localStorage.setItem('adminUsers', JSON.stringify(users));
}

// Get user initials
function getUserInitials(name) {
    return name.split(' ').map(n => n[0]).join('').toUpperCase();
}

// ============================================
// FACILITIES MANAGEMENT FUNCTIONS
// ============================================

// Default facilities data
const defaultFacilities = [
    {
        id: 1,
        name: 'Swimming Pool',
        category: 'outdoor',
        description: 'Olympic size swimming pool',
        icon: 'https://cdn-icons-png.flaticon.com/512/3105/3105838.png',
        status: 'available',
        hours: '6AM - 10PM',
        capacity: 50,
        location: 'Ground Floor',
        usage: 156,
        rating: 4.8
    },
    {
        id: 2,
        name: 'Gym & Fitness Center',
        category: 'indoor',
        description: 'Modern gym equipment',
        icon: 'https://cdn-icons-png.flaticon.com/512/2966/2966327.png',
        status: 'available',
        hours: '24/7',
        capacity: 30,
        location: 'First Floor',
        usage: 234,
        rating: 4.9
    },
    {
        id: 3,
        name: 'Spa & Wellness Center',
        category: 'wellness',
        description: 'Luxury spa treatments',
        icon: 'https://cdn-icons-png.flaticon.com/512/2917/2917996.png',
        status: 'available',
        hours: '9AM - 9PM',
        capacity: 20,
        location: 'Second Floor',
        usage: 89,
        rating: 4.9
    }
];

// Get facilities from localStorage or use default
function getFacilities() {
    const saved = localStorage.getItem('adminFacilities');
    return saved ? JSON.parse(saved) : defaultFacilities;
}

// Save facilities to localStorage
function saveFacilities(facilities) {
    localStorage.setItem('adminFacilities', JSON.stringify(facilities));
}

// ============================================
// SETTINGS FUNCTIONS
// ============================================

// Save settings
function saveSettings() {
    showNotification('Settings saved successfully!', 'success');
}

// Reset settings
function resetSettings() {
    if (confirm('Are you sure you want to reset all settings?')) {
        showNotification('Settings reset to default!', 'warning');
    }
}

// Create backup
function createBackup() {
    showNotification('Backup created successfully!', 'success');
}

// ============================================
// EXPORT FUNCTIONS
// ============================================

// Export data as CSV
function exportToCSV(data, filename) {
    if (!data || data.length === 0) {
        showNotification('No data to export!', 'warning');
        return;
    }
    
    const headers = Object.keys(data[0]).join(',');
    const rows = data.map(item => Object.values(item).join(',')).join('\n');
    const csv = headers + '\n' + rows;
    
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename + '.csv';
    a.click();
    
    showNotification('Data exported successfully!', 'success');
}

// ============================================
// INITIALIZATION
// ============================================

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Check authentication on admin pages
    if (window.location.pathname.includes('/admin/') && 
        !window.location.pathname.includes('/login.php')) {
        checkAdminAuth();
    }
    
    // Update current date in header
    const dateElements = document.querySelectorAll('.current-date');
    dateElements.forEach(el => {
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        el.textContent = new Date().toLocaleDateString('en-US', options);
    });
});