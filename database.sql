CREATE DATABASE RateMyTeacher;
USE RateMyTeacher;

CREATE TABLE students (
    s_id INT AUTO_INCREMENT PRIMARY KEY,
    s_course VARCHAR(50) NOT NULL,
    enrollment_year YEAR NOT NULL,
    s_email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    CONSTRAINT students_kmcen_email CHECK (s_email LIKE '%@kmcen.edu.np')
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

CREATE TABLE teacher_subjects (
    t_id INT NOT NULL,
    subject_id INT NOT NULL,
    PRIMARY KEY (t_id, subject_id),
    FOREIGN KEY (t_id) REFERENCES teachers(t_id),
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id)
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

CREATE TABLE rating_helpful_votes (
    r_id INT NOT NULL,
    s_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (r_id, s_id),
    FOREIGN KEY (r_id) REFERENCES ratings(r_id) ON DELETE CASCADE,
    FOREIGN KEY (s_id) REFERENCES students(s_id) ON DELETE CASCADE
);

CREATE TABLE rating_reports (
    report_id INT AUTO_INCREMENT PRIMARY KEY,
    r_id INT NOT NULL,
    s_id INT NOT NULL,
    reason VARCHAR(255) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'Open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_rating_reporter (r_id, s_id),
    FOREIGN KEY (r_id) REFERENCES ratings(r_id) ON DELETE CASCADE,
    FOREIGN KEY (s_id) REFERENCES students(s_id) ON DELETE CASCADE
);

-- Sample admin
INSERT INTO admin (username, password)
VALUES ('admin', '$2y$10$Ca08QsncrUb76pdaQUzsTeerJNs38mlQxbHiTLGFJvPxtdANH6Jpe');

-- Demo data for local development and testing.
INSERT INTO teachers (t_id, t_name) VALUES
    (1, 'Sushil Ghimire'),
    (2, 'Rajit Bhusal'),
    (3, 'Sushil Khadka'),
    (4, 'Bishal Lama'),
    (5, 'Anita Shrestha'),
    (6, 'Prakash Adhikari'),
    (7, 'Nisha Karki'),
    (8, 'Ramesh Thapa'),
    (9, 'Mina Gurung'),
    (10, 'Dipak Poudel'),
    (11, 'Sarita Rai'),
    (12, 'Kiran Maharjan');

INSERT INTO subjects (subject_id, subject_name, course, semester) VALUES
    (1, 'Object-Oriented Programming', 'BCA', '1'),
    (2, 'Web Technology', 'BCA', '2'),
    (3, 'Data Structures and Algorithms', 'BCA', '3'),
    (4, 'Introduction to Artificial Intelligence', 'BCA', '4'),
    (5, 'Business Communication', 'BBA', '1'),
    (6, 'Database Systems', 'BCA', '2');

INSERT INTO teacher_subjects (t_id, subject_id) VALUES
    (1, 1), (1, 2), (1, 5),
    (2, 3), (2, 4),
    (3, 6),
    (4, 5);

INSERT INTO students (s_id, s_course, enrollment_year, s_email, password_hash) VALUES
    (1, 'BCA', YEAR(CURDATE()) - 1, 'demo.student@kmcen.edu.np', '$2y$10$ivjalL5F9ZSHEDyAlhhdouD/1r2JBW1tbSbExJ8wL.WJMIGfNI/Ka');

INSERT INTO ratings
    (r_id, quality_rating, difficulty_rating, take_again, review, review_date, status, semester, s_id, t_id, subject_id)
VALUES
    (1, 5, 3, 'yes', 'Clear explanations and helpful examples throughout the course.', '2026-01-12', 'Approved', '1', 1, 1, 1),
    (2, 4, 4, 'yes', 'Well-organized classes with challenging but fair assignments.', '2026-02-18', 'Approved', '2', 1, 1, 2),
    (3, 4, 3, 'yes', 'Good lectures and practical exercises.', '2026-03-04', 'Approved', '3', 1, 2, 3),
    (4, 3, 5, 'no', 'The workload is heavy, so plan time for every assignment.', '2026-03-22', 'Approved', '4', 1, 2, 4),
    (5, 5, 2, 'yes', 'The pending review should not appear on the public profile yet.', '2026-04-01', 'Pending', '2', 1, 3, 6),
    (6, 5, 2, 'yes', 'Very approachable and excellent at breaking down difficult topics.', '2026-04-12', 'Approved', '2', 1, 3, 6),
    (7, 4, 4, 'yes', 'Useful examples and a practical approach to algorithms.', '2026-04-20', 'Approved', '1', 1, 4, 5);