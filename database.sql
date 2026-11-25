CREATE DATABASE IF NOT EXISTS hostelhub CHARACTER SET utf8mb4;
USE hostelhub;
CREATE TABLE users(id INT AUTO_INCREMENT PRIMARY KEY,name VARCHAR(100),email VARCHAR(120) UNIQUE,password VARCHAR(255),role ENUM('admin','student') DEFAULT 'student',room_id INT NULL);
CREATE TABLE rooms(id INT AUTO_INCREMENT PRIMARY KEY,room_no VARCHAR(20) UNIQUE,block VARCHAR(10),capacity INT DEFAULT 2);
CREATE TABLE student_profiles(user_id INT PRIMARY KEY,enrollment_no VARCHAR(40) NOT NULL UNIQUE,phone VARCHAR(30) NOT NULL,course VARCHAR(100) NOT NULL,department VARCHAR(100) NOT NULL,FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE);
CREATE TABLE contact_messages(id INT AUTO_INCREMENT PRIMARY KEY,full_name VARCHAR(100) NOT NULL,email VARCHAR(120) NOT NULL,subject VARCHAR(200) NOT NULL,message TEXT NOT NULL,status ENUM('New','Read','Closed') DEFAULT 'New',created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE complaints(id INT AUTO_INCREMENT PRIMARY KEY,student_id INT,title VARCHAR(200),category VARCHAR(50),status ENUM('Pending','In Progress','Resolved') DEFAULT 'Pending',created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE mess_menu(day VARCHAR(10) PRIMARY KEY,breakfast VARCHAR(150),lunch VARCHAR(150),dinner VARCHAR(150));
CREATE TABLE notices(id INT AUTO_INCREMENT PRIMARY KEY,title VARCHAR(200),body TEXT,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
-- Demo password for all accounts: password
INSERT INTO rooms(room_no,block,capacity) VALUES('A-102','A',2),('A-401','A',2),('B-205','B',3),('C-310','C',2),('C-311','C',2);
INSERT INTO users(name,email,password,role,room_id) VALUES
('Admin User','admin@hostelhub.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','admin',NULL),
('John Doe','john@student.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','student',1),
('Jane Smith','jane@student.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','student',3),
('Alex Johnson','alex@student.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','student',4),
('Michael Brown','michael@student.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','student',2);
INSERT INTO complaints(student_id,title,category,status) VALUES(2,'Leaking pipe in bathroom','Plumbing','Pending'),(3,'Fan regulator not working','Electrical','In Progress'),(4,'Broken window latch','Carpentry','Resolved'),(5,'Wi-Fi router unstable','IT/Network','Pending');
INSERT INTO mess_menu VALUES('Monday','Poha, Tea','Dal, Rice, Roti, Sabzi','Paneer, Roti, Rice'),('Tuesday','Idli, Sambar','Rajma, Rice, Roti','Veg Pulao, Raita'),('Wednesday','Paratha, Curd','Chole, Rice, Roti','Dal Fry, Roti, Rice'),('Thursday','Upma, Tea','Kadhi, Rice, Roti','Mix Veg, Roti'),('Friday','Bread Omelette/Jam','Dal, Rice, Roti, Sabzi','Veg Biryani, Raita'),('Saturday','Aloo Paratha','Pav Bhaji','Dal Tadka, Rice'),('Sunday','Puri Bhaji','Special Thali','Light Khichdi');
INSERT INTO notices(title,body) VALUES('Welcome to HostelHub','Please keep your rooms clean and report issues via Complaints.');
