<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

const KMCEN_EMAIL_DOMAIN = 'kmcen.edu.np';

function isKmcenEmail(string $email): bool
{
    return (bool) filter_var($email, FILTER_VALIDATE_EMAIL)
        && strtolower(substr(strrchr($email, '@'), 1)) === KMCEN_EMAIL_DOMAIN;
}

function currentStudentEmail(mysqli $conn): ?string
{
    if (empty($_SESSION['s_id'])) {
        return null;
    }

    if (!empty($_SESSION['s_email'])) {
        return $_SESSION['s_email'];
    }

    $statement = mysqli_prepare($conn, 'SELECT s_email FROM students WHERE s_id = ?');
    $studentId = (int) $_SESSION['s_id'];
    mysqli_stmt_bind_param($statement, 'i', $studentId);
    mysqli_stmt_execute($statement);
    $student = mysqli_fetch_assoc(mysqli_stmt_get_result($statement));
    mysqli_stmt_close($statement);

    if (!$student || !isKmcenEmail($student['s_email'])) {
        unset($_SESSION['s_id'], $_SESSION['s_email']);
        return null;
    }

    $_SESSION['s_email'] = $student['s_email'];
    return $student['s_email'];
}
