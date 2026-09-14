USE RateMyTeacher;

INSERT INTO teachers (t_name)
SELECT 'Sushil Ghimire' WHERE NOT EXISTS (SELECT 1 FROM teachers WHERE t_name = 'Sushil Ghimire');
INSERT INTO teachers (t_name)
SELECT 'Rajit Bhusal' WHERE NOT EXISTS (SELECT 1 FROM teachers WHERE t_name = 'Rajit Bhusal');
INSERT INTO teachers (t_name)
SELECT 'Sushil Khadka' WHERE NOT EXISTS (SELECT 1 FROM teachers WHERE t_name = 'Sushil Khadka');
INSERT INTO teachers (t_name)
SELECT 'Bishal Lama' WHERE NOT EXISTS (SELECT 1 FROM teachers WHERE t_name = 'Bishal Lama');
INSERT INTO teachers (t_name)
SELECT 'Anita Shrestha' WHERE NOT EXISTS (SELECT 1 FROM teachers WHERE t_name = 'Anita Shrestha');
INSERT INTO teachers (t_name)
SELECT 'Prakash Adhikari' WHERE NOT EXISTS (SELECT 1 FROM teachers WHERE t_name = 'Prakash Adhikari');
INSERT INTO teachers (t_name)
SELECT 'Nisha Karki' WHERE NOT EXISTS (SELECT 1 FROM teachers WHERE t_name = 'Nisha Karki');
INSERT INTO teachers (t_name)
SELECT 'Ramesh Thapa' WHERE NOT EXISTS (SELECT 1 FROM teachers WHERE t_name = 'Ramesh Thapa');
INSERT INTO teachers (t_name)
SELECT 'Mina Gurung' WHERE NOT EXISTS (SELECT 1 FROM teachers WHERE t_name = 'Mina Gurung');
INSERT INTO teachers (t_name)
SELECT 'Dipak Poudel' WHERE NOT EXISTS (SELECT 1 FROM teachers WHERE t_name = 'Dipak Poudel');
INSERT INTO teachers (t_name)
SELECT 'Sarita Rai' WHERE NOT EXISTS (SELECT 1 FROM teachers WHERE t_name = 'Sarita Rai');
INSERT INTO teachers (t_name)
SELECT 'Kiran Maharjan' WHERE NOT EXISTS (SELECT 1 FROM teachers WHERE t_name = 'Kiran Maharjan');

INSERT INTO subjects (subject_name, course, semester)
SELECT 'Object-Oriented Programming', 'BCA', '1'
    AND NOT EXISTS (SELECT 1 FROM subjects WHERE subject_name = 'Object-Oriented Programming' AND course = 'BCA' AND semester = '1');
INSERT INTO subjects (subject_name, course, semester)
SELECT 'Web Technology', 'BCA', '2'
    AND NOT EXISTS (SELECT 1 FROM subjects WHERE subject_name = 'Web Technology' AND course = 'BCA' AND semester = '2');
INSERT INTO subjects (subject_name, course, semester)
SELECT 'Data Structures and Algorithms', 'BCA', '3'
    AND NOT EXISTS (SELECT 1 FROM subjects WHERE subject_name = 'Data Structures and Algorithms' AND course = 'BCA' AND semester = '3');
INSERT INTO subjects (subject_name, course, semester)
SELECT 'Introduction to Artificial Intelligence', 'BCA', '4'
    AND NOT EXISTS (SELECT 1 FROM subjects WHERE subject_name = 'Introduction to Artificial Intelligence' AND course = 'BCA' AND semester = '4');
INSERT INTO subjects (subject_name, course, semester)
SELECT 'Business Communication', 'BBA', '1'
    AND NOT EXISTS (SELECT 1 FROM subjects WHERE subject_name = 'Business Communication' AND course = 'BBA' AND semester = '1');
INSERT INTO subjects (subject_name, course, semester)
SELECT 'Database Systems', 'BCA', '2'
    AND NOT EXISTS (SELECT 1 FROM subjects WHERE subject_name = 'Database Systems' AND course = 'BCA' AND semester = '2');

INSERT IGNORE INTO teacher_subjects (t_id, subject_id)
SELECT t.t_id, s.subject_id
FROM teachers AS t
INNER JOIN subjects AS s ON s.subject_name = 'Object-Oriented Programming' AND s.course = 'BCA' AND s.semester = '1'
WHERE t.t_name = 'Sushil Ghimire';
INSERT IGNORE INTO teacher_subjects (t_id, subject_id)
SELECT t.t_id, s.subject_id
FROM teachers AS t
INNER JOIN subjects AS s ON s.subject_name = 'Web Technology' AND s.course = 'BCA' AND s.semester = '2'
WHERE t.t_name = 'Sushil Ghimire';
INSERT IGNORE INTO teacher_subjects (t_id, subject_id)
SELECT t.t_id, s.subject_id
FROM teachers AS t
INNER JOIN subjects AS s ON s.subject_name = 'Data Structures and Algorithms' AND s.course = 'BCA' AND s.semester = '3'
WHERE t.t_name = 'Rajit Bhusal';
INSERT IGNORE INTO teacher_subjects (t_id, subject_id)
SELECT t.t_id, s.subject_id
FROM teachers AS t
INNER JOIN subjects AS s ON s.subject_name = 'Introduction to Artificial Intelligence' AND s.course = 'BCA' AND s.semester = '4'
WHERE t.t_name = 'Rajit Bhusal';
INSERT IGNORE INTO teacher_subjects (t_id, subject_id)
SELECT t.t_id, s.subject_id
FROM teachers AS t
INNER JOIN subjects AS s ON s.subject_name = 'Business Communication' AND s.course = 'BBA' AND s.semester = '1'
WHERE t.t_name = 'Bishal Lama';
INSERT IGNORE INTO teacher_subjects (t_id, subject_id)
SELECT t.t_id, s.subject_id
FROM teachers AS t
INNER JOIN subjects AS s ON s.subject_name = 'Business Communication' AND s.course = 'BBA' AND s.semester = '1'
WHERE t.t_name = 'Sushil Ghimire';
INSERT IGNORE INTO teacher_subjects (t_id, subject_id)
SELECT t.t_id, s.subject_id
FROM teachers AS t
INNER JOIN subjects AS s ON s.subject_name = 'Database Systems' AND s.course = 'BCA' AND s.semester = '2'
WHERE t.t_name = 'Sushil Khadka';

INSERT IGNORE INTO students (s_course, enrollment_year, s_email, password_hash)
VALUES ('BCA', YEAR(CURDATE()) - 1, 'demo.student@kmcen.edu.np', '$2y$10$ivjalL5F9ZSHEDyAlhhdouD/1r2JBW1tbSbExJ8wL.WJMIGfNI/Ka');

INSERT INTO ratings
    (quality_rating, difficulty_rating, take_again, review, review_date, status, semester, s_id, t_id, subject_id)
SELECT 5, 3, 'yes', 'Clear explanations and helpful examples throughout the course.', '2026-01-12', 'Approved', '1',
    (SELECT s_id FROM students WHERE s_email = 'demo.student@kmcen.edu.np'),
       (SELECT t_id FROM teachers WHERE t_name = 'Sushil Ghimire'),
    (SELECT ts.subject_id FROM teacher_subjects AS ts INNER JOIN subjects AS s ON s.subject_id = ts.subject_id WHERE ts.t_id = (SELECT t_id FROM teachers WHERE t_name = 'Sushil Ghimire') AND s.subject_name = 'Object-Oriented Programming' AND s.course = 'BCA' AND s.semester = '1')
WHERE NOT EXISTS (
    SELECT 1 FROM ratings r
    INNER JOIN teachers t ON t.t_id = r.t_id
    WHERE t.t_name = 'Sushil Ghimire' AND r.review = 'Clear explanations and helpful examples throughout the course.'
);

INSERT INTO ratings
    (quality_rating, difficulty_rating, take_again, review, review_date, status, semester, s_id, t_id, subject_id)
SELECT 4, 4, 'yes', 'Well-organized classes with challenging but fair assignments.', '2026-02-18', 'Approved', '2',
    (SELECT s_id FROM students WHERE s_email = 'demo.student@kmcen.edu.np'),
       (SELECT t_id FROM teachers WHERE t_name = 'Sushil Ghimire'),
    (SELECT ts.subject_id FROM teacher_subjects AS ts INNER JOIN subjects AS s ON s.subject_id = ts.subject_id WHERE ts.t_id = (SELECT t_id FROM teachers WHERE t_name = 'Sushil Ghimire') AND s.subject_name = 'Web Technology' AND s.course = 'BCA' AND s.semester = '2')
WHERE NOT EXISTS (
    SELECT 1 FROM ratings r
    INNER JOIN teachers t ON t.t_id = r.t_id
    WHERE t.t_name = 'Sushil Ghimire' AND r.review = 'Well-organized classes with challenging but fair assignments.'
);

INSERT INTO ratings
    (quality_rating, difficulty_rating, take_again, review, review_date, status, semester, s_id, t_id, subject_id)
SELECT 4, 3, 'yes', 'Good lectures and practical exercises.', '2026-03-04', 'Approved', '3',
    (SELECT s_id FROM students WHERE s_email = 'demo.student@kmcen.edu.np'),
       (SELECT t_id FROM teachers WHERE t_name = 'Rajit Bhusal'),
    (SELECT ts.subject_id FROM teacher_subjects AS ts INNER JOIN subjects AS s ON s.subject_id = ts.subject_id WHERE ts.t_id = (SELECT t_id FROM teachers WHERE t_name = 'Rajit Bhusal') AND s.subject_name = 'Data Structures and Algorithms' AND s.course = 'BCA' AND s.semester = '3')
WHERE NOT EXISTS (
    SELECT 1 FROM ratings r
    INNER JOIN teachers t ON t.t_id = r.t_id
    WHERE t.t_name = 'Rajit Bhusal' AND r.review = 'Good lectures and practical exercises.'
);

INSERT INTO ratings
    (quality_rating, difficulty_rating, take_again, review, review_date, status, semester, s_id, t_id, subject_id)
SELECT 3, 5, 'no', 'The workload is heavy, so plan time for every assignment.', '2026-03-22', 'Approved', '4',
    (SELECT s_id FROM students WHERE s_email = 'demo.student@kmcen.edu.np'),
       (SELECT t_id FROM teachers WHERE t_name = 'Rajit Bhusal'),
    (SELECT ts.subject_id FROM teacher_subjects AS ts INNER JOIN subjects AS s ON s.subject_id = ts.subject_id WHERE ts.t_id = (SELECT t_id FROM teachers WHERE t_name = 'Rajit Bhusal') AND s.subject_name = 'Introduction to Artificial Intelligence' AND s.course = 'BCA' AND s.semester = '4')
WHERE NOT EXISTS (
    SELECT 1 FROM ratings r
    INNER JOIN teachers t ON t.t_id = r.t_id
    WHERE t.t_name = 'Rajit Bhusal' AND r.review = 'The workload is heavy, so plan time for every assignment.'
);

INSERT INTO ratings
    (quality_rating, difficulty_rating, take_again, review, review_date, status, semester, s_id, t_id, subject_id)
SELECT 5, 2, 'yes', 'Very approachable and excellent at breaking down difficult topics.', '2026-04-12', 'Approved', '2',
    (SELECT s_id FROM students WHERE s_email = 'demo.student@kmcen.edu.np'),
       (SELECT t_id FROM teachers WHERE t_name = 'Sushil Khadka'),
    (SELECT ts.subject_id FROM teacher_subjects AS ts INNER JOIN subjects AS s ON s.subject_id = ts.subject_id WHERE ts.t_id = (SELECT t_id FROM teachers WHERE t_name = 'Sushil Khadka') AND s.subject_name = 'Database Systems' AND s.course = 'BCA' AND s.semester = '2')
WHERE NOT EXISTS (
    SELECT 1 FROM ratings r
    INNER JOIN teachers t ON t.t_id = r.t_id
    WHERE t.t_name = 'Sushil Khadka' AND r.review = 'Very approachable and excellent at breaking down difficult topics.'
);

INSERT INTO ratings
    (quality_rating, difficulty_rating, take_again, review, review_date, status, semester, s_id, t_id, subject_id)
SELECT 4, 4, 'yes', 'Useful examples and a practical approach to algorithms.', '2026-04-20', 'Approved', '1',
    (SELECT s_id FROM students WHERE s_email = 'demo.student@kmcen.edu.np'),
       (SELECT t_id FROM teachers WHERE t_name = 'Bishal Lama'),
    (SELECT ts.subject_id FROM teacher_subjects AS ts INNER JOIN subjects AS s ON s.subject_id = ts.subject_id WHERE ts.t_id = (SELECT t_id FROM teachers WHERE t_name = 'Bishal Lama') AND s.subject_name = 'Business Communication' AND s.course = 'BBA' AND s.semester = '1')
WHERE NOT EXISTS (
    SELECT 1 FROM ratings r
    INNER JOIN teachers t ON t.t_id = r.t_id
    WHERE t.t_name = 'Bishal Lama' AND r.review = 'Useful examples and a practical approach to algorithms.'
);

INSERT INTO ratings
    (quality_rating, difficulty_rating, take_again, review, review_date, status, semester, s_id, t_id, subject_id)
SELECT 5, 2, 'yes', 'This pending review is intentionally hidden from public profiles.', '2026-04-25', 'Pending', '1',
    (SELECT s_id FROM students WHERE s_email = 'demo.student@kmcen.edu.np'),
       (SELECT t_id FROM teachers WHERE t_name = 'Sushil Khadka'),
    (SELECT ts.subject_id FROM teacher_subjects AS ts INNER JOIN subjects AS s ON s.subject_id = ts.subject_id WHERE ts.t_id = (SELECT t_id FROM teachers WHERE t_name = 'Sushil Khadka') AND s.subject_name = 'Database Systems' AND s.course = 'BCA' AND s.semester = '2')
WHERE NOT EXISTS (
    SELECT 1 FROM ratings r
    INNER JOIN teachers t ON t.t_id = r.t_id
    WHERE t.t_name = 'Sushil Khadka' AND r.review = 'This pending review is intentionally hidden from public profiles.'
);