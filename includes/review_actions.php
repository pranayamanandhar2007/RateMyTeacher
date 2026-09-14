<?php
function handleReviewAction(mysqli $conn, int $teacherId, ?int $studentId, string $csrfToken): array
{
    if (!$studentId) {
        return ['error' => 'Please sign in before using review actions.'];
    }
    if (!hash_equals($csrfToken, (string) ($_POST['csrf_token'] ?? ''))) {
        return ['error' => 'This action could not be verified. Refresh the page and try again.'];
    }

    $reviewId = filter_input(INPUT_POST, 'r_id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    $action = $_POST['review_action'] ?? '';
    if (!$reviewId || !in_array($action, ['helpful', 'report'], true)) {
        return ['error' => 'Invalid review action.'];
    }

    $reviewCheck = mysqli_prepare($conn, "
        SELECT r_id FROM ratings
        WHERE r_id = ? AND t_id = ? AND status = 'Approved'
    ");
    mysqli_stmt_bind_param($reviewCheck, 'ii', $reviewId, $teacherId);
    mysqli_stmt_execute($reviewCheck);
    $reviewExists = mysqli_stmt_get_result($reviewCheck)->num_rows === 1;
    mysqli_stmt_close($reviewCheck);
    if (!$reviewExists) {
        return ['error' => 'That review is not available.'];
    }

    if ($action === 'helpful') {
        $voteCheck = mysqli_prepare($conn, 'SELECT 1 FROM rating_helpful_votes WHERE r_id = ? AND s_id = ?');
        mysqli_stmt_bind_param($voteCheck, 'ii', $reviewId, $studentId);
        mysqli_stmt_execute($voteCheck);
        $hasVoted = mysqli_stmt_get_result($voteCheck)->num_rows === 1;
        mysqli_stmt_close($voteCheck);

        if ($hasVoted) {
            $voteAction = mysqli_prepare($conn, 'DELETE FROM rating_helpful_votes WHERE r_id = ? AND s_id = ?');
        } else {
            $voteAction = mysqli_prepare($conn, 'INSERT INTO rating_helpful_votes (r_id, s_id) VALUES (?, ?)');
        }
        mysqli_stmt_bind_param($voteAction, 'ii', $reviewId, $studentId);
        mysqli_stmt_execute($voteAction);
        mysqli_stmt_close($voteAction);
        return ['success' => $hasVoted ? 'Helpful vote removed.' : 'Marked as helpful.'];
    }

    $reason = trim($_POST['report_reason'] ?? 'Inappropriate or inaccurate content.');
    $reason = mb_substr($reason, 0, 255);
    $report = mysqli_prepare($conn, "
        INSERT INTO rating_reports (r_id, s_id, reason)
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE reason = VALUES(reason)
    ");
    mysqli_stmt_bind_param($report, 'iis', $reviewId, $studentId, $reason);
    mysqli_stmt_execute($report);
    mysqli_stmt_close($report);
    return ['success' => 'Thanks. The review was reported for admin review.'];
}
