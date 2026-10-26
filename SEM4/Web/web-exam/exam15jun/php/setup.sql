-- =============================================================
-- EXAM DB SETUP  —  run this first, every time
-- MySQL: open phpMyAdmin or run: mysql -u root -p < setup.sql
-- =============================================================

CREATE DATABASE IF NOT EXISTS examdb CHARACTER SET utf8mb4;
USE examdb;

DROP TABLE IF EXISTS WaitList;
DROP TABLE IF EXISTS Booking;
DROP TABLE IF EXISTS Class;
DROP TABLE IF EXISTS User;

CREATE TABLE User (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255),
    membershipType VARCHAR(255),
    CONSTRAINT chk_user_tier CHECK (membershipType IN ('Basic', 'Premium'))
);

CREATE TABLE Class (
    id INT PRIMARY KEY AUTO_INCREMENT,
    className VARCHAR(255),
    instructorName VARCHAR(255),
    classDate DATE,
    maxCapacity INT,
    CONSTRAINT chk_class_status CHECK (className LIKE '%-Low' OR className LIKE '%-High')
);

CREATE TABLE Booking (
    id INT PRIMARY KEY AUTO_INCREMENT,
    userId INT,
    classId INT,
    bookedAt DATETIME,
    cancelled INT,
    FOREIGN KEY (userId) REFERENCES User(id) ON DELETE SET NULL,
    FOREIGN KEY (classId) REFERENCES Class(id) ON DELETE SET NULL,
    CONSTRAINT chk_cancelled CHECK (cancelled IN (0, 1))
);

CREATE TABLE WaitList (
    id INT PRIMARY KEY AUTO_INCREMENT,
    userId INT,
    classId INT,
    addedAt DATETIME,
    FOREIGN KEY (userId) REFERENCES User(id) ON DELETE SET NULL,
    FOREIGN KEY (classId) REFERENCES Class(id) ON DELETE SET NULL
);

-- Sample data
INSERT INTO User (username, membershipType) VALUES
('Alice', 'Basic'),
('Bob',   'Premium'),
('Carol', 'Basic'),
('Dave',  'Basic');

INSERT INTO Class (className, instructorName, classDate, maxCapacity) VALUES
('Yoga-Low', 'Alice', '2026-06-16', 100),
('Spinning-High', 'Dave', '2026-06-17', 50);

INSERT INTO Booking (userId, classId, bookedAt, cancelled) VALUES
(1, 1, '2026-06-13 20:20:17', 0),
(2, 1, '2026-06-13 10:20:17', 1),
(3, 2, '2026-06-12 20:20:17', 0);

INSERT INTO WaitList (userId, classId, addedAt) VALUES
(2, 1, '2026-06-13 10:20:17'),
(4, 2, '2026-06-12 20:20:17');
