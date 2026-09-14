<?php
function submitRating(mysqli $conn, int $teacherId, ?int $studentId, ?string $studentCourse, ?int $studentSemester): array
{
    if (!$studentId) {
        return ['error' => 'Please sign in as a student before submitting a rating.'];
    }

    $quality = filter_input(INPUT_POST, 'quality_rating', FILTER_VALIDATE_INT);
    $difficulty = filter_input(INPUT_POST, 'difficulty_rating', FILTER_VALIDATE_INT);
    $subjectId = filter_input(INPUT_POST, 'subject_id', FILTER_VALIDATE_INT);
    $takeAgain = $_POST['take_again'] ?? '';
    $review = trim($_POST['review'] ?? '');

    if (!$quality || $quality < 1 || $quality > 5 || !$difficulty || $difficulty < 1 || $difficulty > 5) {
        return ['error' => 'Quality and difficulty must be between 1 and 5.'];
    }
    if (!$subjectId || !$studentCourse || !$studentSemester || !in_array($takeAgain, ['yes', 'no'], true) || $review === '') {
        return ['error' => 'Please complete every rating field.'];
    }
    if (mb_strlen($review) > 1000) {
        return ['error' => 'Your review must be 1000 characters or fewer.'];
    }

    $subjectCheck = mysqli_prepare($conn, 'SELECT s.subject_id, s.semester FROM subjects AS s INNER JOIN teacher_subjects AS ts ON ts.subject_id = s.subject_id WHERE s.subject_id = ? AND ts.t_id = ? AND s.course = ? AND CAST(s.semester AS UNSIGNED) <= ?');
    mysqli_stmt_bind_param($subjectCheck, 'iisi', $subjectId, $teacherId, $studentCourse, $studentSemester);
    mysqli_stmt_execute($subjectCheck);
    $subjectResult = mysqli_stmt_get_result($subjectCheck);
    $subject = mysqli_fetch_assoc($subjectResult);
    mysqli_stmt_close($subjectCheck);

    if (!$subject) {
        return ['error' => 'The selected subject is not assigned to this teacher.'];
    }

    $semester = $subject['semester'];

    $insert = mysqli_prepare($conn, "
        INSERT INTO ratings
          (quality_rating, difficulty_rating, take_again, review, review_date, status, semester, s_id, t_id, subject_id)
        VALUES (?, ?, ?, ?, CURDATE(), 'Approved', ?, ?, ?, ?)
    ");
    mysqli_stmt_bind_param($insert, 'iisssiii', $quality, $difficulty, $takeAgain, $review, $semester, $studentId, $teacherId, $subjectId);
    $saved = mysqli_stmt_execute($insert);
    mysqli_stmt_close($insert);

    return $saved
        ? ['success' => 'Your rating was submitted successfully.']
        : ['error' => 'The rating could not be saved. Please try again.'];
}
