CREATE DATABASE rate_my_teacher;
USE rate_my_teacher;

CREATE TABLE students (
    s_id INT AUTO_INCREMENT PRIMARY KEY,
    s_name VARCHAR(100) NOT NULL,
    s_course VARCHAR(50) NOT NULL,
    s_semester VARCHAR(20) NOT NULL
);

CREATE TABLE teachers (
    t_id INT AUTO_INCREMENT PRIMARY KEY,
    t_name VARCHAR(100) NOT NULL
);

CREATE TABLE subjects (
    subject_id INT AUTO_INCREMENT PRIMARY KEY,
    subject_name VARCHAR(100) NOT NULL,
    course VARCHAR(50) NOT NULL,
    semester VARCHAR(20) NOT NULL
);

CREATE TABLE admin (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE ratings (
    r_id INT AUTO_INCREMENT PRIMARY KEY,

    quality_rating INT NOT NULL,
    difficulty_rating INT NOT NULL,
    take_again VARCHAR(10) NOT NULL,
    review TEXT,
    review_date DATE NOT NULL,
    status VARCHAR(20) DEFAULT 'Pending',
    semester VARCHAR(20) NOT NULL,

    s_id INT NOT NULL,
    t_id INT NOT NULL,
    subject_id INT NOT NULL,
    admin_id INT NULL,

    FOREIGN KEY (s_id) REFERENCES students(s_id),
    FOREIGN KEY (t_id) REFERENCES teachers(t_id),
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id),
    FOREIGN KEY (admin_id) REFERENCES admin(admin_id)
);

-- Sample admin
INSERT INTO admin (username, password)
VALUES ('admin', 'admin123');