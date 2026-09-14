<?php
session_start();
require_once __DIR__ . '/../config/dbconn.php';
require_once __DIR__ . '/../includes/rating_submission.php';

$teacherId = filter_input(INPUT_GET, 't_id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if (!$teacherId) {
  http_response_code(400);
  exit('A valid teacher ID is required.');
}

$teacherStatement = mysqli_prepare($conn, 'SELECT t_id, t_name FROM teachers WHERE t_id = ?');
mysqli_stmt_bind_param($teacherStatement, 'i', $teacherId);
$teacherResult = null;
mysqli_stmt_execute($teacherStatement);
$teacherResult = mysqli_stmt_get_result($teacherStatement);
$teacher = mysqli_fetch_assoc($teacherResult);
mysqli_stmt_close($teacherStatement);

if (!$teacher) {
  http_response_code(404);
  exit('Teacher not found.');
}

$studentId = $_SESSION['s_id'] ?? null;
$studentCourse = null;
$studentSemester = null;
if ($studentId) {
  $studentStatement = mysqli_prepare($conn, 'SELECT s_course, enrollment_year FROM students WHERE s_id = ?');
  mysqli_stmt_bind_param($studentStatement, 'i', $studentId);
  mysqli_stmt_execute($studentStatement);
  $studentResult = mysqli_stmt_get_result($studentStatement);
  $student = mysqli_fetch_assoc($studentResult);
  mysqli_stmt_close($studentStatement);

  if ($student) {
    $studentCourse = $student['s_course'];
    $academicYearsCompleted = max(0, (int) date('Y') - (int) $student['enrollment_year']);
    $studentSemester = min(8, ($academicYearsCompleted * 2) + ((int) date('n') >= 7 ? 2 : 1));
  }
}

$subjectQuery = 'SELECT s.subject_id, s.subject_name, s.course, s.semester FROM subjects AS s INNER JOIN teacher_subjects AS ts ON ts.subject_id = s.subject_id WHERE ts.t_id = ? ORDER BY s.semester, s.subject_name';
if ($studentCourse && $studentSemester) {
  $subjectQuery = 'SELECT s.subject_id, s.subject_name, s.course, s.semester FROM subjects AS s INNER JOIN teacher_subjects AS ts ON ts.subject_id = s.subject_id WHERE ts.t_id = ? AND s.course = ? AND CAST(s.semester AS UNSIGNED) <= ? ORDER BY s.semester, s.subject_name';
}
$subjectStatement = mysqli_prepare($conn, $subjectQuery);
if ($studentCourse && $studentSemester) {
  mysqli_stmt_bind_param($subjectStatement, 'isi', $teacherId, $studentCourse, $studentSemester);
} else {
  mysqli_stmt_bind_param($subjectStatement, 'i', $teacherId);
}
mysqli_stmt_execute($subjectStatement);
$subjectResult = mysqli_stmt_get_result($subjectStatement);
$subjects = [];
while ($subject = mysqli_fetch_assoc($subjectResult)) {
  $subjects[] = $subject;
}
mysqli_stmt_close($subjectStatement);

$errorMessage = '';
$successMessage = $_SESSION['rating_success'] ?? '';
unset($_SESSION['rating_success']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $submission = submitRating($conn, $teacherId, $studentId, $studentCourse, $studentSemester);
  $errorMessage = $submission['error'] ?? '';
  $successMessage = $submission['success'] ?? '';
  if ($successMessage !== '') {
    $_SESSION['rating_success'] = $successMessage;
    header('Location: rating.php?t_id=' . $teacherId);
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Rate <?= htmlspecialchars($teacher['t_name']) ?> · Rate My Teacher</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons (used as a stand-in for the Figma icon assets, which couldn't be downloaded in this environment) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/rating.css">
<!-- Google Fonts: Manrope (headings) + Inter (body) to match the Figma type styles -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">


</head>
<body>

<?php $basePath = '../'; include __DIR__ . '/../includes/navbar.php'; ?>

<!-- ============ MAIN ============ -->
<main class="container" style="max-width: 896px;">
  <div class="pt-5 pb-5">

    <!-- Page header -->
    <section class="mb-4">
      <a href="professor-profile.php?t_id=<?= (int) $teacher['t_id'] ?>" class="back-link mb-2">
        <i class="bi bi-arrow-left"></i> Back to Profile
      </a>
      <h1 class="page-title mt-2 mb-2">Rate <span class="prof-name"><?= htmlspecialchars($teacher['t_name']) ?></span></h1>
      <p class="page-subtitle mb-0">Your feedback helps others make informed decisions. Please be honest and direct.</p>
    </section>

    <?php if (!$studentId): ?>
      <div class="alert alert-info" role="alert">
        Please <a href="login.php">sign in</a> to see the subjects available for your course and semester.
      </div>
    <?php elseif (!$subjects): ?>
      <div class="alert alert-warning" role="alert">This teacher has no subjects available for your course up to your current semester.</div>
    <?php else: ?>
    <!-- Form -->
    <form id="rateForm" class="d-flex flex-column gap-4" method="post" action="rating.php?t_id=<?= (int) $teacher['t_id'] ?>">

      <div class="row g-4">
        <!-- Quality -->
        <div class="col-12 col-md-6">
          <fieldset class="rate-fieldset h-100">
            <legend class="rate-legend mb-2">Quality</legend>
            <p class="rate-hint mb-2">Overall teaching effectiveness</p>
            <div class="scale-group" data-group="quality" role="radiogroup" aria-label="Quality rating">
              <input type="hidden" name="quality_rating" id="qualityRating">
              <button type="button" class="scale-pill" data-value="1">1</button>
              <button type="button" class="scale-pill" data-value="2">2</button>
              <button type="button" class="scale-pill" data-value="3">3</button>
              <button type="button" class="scale-pill" data-value="4">4</button>
              <button type="button" class="scale-pill" data-value="5">5</button>
            </div>
          </fieldset>
        </div>

        <!-- Difficulty -->
        <div class="col-12 col-md-6">
          <fieldset class="rate-fieldset h-100">
            <legend class="rate-legend mb-2">Difficulty</legend>
            <p class="rate-hint mb-2">Course workload and grading</p>
            <div class="scale-group" data-group="difficulty" role="radiogroup" aria-label="Difficulty rating">
              <input type="hidden" name="difficulty_rating" id="difficultyRating">
              <button type="button" class="scale-pill" data-value="1">1</button>
              <button type="button" class="scale-pill" data-value="2">2</button>
              <button type="button" class="scale-pill" data-value="3">3</button>
              <button type="button" class="scale-pill" data-value="4">4</button>
              <button type="button" class="scale-pill" data-value="5">5</button>
            </div>
          </fieldset>
        </div>
      </div>

      <!-- Take again -->
      <fieldset class="rate-fieldset">
        <legend class="rate-legend mb-3">Would you take this professor again?</legend>
        <div class="yesno-group" data-group="takeAgain" role="radiogroup" aria-label="Would you take this professor again?">
          <input type="hidden" name="take_again" id="takeAgain">
          <button type="button" class="yesno-pill" data-value="yes">Yes</button>
          <button type="button" class="yesno-pill" data-value="no">No</button>
        </div>
      </fieldset>

      <fieldset class="rate-fieldset">
        <legend class="rate-legend mb-3">Subject</legend>
        <select class="form-select" name="subject_id" id="subjectId" required>
          <option value="">Select the subject you took</option>
          <?php foreach ($subjects as $subject): ?>
            <option value="<?= (int) $subject['subject_id'] ?>">
              <?= htmlspecialchars($subject['subject_name']) ?> (<?= htmlspecialchars($subject['course']) ?>, <?= htmlspecialchars($subject['semester']) ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </fieldset>

      <!-- Written review -->
      <fieldset class="rate-fieldset">
        <legend class="review-legend mb-3">Written Review</legend>
        <textarea
          id="reviewText"
          name="review"
          class="review-textarea"
          placeholder="Describe your experience with this professor..."
          maxlength="1000"
        ></textarea>
        <div class="text-end mt-2">
          <span class="char-count"><span id="charCount">0</span> / 1000</span>
        </div>
      </fieldset>

      <!-- Submit -->
      <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-brand submit-btn" id="submitBtn">Submit Rating</button>
      </div>

      <?php if ($successMessage): ?><div class="alert alert-success" role="alert"><?= htmlspecialchars($successMessage) ?></div><?php endif; ?>
      <?php if ($errorMessage): ?><div class="alert alert-danger" role="alert"><?= htmlspecialchars($errorMessage) ?></div><?php endif; ?>

    </form>
    <?php endif; ?>
  </div>
</main>

<?php $basePath = '../'; include __DIR__ . '/../includes/footer.php'; ?>

<!-- Bootstrap JS bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  // ---- Selection state ----
  const selections = {
    quality: null,
    difficulty: null,
    takeAgain: null
  };

  // ---- Wire up scale pills (Quality / Difficulty) ----
  document.querySelectorAll('.scale-group').forEach(group => {
    const key = group.dataset.group;
    group.querySelectorAll('.scale-pill').forEach(pill => {
      pill.addEventListener('click', () => {
        group.querySelectorAll('.scale-pill').forEach(p => {
          p.classList.remove('selected');
          p.setAttribute('aria-checked', 'false');
        });
        pill.classList.add('selected');
        pill.setAttribute('aria-checked', 'true');
        selections[key] = pill.dataset.value;
        document.getElementById(key === 'quality' ? 'qualityRating' : 'difficultyRating').value = pill.dataset.value;
      });
    });
  });

  // ---- Wire up Yes/No pills ----
  document.querySelectorAll('.yesno-group').forEach(group => {
    const key = group.dataset.group;
    group.querySelectorAll('.yesno-pill').forEach(pill => {
      pill.addEventListener('click', () => {
        group.querySelectorAll('.yesno-pill').forEach(p => {
          p.classList.remove('selected');
          p.setAttribute('aria-checked', 'false');
        });
        pill.classList.add('selected');
        pill.setAttribute('aria-checked', 'true');
        selections[key] = pill.dataset.value;
        document.getElementById('takeAgain').value = pill.dataset.value;
      });
    });
  });

  // ---- Character counter ----
  const reviewText = document.getElementById('reviewText');
  const charCount = document.getElementById('charCount');
  reviewText.addEventListener('input', () => {
    charCount.textContent = reviewText.value.length;
  });

  // ---- Form submit ----
  const form = document.getElementById('rateForm');
  document.getElementById('rateForm').addEventListener('submit', (event) => {
    if (!selections.quality || !selections.difficulty || !selections.takeAgain || !document.getElementById('subjectId').value || !reviewText.value.trim()) {
      event.preventDefault();
      alert('Please complete all rating fields.');
    }
  });
</script>

</body>
</html>