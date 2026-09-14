USE RateMyTeacher;

CREATE TABLE teacher_subjects (
    t_id INT NOT NULL,
    subject_id INT NOT NULL,
    PRIMARY KEY (t_id, subject_id),
    FOREIGN KEY (t_id) REFERENCES teachers(t_id),
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id)
);

INSERT INTO teacher_subjects (t_id, subject_id)
SELECT t_id, subject_id
FROM subjects;

ALTER TABLE subjects
    DROP FOREIGN KEY fk_subject_teacher,
    DROP INDEX unique_teacher_subject,
    DROP COLUMN t_id;
