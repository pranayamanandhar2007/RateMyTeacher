USE RateMyTeacher;

-- Run once for an existing database created from the previous schema.
ALTER TABLE students
    DROP COLUMN s_name;
