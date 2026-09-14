<?php
session_start();
require_once __DIR__ . '/../config/dbconn.php';
require_once __DIR__ . '/../includes/review_actions.php';

if (empty($_SESSION['review_csrf'])) {
  $_SESSION['review_csrf'] = bin2hex(random_bytes(32));
}
$reviewCsrf = $_SESSION['review_csrf'];

$teacherId = filter_input(INPUT_GET, 't_id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if (!$teacherId) {
  http_response_code(400);
  exit('A valid teacher ID is required.');
}

$teacherStatement = mysqli_prepare($conn, 'SELECT t_id, t_name FROM teachers WHERE t_id = ?');
mysqli_stmt_bind_param($teacherStatement, 'i', $teacherId);
mysqli_stmt_execute($teacherStatement);
$teacher = mysqli_fetch_assoc(mysqli_stmt_get_result($teacherStatement));
mysqli_stmt_close($teacherStatement);

if (!$teacher) {
  http_response_code(404);
  exit('Teacher not found.');
}

$studentId = $_SESSION['s_id'] ?? null;
$actionMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $actionResult = handleReviewAction($conn, $teacherId, $studentId, $reviewCsrf);
  $actionMessage = $actionResult['success'] ?? $actionResult['error'] ?? '';
}

$subjectFilter = filter_input(INPUT_GET, 'subject_id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ?: null;
$subjectOptionsStatement = mysqli_prepare($conn, "
  SELECT s.subject_id, s.subject_name, s.course, s.semester
  FROM subjects AS s
  INNER JOIN teacher_subjects AS ts ON ts.subject_id = s.subject_id
  WHERE ts.t_id = ?
  ORDER BY s.semester, s.subject_name
");
mysqli_stmt_bind_param($subjectOptionsStatement, 'i', $teacherId);
mysqli_stmt_execute($subjectOptionsStatement);
$subjectOptionsResult = mysqli_stmt_get_result($subjectOptionsStatement);
$subjectOptions = [];
while ($subjectOption = mysqli_fetch_assoc($subjectOptionsResult)) {
  $subjectOptions[] = $subjectOption;
}
mysqli_stmt_close($subjectOptionsStatement);
$validSubjectIds = array_map(static fn ($subject) => (int) $subject['subject_id'], $subjectOptions);
if ($subjectFilter !== null && !in_array($subjectFilter, $validSubjectIds, true)) {
  $subjectFilter = null;
}

$reviewSort = $_GET['review_sort'] ?? 'recent';
if (!in_array($reviewSort, ['recent', 'highest', 'lowest', 'helpful'], true)) {
  $reviewSort = 'recent';
}
$reviewPage = max(1, (int) ($_GET['reviews_page'] ?? 1));
$reviewsPerPage = 5;

$reviewCountSql = 'SELECT COUNT(*) AS total FROM ratings WHERE t_id = ? AND status = \'Approved\'';
$reviewCountTypes = 'i';
$reviewCountParams = [$teacherId];
if ($subjectFilter !== null) {
  $reviewCountSql .= ' AND subject_id = ?';
  $reviewCountTypes .= 'i';
  $reviewCountParams[] = $subjectFilter;
}
$reviewCountStatement = mysqli_prepare($conn, $reviewCountSql);
$reviewCountBindValues = [$reviewCountStatement, $reviewCountTypes];
foreach ($reviewCountParams as $key => $value) {
  $reviewCountBindValues[] = &$reviewCountParams[$key];
}
call_user_func_array('mysqli_stmt_bind_param', $reviewCountBindValues);
mysqli_stmt_execute($reviewCountStatement);
$totalReviewCount = (int) (mysqli_fetch_assoc(mysqli_stmt_get_result($reviewCountStatement))['total'] ?? 0);
mysqli_stmt_close($reviewCountStatement);
$totalReviewPages = max(1, (int) ceil($totalReviewCount / $reviewsPerPage));
$reviewPage = min($reviewPage, $totalReviewPages);
$reviewOffset = ($reviewPage - 1) * $reviewsPerPage;

$summaryStatement = mysqli_prepare($conn, "
  SELECT
    AVG(quality_rating) AS average_quality,
    AVG(difficulty_rating) AS average_difficulty,
    COUNT(*) AS total_reviews,
    COALESCE(SUM(take_again = 'yes') / NULLIF(COUNT(*), 0) * 100, 0) AS take_again_percentage
  FROM ratings
  WHERE t_id = ? AND status = 'Approved'
");
mysqli_stmt_bind_param($summaryStatement, 'i', $teacherId);
mysqli_stmt_execute($summaryStatement);
$summary = mysqli_fetch_assoc(mysqli_stmt_get_result($summaryStatement));
mysqli_stmt_close($summaryStatement);

$breakdown = array_fill(1, 5, 0);
$breakdownStatement = mysqli_prepare($conn, "
  SELECT quality_rating, COUNT(*) AS rating_count
  FROM ratings
  WHERE t_id = ? AND status = 'Approved'
  GROUP BY quality_rating
");
mysqli_stmt_bind_param($breakdownStatement, 'i', $teacherId);
$breakdownResult = null;
mysqli_stmt_execute($breakdownStatement);
$breakdownResult = mysqli_stmt_get_result($breakdownStatement);
while ($row = mysqli_fetch_assoc($breakdownResult)) {
  $rating = (int) $row['quality_rating'];
  if ($rating >= 1 && $rating <= 5) {
    $breakdown[$rating] = (int) $row['rating_count'];
  }
}
mysqli_stmt_close($breakdownStatement);

$reviewSql = "
  SELECT r.r_id, r.quality_rating, r.review, r.review_date, s.subject_id, s.subject_name,
         COUNT(DISTINCT hv.s_id) AS helpful_count,
         MAX(CASE WHEN hv.s_id = ? THEN 1 ELSE 0 END) AS helpful_by_current_student
  FROM ratings AS r
  INNER JOIN subjects AS s ON s.subject_id = r.subject_id
  LEFT JOIN rating_helpful_votes AS hv ON hv.r_id = r.r_id
  WHERE r.t_id = ? AND r.status = 'Approved'";
$reviewTypes = 'ii';
$reviewParams = [$studentId ?? 0, $teacherId];
if ($subjectFilter !== null) {
  $reviewSql .= ' AND r.subject_id = ?';
  $reviewTypes .= 'i';
  $reviewParams[] = $subjectFilter;
}
$reviewOrder = match ($reviewSort) {
  'highest' => 'r.quality_rating DESC, r.review_date DESC, r.r_id DESC',
  'lowest' => 'r.quality_rating ASC, r.review_date DESC, r.r_id DESC',
  'helpful' => 'helpful_count DESC, r.review_date DESC, r.r_id DESC',
  default => 'r.review_date DESC, r.r_id DESC',
};
$reviewSql .= " GROUP BY r.r_id, r.quality_rating, r.review, r.review_date, s.subject_id, s.subject_name ORDER BY $reviewOrder LIMIT $reviewsPerPage OFFSET $reviewOffset";
$reviewStatement = mysqli_prepare($conn, $reviewSql);
$reviewBindValues = [$reviewStatement, $reviewTypes];
foreach ($reviewParams as $key => $value) {
  $reviewBindValues[] = &$reviewParams[$key];
}
call_user_func_array('mysqli_stmt_bind_param', $reviewBindValues);
$reviewResult = null;
mysqli_stmt_execute($reviewStatement);
$reviewResult = mysqli_stmt_get_result($reviewStatement);
$reviews = [];
while ($row = mysqli_fetch_assoc($reviewResult)) {
  $reviews[] = $row;
}
mysqli_stmt_close($reviewStatement);

$courseStatement = mysqli_prepare($conn, "
  SELECT course FROM subjects AS s
  INNER JOIN ratings AS r ON r.subject_id = s.subject_id
  WHERE r.t_id = ? AND r.status = 'Approved'
  GROUP BY s.course
  ORDER BY COUNT(*) DESC
  LIMIT 1
");
mysqli_stmt_bind_param($courseStatement, 'i', $teacherId);
$courseResult = null;
mysqli_stmt_execute($courseStatement);
$courseResult = mysqli_stmt_get_result($courseStatement);
$course = mysqli_fetch_assoc($courseResult)['course'] ?? 'All courses';
mysqli_stmt_close($courseStatement);

$averageQuality = $summary['average_quality'] !== null ? number_format((float) $summary['average_quality'], 1) : '0.0';
$averageDifficulty = $summary['average_difficulty'] !== null ? number_format((float) $summary['average_difficulty'], 1) : '0.0';
$takeAgainPercentage = (int) round((float) ($summary['take_again_percentage'] ?? 0));
$totalReviews = (int) ($summary['total_reviews'] ?? 0);
$reviewQuery = $subjectFilter !== null ? '&subject_id=' . $subjectFilter : '';
$reviewPageQuery = $reviewQuery . '&review_sort=' . urlencode($reviewSort);

function reviewPageUrl(int $teacherId, int $page, string $subjectQuery, string $sort): string
{
  return 'professor-profile.php?t_id=' . $teacherId . $subjectQuery . '&review_sort=' . urlencode($sort) . '&reviews_page=' . $page;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($teacher['t_name']) ?> · Rate My Teacher</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/professor-profile.css">
<!-- Bootstrap Icons (used as stand-ins for the Figma icon assets, which couldn't be downloaded in this environment) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<!-- Google Fonts: Manrope (headings) + Inter (body) to match the Figma type styles -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@700;800&display=swap" rel="stylesheet">

</head>
<body>

<?php $basePath = '../'; include __DIR__ . '/../includes/navbar.php'; ?>

<!-- ============ MAIN ============ -->
<main class="container" style="max-width: 896px;">
  <div class="pt-5 pb-5">

    <a href="search.php" class="back-link mb-4 d-inline-flex align-items-center gap-2">
      <i class="bi bi-arrow-left"></i> Back to Search
    </a>

    <!-- Professor header -->
    <section class="pb-4 mb-2">
      <p class="eyebrow mb-3">Course: <?= htmlspecialchars($course) ?></p>
      <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
        <h1 class="prof-name"><?= htmlspecialchars($teacher['t_name']) ?></h1>
        <div class="d-flex align-items-center gap-3">
          <div class="rating-card">
            <div class="score" id="overall-score"><?= $averageQuality ?></div>
            <div class="label">Overall</div>
          </div>
          <a href="rating.php?t_id=<?= (int) $teacher['t_id'] ?>" class="btn btn-brand px-4 py-3">Rate</a>
        </div>
      </div>
    </section>

    <!-- Stats bar -->
    <section class="stats-bar">
      <div class="row text-center text-md-start">
        <div class="col-12 col-md-4 stat"><?= $takeAgainPercentage ?>% Would take again</div>
        <div class="col-12 col-md-4 stat"><?= $averageDifficulty ?> Difficulty</div>
        <div class="col-12 col-md-4 stat"><?= $totalReviews ?> Total Reviews</div>
      </div>
    </section>

    <!-- Ratings breakdown -->
    <section class="pt-5">
      <div class="breakdown-card">
        <p class="breakdown-title mb-0">Rating Breakdown</p>
        <div id="breakdownRows" data-counts="5:<?= $breakdown[5] ?>,4:<?= $breakdown[4] ?>,3:<?= $breakdown[3] ?>,2:<?= $breakdown[2] ?>,1:<?= $breakdown[1] ?>">
          <!-- rows are rendered by JS from data-counts -->
        </div>
      </div>
    </section>

    <!-- Reviews -->
    <section class="pt-5">
      <?php if ($actionMessage && (($_POST['review_action'] ?? '') === 'report' || str_contains($actionMessage, 'could not') || str_contains($actionMessage, 'available'))): ?><div class="alert alert-info" role="alert"><?= htmlspecialchars($actionMessage) ?></div><?php endif; ?>
      <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <h2 class="section-title mb-0">Student Reviews</h2>
        <div class="d-flex align-items-center gap-2">
          <form method="get" action="professor-profile.php" class="d-flex align-items-center gap-2">
            <input type="hidden" name="t_id" value="<?= (int) $teacher['t_id'] ?>">
            <label class="sort-label mb-0" for="subjectFilter">Subject:</label>
            <select class="sort-select form-select-sm" id="subjectFilter" name="subject_id" onchange="this.form.submit()">
              <option value="">All subjects</option>
              <?php foreach ($subjectOptions as $subjectOption): ?>
                <option value="<?= (int) $subjectOption['subject_id'] ?>" <?= $subjectFilter === (int) $subjectOption['subject_id'] ? 'selected' : '' ?>><?= htmlspecialchars($subjectOption['subject_name']) ?></option>
              <?php endforeach; ?>
            </select>
          </form>
          <span class="sort-label">Sort:</span>
          <form method="get" action="professor-profile.php" class="d-flex">
            <input type="hidden" name="t_id" value="<?= (int) $teacher['t_id'] ?>">
            <?php if ($subjectFilter !== null): ?><input type="hidden" name="subject_id" value="<?= (int) $subjectFilter ?>"><?php endif; ?>
            <input type="hidden" name="reviews_page" value="1">
            <select class="sort-select form-select-sm" name="review_sort" id="sortSelect" aria-label="Sort reviews" onchange="this.form.submit()">
              <option value="recent" <?= $reviewSort === 'recent' ? 'selected' : '' ?>>Recent</option>
              <option value="highest" <?= $reviewSort === 'highest' ? 'selected' : '' ?>>Highest Rated</option>
              <option value="lowest" <?= $reviewSort === 'lowest' ? 'selected' : '' ?>>Lowest Rated</option>
              <option value="helpful" <?= $reviewSort === 'helpful' ? 'selected' : '' ?>>Most Helpful</option>
            </select>
          </form>
        </div>
      </div>

      <div class="d-flex flex-column gap-4" id="reviewsList">
        <?php if (!$reviews): ?>
          <p class="text-muted">No public reviews yet.</p>
        <?php else: ?>
          <?php foreach ($reviews as $review): ?>
            <article class="review-card" data-rating="<?= (int) $review['quality_rating'] ?>" data-helpful="<?= (int) $review['helpful_count'] ?>" data-date="<?= htmlspecialchars($review['review_date']) ?>">
              <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                <div>
                  <h3 class="review-course"><?= htmlspecialchars($review['subject_name']) ?></h3>
                  <p class="review-date mb-0"><?= htmlspecialchars(date('M d, Y', strtotime($review['review_date']))) ?></p>
                </div>
                <span class="score-pill"><?= (int) $review['quality_rating'] ?>.0 / 5.0</span>
              </div>
              <p class="review-body mb-3"><?= nl2br(htmlspecialchars($review['review'] ?? '')) ?></p>
              <div class="d-flex align-items-center justify-content-between review-footer">
                <form method="post" action="professor-profile.php?t_id=<?= (int) $teacher['t_id'] ?><?= $reviewQuery ?>">
                  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($reviewCsrf) ?>">
                  <input type="hidden" name="r_id" value="<?= (int) $review['r_id'] ?>">
                  <input type="hidden" name="review_action" value="helpful">
                  <button type="submit" class="helpful-btn <?= $review['helpful_by_current_student'] ? 'active' : '' ?> d-flex align-items-center gap-1">
                    <i class="bi bi-hand-thumbs-up"></i>
                    <span>Helpful (<?= (int) $review['helpful_count'] ?>)</span>
                  </button>
                </form>
                <form method="post" action="professor-profile.php?t_id=<?= (int) $teacher['t_id'] ?><?= $reviewQuery ?>" class="report-form">
                  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($reviewCsrf) ?>">
                  <input type="hidden" name="r_id" value="<?= (int) $review['r_id'] ?>">
                  <input type="hidden" name="review_action" value="report">
                  <input type="hidden" name="report_reason" value="">
                  <button type="submit" class="report-btn">Report</button>
                </form>
              </div>
            </article>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <?php if ($totalReviewPages > 1): ?>
        <nav class="review-pagination" aria-label="Review pages">
          <?php if ($reviewPage > 1): ?>
            <a href="<?= htmlspecialchars(reviewPageUrl((int) $teacher['t_id'], $reviewPage - 1, $reviewQuery, $reviewSort)) ?>">Previous</a>
          <?php endif; ?>
          <?php for ($pageNumber = 1; $pageNumber <= $totalReviewPages; $pageNumber++): ?>
            <a href="<?= htmlspecialchars(reviewPageUrl((int) $teacher['t_id'], $pageNumber, $reviewQuery, $reviewSort)) ?>" class="<?= $pageNumber === $reviewPage ? 'active' : '' ?>"><?= $pageNumber ?></a>
          <?php endfor; ?>
          <?php if ($reviewPage < $totalReviewPages): ?>
            <a href="<?= htmlspecialchars(reviewPageUrl((int) $teacher['t_id'], $reviewPage + 1, $reviewQuery, $reviewSort)) ?>">Next</a>
          <?php endif; ?>
        </nav>
      <?php endif; ?>

    </section>

  </div>
</main>

<?php $basePath = '../'; include __DIR__ . '/../includes/footer.php'; ?>

<!-- Bootstrap JS bundle (needed for the navbar) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  // ---- Ratings breakdown ----
  (function renderBreakdown(){
    const wrap = document.getElementById('breakdownRows');
    if (!wrap) return;

    // "5:24,4:10,3:5,2:2,1:1" -> [[5,24],[4,10],[3,5],[2,2],[1,1]]
    const counts = wrap.dataset.counts
      .split(',')
      .map(pair => pair.split(':').map(Number));

    const total = counts.reduce((sum, [, count]) => sum + count, 0) || 1;

    wrap.innerHTML = counts.map(([star, count]) => {
      const pct = Math.round((count / total) * 100);
      return `
        <div class="breakdown-row">
          <span class="breakdown-label">${star} <i class="bi bi-star-fill"></i></span>
          <div class="breakdown-track">
            <div class="breakdown-fill" data-width="${pct}"></div>
          </div>
          <span class="breakdown-count">${count} (${pct}%)</span>
        </div>`;
    }).join('');

    // Animate the bars in after paint
    requestAnimationFrame(() => {
      wrap.querySelectorAll('.breakdown-fill').forEach(fill => {
        fill.style.width = fill.dataset.width + '%';
      });
    });
  })();

  document.querySelectorAll('.report-form').forEach(form => {
    form.addEventListener('submit', (event) => {
      const reason = window.prompt('Why are you reporting this review?', 'Inappropriate or inaccurate content.');
      if (!reason || !reason.trim()) {
        event.preventDefault();
        return;
      }
      form.querySelector('[name="report_reason"]').value = reason.trim();
    });
  });

</script>

</body>
</html>