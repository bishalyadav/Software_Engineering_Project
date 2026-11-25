-- Run once in phpMyAdmin on the existing hostelhub database.
-- This preserves existing accounts and adds the registration/contact tables.
CREATE TABLE IF NOT EXISTS student_profiles (
  user_id INT PRIMARY KEY,
  enrollment_no VARCHAR(40) NOT NULL UNIQUE,
  phone VARCHAR(30) NOT NULL,
  course VARCHAR(100) NOT NULL,
  department VARCHAR(100) NOT NULL,
  CONSTRAINT fk_student_profiles_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS contact_messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(100) NOT NULL,
  email VARCHAR(120) NOT NULL,
  subject VARCHAR(200) NOT NULL,
  message TEXT NOT NULL,
  status ENUM('New','Read','Closed') DEFAULT 'New',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
