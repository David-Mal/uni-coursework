CREATE DATABASE IF NOT EXISTS shop_db;
USE shop_db;

CREATE TABLE User (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL
);

CREATE TABLE Product (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL
);

-- Am pus backticks în jurul numelui Order deoarece este cuvânt rezervat
CREATE TABLE `Order` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    userID INT,
    totalPrice DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (userID) REFERENCES User(id)
);

CREATE TABLE OrderItem (
    id INT AUTO_INCREMENT PRIMARY KEY,
    orderID INT,
    productID INT,
    FOREIGN KEY (orderID) REFERENCES `Order`(id),
    FOREIGN KEY (productID) REFERENCES Product(id)
);

-- Inserare date de test
INSERT INTO User (username) VALUES ('David'), ('Bob'), ('Messi');

INSERT INTO Product (name, price) VALUES 
('TOY-car', 10.00), 
('TOY-doll', 15.00), 
('TOY-teddy bear', 20.00), 
('BOOK-math', 30.00), 
('BOOK-english', 25.00), 
('FOOD-apple', 5.00), 
('FOOD-banana', 3.00), 
('DEVICE-laptop', 1000.00), 
('DEVICE-phone', 500.00);

INSERT INTO `Order` (userID, totalPrice) VALUES (1, 25.00), (2, 30.00), (3, 1000.00);

INSERT INTO OrderItem (orderID, productID) VALUES (1, 1), (1, 2), (2, 4), (3, 8);