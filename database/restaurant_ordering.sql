CREATE DATABASE IF NOT EXISTS restaurant_ordering;
USE restaurant_ordering;

-- =========================================
-- ADMINS TABLE
-- =========================================

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100),
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================
-- USERS TABLE
-- =========================================

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(100) UNIQUE NOT NULL,
    address TEXT,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================
-- CATEGORIES TABLE
-- =========================================

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL,
    image VARCHAR(255),
    status ENUM('Active','Inactive') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================
-- FOODS TABLE
-- =========================================

CREATE TABLE foods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    food_name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2),
    image VARCHAR(255),
    status ENUM('Available','Unavailable') DEFAULT 'Available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- =========================================
-- ORDERS TABLE
-- =========================================

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total_amount DECIMAL(10,2),
    payment_method VARCHAR(50),
    order_status ENUM(
        'Pending',
        'Preparing',
        'Out for Delivery',
        'Completed',
        'Cancelled'
    ) DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- =========================================
-- ORDER ITEMS TABLE
-- =========================================

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    food_id INT,
    quantity INT,
    price DECIMAL(10,2),
    subtotal DECIMAL(10,2),
    FOREIGN KEY(order_id) REFERENCES orders(id),
    FOREIGN KEY(food_id) REFERENCES foods(id)
);

-- =========================================
-- CONTACT MESSAGES
-- =========================================

CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100),
    email VARCHAR(100),
    subject VARCHAR(150),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================
-- SETTINGS
-- =========================================

CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    restaurant_name VARCHAR(100),
    phone VARCHAR(30),
    email VARCHAR(100),
    address TEXT,
    logo VARCHAR(255)
);