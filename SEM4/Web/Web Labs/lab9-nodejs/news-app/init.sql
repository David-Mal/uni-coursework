CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    text TEXT NOT NULL,
    producer VARCHAR(100),
    date DATE NOT NULL,
    category VARCHAR(50) NOT NULL
);

INSERT INTO users (username, password) VALUES ('admin', 'admin');

INSERT INTO news (title, text, producer, date, category) VALUES
('New Health Guidelines Released', 'The WHO has released new guidelines for daily exercise and nutrition recommendations for adults.', 'WHO Press', '2026-04-01', 'health'),
('Local Elections Update', 'City council elections are scheduled for next month with several new candidates entering the race.', 'City Times', '2026-04-05', 'politics'),
('Championship Finals This Weekend', 'The national basketball championship finals begin this Saturday with a sold-out arena expected.', 'Sports Daily', '2026-04-08', 'sports'),
('Community Park Renovation Completed', 'The downtown community park has reopened after a six-month renovation with new facilities for families.', 'Local News', '2026-04-03', 'society'),
('Storm Warning for the Region', 'Meteorologists predict heavy rainfall and strong winds over the next 48 hours across the northern region.', 'Weather Central', '2026-04-09', 'weather');
