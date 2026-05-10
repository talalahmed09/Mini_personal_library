

CREATE DATABASE IF NOT EXISTS mini_library;
USE mini_library;


CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    author VARCHAR(100) NOT NULL,
    category VARCHAR(100) NOT NULL,
    status ENUM('want_to_read', 'reading', 'completed') DEFAULT 'want_to_read',
    book_type ENUM('online', 'offline') DEFAULT 'offline',
    book_link VARCHAR(500) DEFAULT NULL,
    rating INT DEFAULT NULL CHECK (rating >= 1 AND rating <= 5),
    chapters_total INT DEFAULT NULL,
    chapters_read  INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


