CREATE DATABASE IF NOT EXISTS concert_app;
USE concert_app;

CREATE TABLE Flights (
    flightID INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    destinationCity VARCHAR(100) NOT NULL,
    availableSeats INT NOT NULL
);

CREATE TABLE Hotels (
    hotelID INT AUTO_INCREMENT PRIMARY KEY,
    hotelName VARCHAR(100) NOT NULL,
    date DATE NOT NULL,
    city VARCHAR(100) NOT NULL,
    availableRooms INT NOT NULL
);

CREATE TABLE Reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    person VARCHAR(100) NOT NULL,
    type ENUM('Flight', 'Hotel') NOT NULL,
    idReservedResource INT NOT NULL
);

-- Date de test
INSERT INTO Flights (date, destinationCity, availableSeats) VALUES 
('2026-07-20', 'Cluj-Napoca', 5),
('2026-07-20', 'Cluj-Napoca', 0),
('2026-07-25', 'Bucuresti', 10);

INSERT INTO Hotels (hotelName, date, city, availableRooms) VALUES 
('Hotel Transilvania', '2026-07-20', 'Cluj-Napoca', 2),
('Grand Hotel', '2026-07-20', 'Cluj-Napoca', 5),
('City Center', '2026-07-25', 'Bucuresti', 3);