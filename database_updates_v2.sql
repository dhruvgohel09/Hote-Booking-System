-- Run in phpMyAdmin (SQL tab) if you already have an older `hotelbooking` DB without bill columns.
USE hotelbooking;

ALTER TABLE bookings ADD COLUMN guests INT NOT NULL DEFAULT 2 AFTER room_id;
ALTER TABLE bookings ADD COLUMN bill_number VARCHAR(40) NULL AFTER total_price;
ALTER TABLE bookings ADD UNIQUE KEY uk_bill_number (bill_number);

UPDATE bookings
SET bill_number = CONCAT('INV-', YEAR(created_at), '-', LPAD(id, 6, '0'))
WHERE bill_number IS NULL OR bill_number = '';
