<?php
require_once __DIR__ . '/../config/dbconn.php';

$searchQuery = trim($_GET['q'] ?? '');
$courseFilter = trim($_GET['course'] ?? '');
$minRating = filter_input(INPUT_GET, 'min_rating', FILTER_VALIDATE_FLOAT);
$sort = $_GET['sort'] ?? 'highest';
$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 6;

$allowedCourses = ['BCA', 'BBA', 'BBM', 'BA/BBS'];
if (!in_array($courseFilter, $allowedCourses, true)) {
    $courseFilter = '';
}
if ($minRating === false || !in_array((string) $minRating, ['3', '4'], true)) {
    $minRating = null;
}
if (!in_array($sort, ['highest', 'lowest', 'reviews', 'name'], true)) {
    $sort = 'highest';
}

$baseSql = "
    FROM teachers AS t
    LEFT JOIN ratings AS r ON r.t_id = t.t_id AND r.status = 'Approved'
    LEFT JOIN subjects AS s ON s.subject_id = r.subject_id
";
$whereSql = [];
$havingSql = [];
$params = [];
$types = '';

if ($searchQuery !== '') {
    $whereSql[] = 't.t_name LIKE ?';
    $params[] = '%' . $searchQuery . '%';
    $types .= 's';
}
if ($courseFilter !== '') {
    $whereSql[] = 'EXISTS (SELECT 1 FROM teacher_subjects AS filter_assignment INNER JOIN subjects AS filter_subject ON filter_subject.subject_id = filter_assignment.subject_id WHERE filter_assignment.t_id = t.t_id AND filter_subject.course = ?)';
    $params[] = $courseFilter;
    $types .= 's';
}
if ($minRating !== null) {
    $havingSql[] = 'COALESCE(AVG(r.quality_rating), 0) >= ?';
    $params[] = $minRating;
    $types .= 'd';
}
$whereClause = $whereSql ? ' WHERE ' . implode(' AND ', $whereSql) : '';
$havingClause = $havingSql ? ' HAVING ' . implode(' AND ', $havingSql) : '';
$groupedSql = "
    SELECT
        t.t_id,
        t.t_name,
        COALESCE(AVG(r.quality_rating), 0) AS average_quality,
        COALESCE(AVG(r.difficulty_rating), 0) AS average_difficulty,
        COUNT(r.r_id) AS total_reviews,
        COALESCE(SUM(r.take_again = 'yes') / NULLIF(COUNT(r.r_id), 0) * 100, 0) AS take_again_percentage,
        COALESCE((
            SELECT GROUP_CONCAT(DISTINCT assigned_subject.course ORDER BY FIELD(assigned_subject.course, 'BCA', 'BBA', 'BBM', 'BA/BBS') SEPARATOR ' | ')
            FROM teacher_subjects AS assignment
            INNER JOIN subjects AS assigned_subject ON assigned_subject.subject_id = assignment.subject_id
            WHERE assignment.t_id = t.t_id
        ), 'All courses') AS course
    $baseSql
    $whereClause
    GROUP BY t.t_id, t.t_name
    $havingClause
";

function executeSearchStatement(mysqli $conn, string $sql, string $types, array $params): mysqli_result
{
    $statement = mysqli_prepare($conn, $sql);
    if ($types !== '') {
        $bindValues = [$statement, $types];
        foreach ($params as $key => $value) {
            $bindValues[] = &$params[$key];
        }
        call_user_func_array('mysqli_stmt_bind_param', $bindValues);
    }
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    mysqli_stmt_close($statement);
    return $result;
}

$countResult = executeSearchStatement($conn, "SELECT COUNT(*) AS total FROM ($groupedSql) AS filtered_teachers", $types, $params);
$totalTeachers = (int) (mysqli_fetch_assoc($countResult)['total'] ?? 0);
$totalPages = max(1, (int) ceil($totalTeachers / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;

$orderBy = match ($sort) {
    'lowest' => 'average_quality ASC, t_name ASC',
    'reviews' => 'total_reviews DESC, t_name ASC',
    'name' => 't_name ASC',
    default => 'average_quality DESC, t_name ASC',
};

$teachersResult = executeSearchStatement($conn, "$groupedSql ORDER BY $orderBy LIMIT $perPage OFFSET $offset", $types, $params);
$teachers = [];
while ($teacher = mysqli_fetch_assoc($teachersResult)) {
    $teachers[] = $teacher;
}

$searchParameters = [
    'q' => $searchQuery,
    'course' => $courseFilter,
    'min_rating' => $minRating !== null ? (string) $minRating : '',
    'sort' => $sort,
];
