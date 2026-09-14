USE RateMyTeacher;

-- Run once for an existing database created from the previous schema.
ALTER TABLE students
    ADD COLUMN enrollment_year YEAR NULL AFTER s_course;

-- Best-effort conversion for legacy rows. Review these values if needed.
UPDATE students
SET enrollment_year = YEAR(CURDATE()) - FLOOR((CAST(s_semester AS UNSIGNED) - 1) / 2);

ALTER TABLE students
    MODIFY enrollment_year YEAR NOT NULL,
    DROP COLUMN s_semester;
