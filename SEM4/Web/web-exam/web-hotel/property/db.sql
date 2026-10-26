CREATE DATABASE IF NOT EXISTS properties;
USE properties;

CREATE TABLE User (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    secretQuestion VARCHAR(255) NOT NULL,
    secretAnswer VARCHAR(255) NOT NULL
);

CREATE TABLE Property (
    id INT AUTO_INCREMENT PRIMARY KEY,
    address VARCHAR(255) NOT NULL,
    description TEXT NOT NULL
);

CREATE TABLE UserToProperties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    idUser INT NOT NULL,
    idProperty INT NOT NULL,
    FOREIGN KEY (idUser) REFERENCES User(id),
    FOREIGN KEY (idProperty) REFERENCES Property(id)
);

INSERT INTO User (name, secretQuestion, secretAnswer) VALUES
    ('David', 'What is your pet name?', 'Max'),
    ('Alice', 'What city were you born in?', 'Bucharest'),
    ('Bob', 'What is your favorite color?', 'Blue');

INSERT INTO Property (address, description) VALUES
    ('10 Main Street', 'Cozy apartment near downtown with modern kitchen'),
    ('22 Oak Avenue', 'Spacious house with large garden and garage'),
    ('5 Lake Road', 'Modern studio apartment with lake view'),
    ('18 Hill Street', 'Family home with renovated kitchen and patio');

INSERT INTO UserToProperties (idUser, idProperty) VALUES
    (1, 1),
    (1, 2),
    (2, 2),
    (2, 3),
    (3, 4);
