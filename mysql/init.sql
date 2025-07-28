CREATE DATABASE IF NOT EXISTS testdb;

USE testdb;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL
);

INSERT INTO users (name, email) VALUES 
('Muhammad Faisal', 'muhammadfaisal@example.com'),
('Ali Shah', 'alishah@example.com'),
('Ahmed Ali', 'ahmedali@example.com');
