USE RateMyTeacher;

INSERT IGNORE INTO teacher_subjects (t_id, subject_id)
SELECT t.t_id, s.subject_id
FROM teachers AS t
INNER JOIN subjects AS s ON s.subject_name = 'Database Systems' AND s.course = 'BCA' AND s.semester = '2'
WHERE t.t_name = 'Sushil Khadka';

INSERT IGNORE INTO teacher_subjects (t_id, subject_id)
SELECT t.t_id, s.subject_id
FROM teachers AS t
INNER JOIN subjects AS s ON s.subject_name = 'Business Communication' AND s.course = 'BBA' AND s.semester = '1'
WHERE t.t_name = 'Bishal Lama';
