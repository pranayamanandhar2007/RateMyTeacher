<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Rate Sushil Ghimire · Rate My Teacher</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons (used as a stand-in for the Figma icon assets, which couldn't be downloaded in this environment) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link rel="stylesheet" href="../css/rating.css">
<!-- Google Fonts: Manrope (headings) + Inter (body) to match the Figma type styles -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">


</head>
<body>

<!-- ============ HEADER ============ -->
<header class="site-header">
  <div class="container-xl">
    <div class="d-flex align-items-center justify-content-between py-3">
      <div class="d-flex align-items-center gap-4">
        <a href="professor-profile.html" class="brand-link">Rate My Teacher</a>
        <nav class="d-none d-md-flex gap-4">
          <a href="#" class="nav-link">Browse</a>
          <a href="#" class="nav-link">Top Lists</a>
          <a href="#" class="nav-link">About</a>
        </nav>
      </div>
      <div class="d-flex align-items-center gap-3">
        <button type="button" class="icon-btn" aria-label="Search">
          <i class="bi bi-search"></i>
        </button>
        <button type="button" class="btn btn-brand px-4 py-2">Sign In</button>
      </div>
    </div>
  </div>
</header>

<!-- ============ MAIN ============ -->
<main class="container" style="max-width: 896px;">
  <div class="pt-5 pb-5">

    <!-- Page header -->
    <section class="mb-4">
      <a href="professor-profile.php" class="back-link mb-2">
        <i class="bi bi-arrow-left"></i> Back to Profile
      </a>
      <h1 class="page-title mt-2 mb-2">Rate <span class="prof-name">Sushil Ghimire</span></h1>
      <p class="page-subtitle mb-0">Your feedback helps others make informed decisions. Please be honest and direct.</p>
    </section>

    <!-- Form -->
    <form id="rateForm" class="d-flex flex-column gap-4" novalidate>

      <div class="row g-4">
        <!-- Quality -->
        <div class="col-12 col-md-6">
          <fieldset class="rate-fieldset h-100">
            <legend class="rate-legend mb-2">Quality</legend>
            <p class="rate-hint mb-2">Overall teaching effectiveness</p>
            <div class="scale-group" data-group="quality" role="radiogroup" aria-label="Quality rating">
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
          <button type="button" class="yesno-pill" data-value="yes">Yes</button>
          <button type="button" class="yesno-pill" data-value="no">No</button>
        </div>
      </fieldset>

      <!-- Written review -->
      <fieldset class="rate-fieldset">
        <legend class="review-legend mb-3">Written Review</legend>
        <textarea
          id="reviewText"
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

      <div class="alert alert-success d-none" id="successAlert" role="alert">
        Thanks! Your rating was submitted (demo only — connect this form to your PHP/MySQL backend).
      </div>
      <div class="alert alert-danger d-none" id="errorAlert" role="alert">
        Please select a Quality and Difficulty rating, choose Yes or No, and write a short review before submitting.
      </div>

    </form>
  </div>
</main>

<!-- ============ FOOTER ============ -->
<footer class="site-footer">
  <div class="container-xl">
    <div class="d-flex flex-column flex-md-row justify-content-between gap-4">
      <div>
        <p class="footer-brand mb-3">Rate My Teacher</p>
        <p class="footer-copy mb-0">© 2026 Rate My Teacher Academic. All rights reserved.</p>
      </div>
      <div class="d-flex flex-wrap gap-5">
        <div>
          <p class="footer-heading">Platform</p>
          <ul class="footer-list">
            <li><a href="#">Browse</a></li>
            <li><a href="#">Top Lists</a></li>
            <li><a href="#">Schools</a></li>
          </ul>
        </div>
        <div>
          <p class="footer-heading">Legal</p>
          <ul class="footer-list">
            <li><a href="#">Privacy Policy</a></li>
            <li><a href="#">Terms of Service</a></li>
            <li><a href="#">Guidelines</a></li>
          </ul>
        </div>
        <div>
          <p class="footer-heading">Support</p>
          <ul class="footer-list">
            <li><a href="#">Help Center</a></li>
            <li><a href="#">Contact Us</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</footer>

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
  const successAlert = document.getElementById('successAlert');
  const errorAlert = document.getElementById('errorAlert');
  const submitBtn = document.getElementById('submitBtn');

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    successAlert.classList.add('d-none');
    errorAlert.classList.add('d-none');

    const reviewValue = reviewText.value.trim();
    const isValid = selections.quality && selections.difficulty && selections.takeAgain && reviewValue.length > 0;

    if (!isValid) {
      errorAlert.classList.remove('d-none');
      errorAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
      return;
    }

    const payload = {
      professor: 'Sushil ghimire',
      quality: Number(selections.quality),
      difficulty: Number(selections.difficulty),
      takeAgain: selections.takeAgain === 'yes',
      review: reviewValue
    };

    // Replace this with a real fetch() POST to your PHP/MySQL endpoint, e.g.:
    // fetch('/api/ratings.php', {
    //   method: 'POST',
    //   headers: { 'Content-Type': 'application/json' },
    //   body: JSON.stringify(payload)
    // }).then(...)
    console.log('Submitting rating payload:', payload);

    submitBtn.disabled = true;
    submitBtn.textContent = 'Submitted';
    successAlert.classList.remove('d-none');
    successAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
  });
</script>

</body>
</html>