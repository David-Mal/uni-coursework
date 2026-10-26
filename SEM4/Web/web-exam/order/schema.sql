CREATE DATABASE IF NOT EXISTS order_app;
USE order_app;

CREATE TABLE User (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE Product (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL
);

CREATE TABLE `Order` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    userID INT NOT NULL,
    totalPrice DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (userID) REFERENCES User(id)
);

CREATE TABLE OrderItem (
    id INT AUTO_INCREMENT PRIMARY KEY,
    orderID INT NOT NULL,
    productID INT NOT NULL,
    FOREIGN KEY (orderID) REFERENCES `Order`(id),
    FOREIGN KEY (productID) REFERENCES Product(id)
);

INSERT INTO User (username) VALUES ('alice'), ('bob'), ('charlie');

INSERT INTO Product (name, price) VALUES
    ('BOOK-Math', 25.00),
    ('BOOK-History', 30.00),
    ('BOOK-Science', 28.00),
    ('TOY-car', 15.00),
    ('TOY-doll', 20.00),
    ('GAME-puzzle', 35.00),
    ('GAME-cards', 12.00);
