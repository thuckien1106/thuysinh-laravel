-- =========================================
-- 🐠 AQUASHOP_FINAL_FULL.SQL
-- Phiên bản đầy đủ cho đồ án kết thúc môn
-- Có bảng customers và addresses liên kết chuẩn
-- =========================================

-- 1️⃣ TẠO DATABASE
CREATE DATABASE IF NOT EXISTS aquashop
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE aquashop;

-- =========================================
-- 2️⃣ CẤU TRÚC CƠ SỞ DỮ LIỆU
-- =========================================

-- DANH MỤC SẢN PHẨM
CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- THƯƠNG HIỆU
CREATE TABLE brands (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL UNIQUE,
  slug VARCHAR(140) UNIQUE,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- NGƯỜI DÙNG HỆ THỐNG (ADMIN / USER)
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(60) UNIQUE NOT NULL,
  email VARCHAR(120) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('user','admin') DEFAULT 'user',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- KHÁCH HÀNG
CREATE TABLE customers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(120) NOT NULL,
  email VARCHAR(120),
  phone VARCHAR(20),
  gender ENUM('Nam','Nữ','Khác') DEFAULT 'Khác',
  birthday DATE NULL,
  address VARCHAR(255),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SẢN PHẨM
CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  description TEXT,
  price DECIMAL(10,2) NOT NULL DEFAULT 0,
  quantity INT NOT NULL DEFAULT 0,
  image VARCHAR(255) DEFAULT 'placeholder.webp',
  category_id INT,
  brand_id INT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id)
    ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (brand_id) REFERENCES brands(id)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ĐƠN HÀNG
CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  customer_id INT NULL,
  total DECIMAL(10,2) NOT NULL DEFAULT 0,
  status VARCHAR(50) DEFAULT 'Đang xử lý',
  customer_name VARCHAR(120),
  customer_address VARCHAR(255),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (customer_id) REFERENCES customers(id)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- CHI TIẾT ĐƠN HÀNG
CREATE TABLE order_details (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ĐÁNH GIÁ SẢN PHẨM
CREATE TABLE reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT NOT NULL,
  user_id INT NULL,
  rating TINYINT NOT NULL,
  content TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (product_id) REFERENCES products(id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- BIẾN ĐỘNG KHO
CREATE TABLE inventory_movements (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT NOT NULL,
  change_qty INT NOT NULL,
  reason VARCHAR(120),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ĐỊA CHỈ GIAO HÀNG (LIÊN KẾT CUSTOMERS)
CREATE TABLE addresses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_id INT NOT NULL,
  full_name VARCHAR(120),
  phone VARCHAR(30),
  address_line VARCHAR(255),
  ward VARCHAR(120),
  district VARCHAR(120),
  province VARCHAR(120),
  is_default TINYINT(1) DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (customer_id) REFERENCES customers(id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- THANH TOÁN
CREATE TABLE payments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  method VARCHAR(50) NOT NULL DEFAULT 'cod',
  amount DECIMAL(10,2) NOT NULL,
  status VARCHAR(30) NOT NULL DEFAULT 'pending',
  transaction_id VARCHAR(120),
  paid_at DATETIME NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (order_id) REFERENCES orders(id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- VẬN CHUYỂN
CREATE TABLE shipments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  carrier VARCHAR(60) DEFAULT 'local',
  tracking_code VARCHAR(120),
  status VARCHAR(30) DEFAULT 'pending',
  shipped_at DATETIME NULL,
  delivered_at DATETIME NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (order_id) REFERENCES orders(id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================
-- 3️⃣ DỮ LIỆU MẪU
-- =========================================

-- DANH MỤC
INSERT INTO categories (name) VALUES
('Thủy sinh'),('Cá cảnh'),('Phụ kiện'),('Hệ thống CO2'),('Thức ăn cho cá');

-- THƯƠNG HIỆU
INSERT INTO brands (name, slug) VALUES
('Aqua Plants','aqua-plants'),
('Freshwater Fish','freshwater-fish'),
('Accessories','accessories'),
('ADA Design','ada-design'),
('JBL Aquatics','jbl-aquatics');

-- NGƯỜI DÙNG HỆ THỐNG
INSERT INTO users (username, email, password, role) VALUES
('admin','admin@example.com','admin123','admin'),
('user1','user1@example.com','123456','user'),
('user2','user2@example.com','123456','user'),
('user3','user3@example.com','123456','user'),
('user4','user4@example.com','123456','user');

-- KHÁCH HÀNG
INSERT INTO customers (full_name, email, phone, gender, birthday, address) VALUES
('Nguyễn Văn An','an@gmail.com','0905123456','Nam','1995-03-20','123 Trần Phú, Q.5, TP.HCM'),
('Trần Thị Bình','binh@gmail.com','0912345678','Nữ','1998-07-12','45 Nguyễn Huệ, Q.1, TP.HCM'),
('Phạm Minh Đức','duc@gmail.com','0902223333','Nam','1992-10-05','89 Lê Lợi, Q.3, TP.HCM'),
('Lê Hồng Hoa','hoa@gmail.com','0977665544','Nữ','2000-01-15','23 Hai Bà Trưng, Q.1, TP.HCM'),
('Võ Quốc Dũng','dung@gmail.com','0933445566','Nam','1996-06-28','77 Nguyễn Văn Linh, Q.7, TP.HCM');

-- SẢN PHẨM (25)
INSERT INTO products (name, description, price, quantity, image, category_id, brand_id) VALUES
('Rêu Java','Rêu thủy sinh phổ biến, dễ trồng.',35000,100,'reu_java.webp',1,1),
('Cây Buồm Đỏ','Cây thủy sinh màu đỏ tươi.',45000,80,'cay_buom_do.webp',1,1),
('Cây Tiêu Thảo','Cây chịu bóng, dễ chăm sóc.',38000,60,'tieu_thao.webp',1,1),
('Rêu Mini Taiwan','Rêu nhỏ mịn, bám đá tốt.',45000,90,'reu_taiwan.webp',1,1),
('Cây Đại Hồng Diệp','Cần ánh sáng mạnh.',55000,50,'hong_diep.webp',1,1),
('Cá Neon','Cá cảnh nhỏ, bơi theo đàn.',15000,200,'ca_neon.webp',2,2),
('Cá Bảy Màu','Guppy nhiều màu.',12000,300,'ca_bay_mau.webp',2,2),
('Cá Betta','Xiêm, màu sặc sỡ.',35000,100,'ca_betta.webp',2,2),
('Cá Ranchu','Cá vàng đầu lân.',45000,70,'ca_ranchu.webp',2,2),
('Cá Chuột Cory','Dọn bể hiền lành.',25000,120,'ca_chuot.webp',2,2),
('Đèn LED 60cm','LED quang hợp.',420000,20,'den_led_60.webp',3,3),
('Máy Lọc Mini','Cho hồ nhỏ.',350000,15,'loc_mini.webp',3,3),
('Sủi Oxy Hai Đầu','Tăng oxy cho cá.',45000,80,'sui_oxy.webp',3,3),
('Vợt Cá Mini','Khung inox.',30000,100,'vot_ca.webp',3,3),
('Ống Hút Vệ Sinh','Hút cặn đáy.',45000,70,'ong_hut.webp',3,3),
('Bình CO2 Mini','Phù hợp hồ nhỏ.',150000,30,'co2_mini.webp',4,4),
('Van Điều Áp CO2 ADA','Điều chỉnh CO2.',950000,10,'van_ada.webp',4,4),
('Đồng Hồ CO2','Theo dõi áp suất.',250000,25,'dong_ho_co2.webp',4,4),
('Ống Dẫn CO2','Ống chịu áp cao.',55000,50,'ong_co2.webp',4,4),
('Sủi CO2 Thủy Tinh','Bọt mịn, hòa tan tốt.',70000,40,'sui_co2.webp',4,4),
('Thức Ăn JBL NovoBel','Thức ăn tổng hợp.',85000,60,'an_jbl.webp',5,5),
('Thức Ăn Tetra Bits','Lên màu đẹp.',110000,50,'an_tetra.webp',5,5),
('Thức Ăn Dạng Viên','Phù hợp nhiều loại.',95000,40,'an_vien.webp',5,5),
('Thức Ăn Dạng Bột','Cho cá bột.',65000,70,'an_bot.webp',5,5),
('Vitamin Cá','Tăng đề kháng.',125000,30,'vitamin_ca.webp',5,5);

-- ĐƠN HÀNG (10)
INSERT INTO orders (user_id, customer_id, total, status, customer_name, customer_address, created_at) VALUES
(2,1,85000,'processing','Nguyễn Văn An','123 Trần Phú, Q.5, TP.HCM',NOW() - INTERVAL 1 DAY),
(3,2,420000,'completed','Trần Thị Bình','45 Nguyễn Huệ, Q.1, TP.HCM',NOW() - INTERVAL 2 DAY),
(4,3,400000,'shipping','Phạm Minh Đức','89 Lê Lợi, Q.3, TP.HCM',NOW() - INTERVAL 3 DAY),
(5,4,195000,'completed','Lê Hồng Hoa','23 Hai Bà Trưng, Q.1, TP.HCM',NOW() - INTERVAL 4 DAY),
(2,5,45000,'processing','Võ Quốc Dũng','77 Nguyễn Văn Linh, Q.7, TP.HCM',NOW() - INTERVAL 5 DAY),
(3,1,950000,'completed','Nguyễn Văn An','123 Trần Phú, Q.5, TP.HCM',NOW() - INTERVAL 6 DAY),
(4,2,110000,'shipping','Trần Thị Bình','45 Nguyễn Huệ, Q.1, TP.HCM',NOW() - INTERVAL 7 DAY),
(5,3,105000,'completed','Phạm Minh Đức','89 Lê Lợi, Q.3, TP.HCM',NOW() - INTERVAL 8 DAY),
(2,4,70000,'processing','Lê Hồng Hoa','23 Hai Bà Trưng, Q.1, TP.HCM',NOW() - INTERVAL 9 DAY),
(3,5,125000,'completed','Võ Quốc Dũng','77 Nguyễn Văn Linh, Q.7, TP.HCM',NOW() - INTERVAL 10 DAY);

-- CHI TIẾT ĐƠN HÀNG
INSERT INTO order_details (order_id, product_id, quantity, price) VALUES
(1,1,2,35000),(1,6,1,15000),
(2,11,1,420000),
(3,10,2,25000),(3,12,1,350000),
(4,21,1,85000),(4,22,1,110000),
(5,15,1,45000),
(6,17,1,950000),
(7,19,2,55000),
(8,8,3,35000),
(9,20,1,70000),
(10,14,1,30000),(10,23,1,95000);

-- ĐÁNH GIÁ
INSERT INTO reviews (product_id, user_id, rating, content, created_at) VALUES
(1,2,5,'Rêu Java phát triển nhanh.',NOW() - INTERVAL 1 DAY),
(2,3,4,'Cây Buồm Đỏ đẹp.',NOW() - INTERVAL 2 DAY),
(6,4,5,'Cá Neon khỏe mạnh.',NOW() - INTERVAL 3 DAY),
(7,5,5,'Cá Bảy Màu sinh sản tốt.',NOW() - INTERVAL 4 DAY),
(11,2,5,'Đèn LED sáng tốt.',NOW() - INTERVAL 5 DAY),
(17,3,4,'Van CO2 dễ điều chỉnh.',NOW() - INTERVAL 6 DAY),
(21,4,5,'Thức ăn JBL chất lượng.',NOW() - INTERVAL 7 DAY),
(22,5,4,'Cá ăn khỏe.',NOW() - INTERVAL 8 DAY),
(19,3,5,'Ống CO2 bền.',NOW() - INTERVAL 9 DAY),
(14,2,4,'Vợt cá nhẹ, dễ dùng.',NOW() - INTERVAL 10 DAY);

-- BIẾN ĐỘNG KHO
INSERT INTO inventory_movements (product_id, change_qty, reason, created_at) VALUES
(1,50,'Nhập kho đầu kỳ',NOW() - INTERVAL 10 DAY),
(2,-20,'Bán lẻ',NOW() - INTERVAL 9 DAY),
(6,100,'Nhập cá mới',NOW() - INTERVAL 8 DAY),
(11,-10,'Bán đèn LED',NOW() - INTERVAL 7 DAY),
(15,-5,'Trả hàng lỗi',NOW() - INTERVAL 6 DAY),
(17,15,'Nhập thêm van CO2',NOW() - INTERVAL 5 DAY),
(19,5,'Điều chỉnh kho tăng',NOW() - INTERVAL 4 DAY),
(21,20,'Nhập hàng JBL',NOW() - INTERVAL 3 DAY),
(23,3,'Khách trả hàng',NOW() - INTERVAL 2 DAY),
(25,30,'Nhập Vitamin mới',NOW() - INTERVAL 1 DAY);

-- ĐỊA CHỈ GIAO HÀNG
INSERT INTO addresses (customer_id, full_name, phone, address_line, ward, district, province, is_default) VALUES
(1,'Nguyễn Văn An','0905123456','123 Trần Phú','Phường 5','Quận 5','TP.HCM',1),
(2,'Trần Thị Bình','0912345678','45 Nguyễn Huệ','Phường Bến Nghé','Quận 1','TP.HCM',1);

-- THANH TOÁN
INSERT INTO payments (order_id, method, amount, status, transaction_id, paid_at) VALUES
(1,'cod',85000,'paid',NULL,NOW() - INTERVAL 1 DAY),
(2,'cod',420000,'paid',NULL,NOW() - INTERVAL 2 DAY),
(3,'cod',400000,'pending',NULL,NULL);

-- VẬN CHUYỂN
INSERT INTO shipments (order_id, carrier, tracking_code, status, shipped_at, delivered_at) VALUES
(1,'local','AQ1-0001','delivered',NOW() - INTERVAL 1 DAY,NOW() - INTERVAL 1 DAY),
(2,'local','AQ1-0002','delivered',NOW() - INTERVAL 2 DAY,NOW() - INTERVAL 2 DAY),
(3,'local','AQ1-0003','pending',NULL,NULL);

-- =========================================
-- Normalize order status to canonical codes
-- =========================================
ALTER TABLE orders
  MODIFY COLUMN status ENUM('processing','shipping','completed','cancelled') DEFAULT 'processing';

-- Migrate any legacy Vietnamese labels to codes
UPDATE orders SET status='processing' WHERE status IN ('Đang xử lý','Dang xu ly');
UPDATE orders SET status='shipping'   WHERE status IN ('Đang giao','Dang giao');
UPDATE orders SET status='completed'  WHERE status IN ('Hoàn thành','Hoan thanh');
UPDATE orders SET status='cancelled'  WHERE status IN ('Đã hủy','Da huy');
