-- =========================================
-- DATABASE: AQUASHOP
-- =========================================

-- Tạo DB & sử dụng
CREATE DATABASE IF NOT EXISTS aquashop
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE aquashop;

-- =========================================
-- BẢNG DANH MỤC
-- =========================================
CREATE TABLE IF NOT EXISTS categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL
);

-- =========================================
-- BẢNG SẢN PHẨM
-- =========================================
CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  description TEXT,
  price DECIMAL(10,2) NOT NULL DEFAULT 0,
  quantity INT NOT NULL DEFAULT 0,
  image VARCHAR(255) DEFAULT 'placeholder.webp',
  category_id INT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- =========================================
-- BẢNG NGƯỜI DÙNG
-- =========================================
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(60) UNIQUE NOT NULL,
  email VARCHAR(120) UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('user','admin') DEFAULT 'user',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- =========================================
-- BẢNG ĐƠN HÀNG
-- =========================================
CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  total DECIMAL(10,2) NOT NULL DEFAULT 0,
  status VARCHAR(50) DEFAULT 'Đang xử lý',
  customer_name VARCHAR(120),
  customer_address VARCHAR(255),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

-- =========================================
-- CHI TIẾT ĐƠN HÀNG
-- =========================================
CREATE TABLE IF NOT EXISTS order_details (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id),
  FOREIGN KEY (product_id) REFERENCES products(id)
);

-- =========================================
-- LIÊN HỆ KHÁCH HÀNG
-- =========================================
CREATE TABLE IF NOT EXISTS contacts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120),
  email VARCHAR(120),
  message TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- =========================================
-- DỮ LIỆU MẪU
-- =========================================

-- Danh mục
INSERT INTO categories (name) VALUES
('Thủy sinh'), 
('Cá cảnh'), 
('Phụ kiện')
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- Sản phẩm mẫu (ảnh .webp, mô tả chi tiết hơn)
INSERT INTO products (name, description, price, quantity, image, category_id) VALUES
('Rêu Java', 
 'Loại rêu phổ biến, dễ trồng, phát triển tốt trong ánh sáng trung bình. Giúp tạo cảm giác tự nhiên và cung cấp nơi trú ẩn cho cá con.',
 35000, 120, 'reu_java.webp', 1),

('Cá Neon', 
 'Cá nhỏ, bơi theo đàn, có sọc xanh dạ quang nổi bật. Dễ nuôi, thích hợp cho cả người mới chơi thủy sinh.',
 15000, 300, 'ca_neon.webp', 2),

('Đèn LED 60cm', 
 'Đèn LED quang hợp chất lượng cao, ánh sáng trắng pha hồng, phù hợp cho bể 50–70cm. Tiết kiệm điện và hỗ trợ cây phát triển khỏe mạnh.',
 420000, 25, 'den_led_60.webp', 3)
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- Tài khoản quản trị
-- Mật khẩu: admin123
INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@example.com', 
 '$2y$10$X3XU5ZbE3x1jOAGuU3cY4uR0kq0y1hN9XwH7dT1mH1b3m8kQk1hSi', 
 'admin')
ON DUPLICATE KEY UPDATE email = VALUES(email);
