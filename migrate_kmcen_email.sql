USE RateMyTeacher;

-- Update the bundled demo account if it still uses the old placeholder domain.
UPDATE students
SET s_email = 'demo.student@kmcen.edu.np'
WHERE s_email = 'demo.student@university.edu';

-- Existing non-demo accounts must be verified or migrated before adding a database
-- CHECK constraint. The PHP registration/login validation already enforces the domain.
SELECT s_id, s_email
FROM students
WHERE LOWER(s_email) NOT LIKE '%@kmcen.edu.np';
