-- Run this file by importing it in phpMyAdmin.
-- Create the database first in phpMyAdmin, then select it and import this file.

CREATE TABLE IF NOT EXISTS `User` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS HotelRoom (
    id INT AUTO_INCREMENT PRIMARY KEY,
    roomNumber VARCHAR(20) NOT NULL,
    capacity INT NOT NULL,
    basePrice INT NOT NULL
);

CREATE TABLE IF NOT EXISTS Reservation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    userId INT NOT NULL,
    roomId INT NOT NULL,
    checkInDate DATE NOT NULL,
    checkOutDate DATE NOT NULL,
    numberOfGuests INT NOT NULL,
    totalPrice INT NOT NULL,
    FOREIGN KEY (userId) REFERENCES `User`(id),
    FOREIGN KEY (roomId) REFERENCES HotelRoom(id)
);

-- Seed users  (password for both: "pass")
INSERT INTO `User` (username, password) VALUES
('admin', 'pass'),
('user1', 'pass');

-- Seed rooms
INSERT INTO HotelRoom (roomNumber, capacity, basePrice) VALUES
('101', 2, 100),
('102', 2, 100),
('103', 3, 150),
('104', 2, 100),
('105', 4, 200),
('201', 2, 120),
('202', 2, 120),
('203', 3, 160),
('204', 2, 120),
('205', 1, 80);
