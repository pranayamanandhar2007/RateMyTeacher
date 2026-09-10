<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sushil Ghimire · Rate My Teacher</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons (used as stand-ins for the Figma icon assets, which couldn't be downloaded in this environment) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<!-- Google Fonts: Manrope (headings) + Inter (body) to match the Figma type styles -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@700;800&display=swap" rel="stylesheet">


</head>
<body>

<!-- ============ HEADER ============ -->
<header class="site-header">
  <div class="container-xl">
    <div class="d-flex align-items-center justify-content-between py-3">
      <div class="d-flex align-items-center gap-4">
        <a href="#" class="brand-link">Rate My Teacher</a>
        <nav class="d-none d-md-flex gap-4">
          <a href="#" class="nav-link">Browse</a>
          <a href="#" class="nav-link">Top Lists</a>
        </nav>
      </div>
      <div class="d-flex align-items-center gap-3">
        <form class="search-wrap d-none d-sm-block" role="search" onsubmit="return false;">
          <i class="bi bi-search"></i>
          <input type="search" placeholder="Search professors..." aria-label="Search professors">
        </form>
        <button type="button" class="btn btn-brand px-3 py-2">Sign In</button>
      </div>
    </div>
  </div>
</header>

<!-- ============ MAIN ============ -->
<main class="container" style="max-width: 896px;">
  <div class="pt-5 pb-5">

    <!-- Professor header -->
    <section class="pb-4 mb-2">
      <p class="eyebrow mb-3">Course: BCA</p>
      <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
        <h1 class="prof-name">Sushil Ghimire</h1>
        <div class="d-flex align-items-center gap-3">
          <div class="rating-card">
            <div class="score" id="overall-score">4.8</div>
            <div class="label">Overall</div>
          </div>
          <button type="button" class="btn btn-brand px-4 py-3" data-bs-toggle="modal" data-bs-target="#rateModal">Rate</button>
        </div>
      </div>
    </section>

    <!-- Stats bar -->
    <section class="stats-bar">
      <div class="row text-center text-md-start">
        <div class="col-12 col-md-4 stat">92% Would take again</div>
        <div class="col-12 col-md-4 stat">3.2 Difficulty</div>
        <div class="col-12 col-md-4 stat">42 Total Reviews</div>
      </div>
    </section>

    <!-- Reviews -->
    <section class="pt-5">
      <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <h2 class="section-title mb-0">Student Reviews</h2>
        <div class="d-flex align-items-center gap-2">
          <span class="sort-label">Sort:</span>
          <select class="sort-select form-select-sm" id="sortSelect" aria-label="Sort reviews">
            <option value="recent" selected>Recent</option>
            <option value="highest">Highest Rated</option>
            <option value="lowest">Lowest Rated</option>
            <option value="helpful">Most Helpful</option>
          </select>
        </div>
      </div>

      <div class="d-flex flex-column gap-4" id="reviewsList">

        <!-- Review 1 -->
        <article class="review-card" data-rating="5.0" data-helpful="12" data-date="2024-01-12">
          <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
            <div>
              <h3 class="review-course">CACS204: OOP in Java</h3>
              <p class="review-date mb-0">Jan 12, 2024</p>
            </div>
            <span class="score-pill">5.0 / 5.0</span>
          </div>
          <div class="d-flex gap-2 review-meta mb-3">
            <span>Grade: <strong>A+</strong></span>
            <span>•</span>
            <span>For Credit: <strong>Yes</strong></span>
          </div>
          <p class="review-body mb-3">
            Sushil sir is incredible. His lectures are dense but he has a unique way of making abstract
            theories feel tangible. He's always available during office hours and truly wants his students to succeed.
            The exams are difficult, but if you attend every lecture and do the assignments, you'll be fine.
          </p>
          <div class="d-flex align-items-center justify-content-between review-footer">
            <button type="button" class="helpful-btn d-flex align-items-center gap-1">
              <i class="bi bi-hand-thumbs-up"></i>
              <span class="helpful-count">Helpful (12)</span>
            </button>
            <button type="button" class="report-btn">Report</button>
          </div>
        </article>

        <!-- Review 2 -->
        <article class="review-card" data-rating="4.5" data-helpful="8" data-date="2023-12-04">
          <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
            <div>
              <h3 class="review-course">CACS254: Scripting Languages</h3>
              <p class="review-date mb-0">Dec 04, 2023</p>
            </div>
            <span class="score-pill">4.5 / 5.0</span>
          </div>
          <div class="d-flex gap-2 review-meta mb-3">
            <span>Grade: <strong>B</strong></span>
            <span>•</span>
            <span>For Credit: <strong>Yes</strong></span>
          </div>
          <p class="review-body mb-3">
            Great professor who actually cares about teaching. Sometimes the pace can be a bit fast, especially in
            the second half of the semester.
          </p>
          <div class="d-flex align-items-center justify-content-between review-footer">
            <button type="button" class="helpful-btn d-flex align-items-center gap-1">
              <i class="bi bi-hand-thumbs-up"></i>
              <span class="helpful-count">Helpful (8)</span>
            </button>
            <button type="button" class="report-btn">Report</button>
          </div>
        </article>

      </div>

      <div class="text-center pt-4">
        <button type="button" class="more-reviews-btn" id="moreReviewsBtn">More Reviews</button>
      </div>
    </section>

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

<!-- ============ RATE MODAL (basic, wired to the "Rate" button) ============ -->
<div class="modal fade" id="rateModal" tabindex="-1" aria-labelledby="rateModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius: 12px;">
      <div class="modal-header">
        <h5 class="modal-title" id="rateModalLabel">Rate Sushil Ghimire</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="text-muted small">This is a placeholder form — wire it up to your PHP/MySQL submission endpoint.</p>
        <div class="mb-3">
          <label for="rateCourse" class="form-label">Course</label>
          <input type="text" class="form-control" id="rateCourse" placeholder="e.g. CACS204">
        </div>
        <div class="mb-3">
          <label for="rateScore" class="form-label">Overall rating (out of 5)</label>
          <input type="number" min="0" max="5" step="0.1" class="form-control" id="rateScore">
        </div>
        <div class="mb-3">
          <label for="rateComment" class="form-label">Review</label>
          <textarea class="form-control" id="rateComment" rows="3"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-brand" id="submitRatingBtn">Submit</button>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS bundle (needed for the modal) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  // ---- Sort reviews ----
  const sortSelect = document.getElementById('sortSelect');
  const reviewsList = document.getElementById('reviewsList');

  function sortReviews(mode){
    const cards = Array.from(reviewsList.querySelectorAll('.review-card'));

    cards.sort((a, b) => {
      if (mode === 'highest') {
        return parseFloat(b.dataset.rating) - parseFloat(a.dataset.rating);
      }
      if (mode === 'lowest') {
        return parseFloat(a.dataset.rating) - parseFloat(b.dataset.rating);
      }
      if (mode === 'helpful') {
        return parseInt(b.dataset.helpful, 10) - parseInt(a.dataset.helpful, 10);
      }
      // 'recent' (default): newest date first
      return new Date(b.dataset.date) - new Date(a.dataset.date);
    });

    cards.forEach(card => reviewsList.appendChild(card));
  }

  sortSelect.addEventListener('change', (e) => sortReviews(e.target.value));

  // ---- Helpful button toggle ----
  document.querySelectorAll('.helpful-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const countSpan = btn.querySelector('.helpful-count');
      const match = countSpan.textContent.match(/\d+/);
      let count = match ? parseInt(match[0], 10) : 0;

      if (btn.classList.contains('active')) {
        count -= 1;
        btn.classList.remove('active');
      } else {
        count += 1;
        btn.classList.add('active');
      }
      countSpan.textContent = `Helpful (${count})`;
    });
  });

  // ---- Report button (placeholder) ----
  document.querySelectorAll('.report-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      alert('Thanks — this review has been flagged for moderator review.');
    });
  });

  // ---- More Reviews (placeholder pagination hook) ----
  document.getElementById('moreReviewsBtn').addEventListener('click', function () {
    this.textContent = 'Loading...';
    // Replace this with a real fetch() call to your PHP endpoint, e.g.:
    // fetch(`/api/reviews.php?professor_id=123&page=2`).then(...)
    setTimeout(() => {
      this.textContent = 'No more reviews';
      this.disabled = true;
    }, 600);
  });

  // ---- Rating submission (placeholder) ----
  document.getElementById('submitRatingBtn').addEventListener('click', () => {
    const course = document.getElementById('rateCourse').value.trim();
    const score = document.getElementById('rateScore').value;
    const comment = document.getElementById('rateComment').value.trim();

    if (!course || !score || !comment) {
      alert('Please fill in all fields.');
      return;
    }

    // Replace with a real fetch() POST to your PHP/MySQL endpoint.
    console.log('Submitting rating:', { course, score, comment });
    alert('Rating submitted (demo only — connect this to your backend).');

    bootstrap.Modal.getInstance(document.getElementById('rateModal')).hide();
    document.getElementById('rateCourse').value = '';
    document.getElementById('rateScore').value = '';
    document.getElementById('rateComment').value = '';
  });
</script>

</body>
</html>
