<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sushil Ghimire · Rate My Teacher</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../css/professor-profile.css">
<!-- Bootstrap Icons (used as stand-ins for the Figma icon assets, which couldn't be downloaded in this environment) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<!-- Google Fonts: Manrope (headings) + Inter (body) to match the Figma type styles -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@700;800&display=swap" rel="stylesheet">

<style>
  :root{
    --brand-blue: #1b4ddb;
    --ink: #1a1b24;
    --body-text: #434655;
    --muted: #747686;
    --border: #c4c5d7;
    --bg: #fbf8ff;
    --footer-bg: #e2e1ee;
    --search-bg: #f4f2ff;
  }

  body{
    background-color: var(--bg);
    color: var(--ink);
    font-family: 'Inter', sans-serif;
  }

  h1, h2, h3, .brand-font{
    font-family: 'Manrope', sans-serif;
    font-weight: 800;
  }

  /* ---------- Header ---------- */
  .site-header{
    position: sticky;
    top: 0;
    z-index: 50;
    background-color: rgba(251, 248, 255, 0.95);
    backdrop-filter: blur(2px);
    border-bottom: 1px solid var(--border);
  }

  .brand-link{
    color: var(--brand-blue);
    font-weight: 800;
    font-size: 1.5rem;
    letter-spacing: -0.6px;
    text-decoration: none;
  }

  .site-header .nav-link{
    color: var(--body-text);
    font-size: 1rem;
    padding: 0;
  }

  .site-header .nav-link:hover{
    color: var(--brand-blue);
  }

  .search-wrap{
    position: relative;
    width: 256px;
  }

  .search-wrap input{
    background-color: var(--search-bg);
    border: 1px solid var(--border);
    border-radius: 9999px;
    padding: 9px 17px 10px 41px;
    font-size: 0.75rem;
    width: 100%;
  }

  .search-wrap input:focus{
    box-shadow: 0 0 0 0.15rem rgba(27,77,219,.2);
    border-color: var(--brand-blue);
  }

  .search-wrap i{
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--muted);
    font-size: 0.9rem;
  }

  .btn-brand{
    background-color: var(--brand-blue);
    color: #fff;
    border-radius: 9999px;
    font-weight: 700;
    border: none;
    white-space: nowrap;
  }

  .btn-brand:hover{
    background-color: #163fae;
    color: #fff;
  }

  /* ---------- Hero / professor header ---------- */
  .eyebrow{
    color: var(--brand-blue);
    font-weight: 700;
    font-size: 0.75rem;
    letter-spacing: 1.2px;
    text-transform: uppercase;
  }

  .prof-name{
    font-size: 4rem;
    line-height: 1.1;
    letter-spacing: -1.4px;
    margin-bottom: 0;
  }

  .rating-card{
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 12px;
    box-shadow: 0 1px 1px rgba(0,0,0,0.05);
    padding: 17px 25px 19px;
    text-align: center;
    min-width: 108px;
  }

  .rating-card .score{
    color: var(--brand-blue);
    font-size: 3rem;
    font-weight: 700;
    letter-spacing: -0.96px;
    line-height: 1;
  }

  .rating-card .label{
    color: var(--muted);
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: -0.6px;
    text-transform: uppercase;
    margin-top: 4px;
  }

  /* ---------- Stats bar ---------- */
  .stats-bar{
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
    padding: 33px 0;
  }

  .stats-bar .stat{
    font-size: 1.25rem;
    color: var(--ink);
  }

  /* ---------- Reviews ---------- */
  .section-title{
    font-size: 1.5rem;
    font-weight: 800;
  }

  .sort-label{
    color: var(--muted);
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
  }

  .sort-select{
    border: none;
    background: transparent;
    color: var(--ink);
    font-size: 1rem;
    font-family: inherit;
    padding-right: 4px;
  }

  .sort-select:focus{
    outline: none;
    box-shadow: none;
  }

  .review-card{
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 12px;
    box-shadow: 0 1px 1px rgba(0,0,0,0.05);
    padding: 25px;
  }

  .review-course{
    color: var(--brand-blue);
    font-weight: 800;
    font-size: 1.25rem;
    margin-bottom: 0;
  }

  .review-date{
    color: var(--muted);
    font-size: 0.75rem;
    font-weight: 600;
  }

  .score-pill{
    background-color: var(--brand-blue);
    color: #fff;
    font-weight: 700;
    font-size: 1rem;
    border-radius: 9999px;
    padding: 4px 12px;
    white-space: nowrap;
    height: fit-content;
  }

  .review-meta{
    color: var(--muted);
    font-size: 0.75rem;
    font-weight: 600;
  }

  .review-meta strong{
    color: var(--ink);
    font-weight: 600;
  }

  .review-body{
    color: var(--body-text);
    font-size: 1rem;
    line-height: 1.625;
    margin-bottom: 0;
  }

  .review-footer{
    border-top: 1px solid var(--border);
    padding-top: 17px;
  }

  .helpful-btn, .report-btn{
    background: none;
    border: none;
    color: var(--muted);
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0;
  }

  .helpful-btn:hover, .report-btn:hover{
    color: var(--brand-blue);
  }

  .helpful-btn.active{
    color: var(--brand-blue);
  }

  .more-reviews-btn{
    background: none;
    border: none;
    color: var(--brand-blue);
    font-weight: 700;
    font-size: 0.75rem;
    letter-spacing: 1.2px;
    text-transform: uppercase;
  }

  /* ---------- Footer ---------- */
  .site-footer{
    background-color: var(--footer-bg);
    border-top: 1px solid var(--border);
    padding: 49px 0 48px;
  }

  .footer-brand{
    color: var(--brand-blue);
    font-weight: 800;
    font-size: 1.5rem;
  }

  .footer-copy{
    color: var(--body-text);
    font-size: 0.75rem;
    max-width: 320px;
  }

  .footer-heading{
    color: var(--ink);
    font-size: 0.75rem;
    font-weight: 700;
    margin-bottom: 8px;
  }

  .footer-list{
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .footer-list li{
    margin-bottom: 8px;
  }

  .footer-list a{
    color: var(--body-text);
    font-size: 0.75rem;
    font-weight: 600;
    text-decoration: none;
  }

  .footer-list a:hover{
    color: var(--brand-blue);
    text-decoration: underline;
  }

  /* ---------- Ratings breakdown ---------- */
  .breakdown-card{
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 12px;
    box-shadow: 0 1px 1px rgba(0,0,0,0.05);
    padding: 25px;
  }

  .breakdown-title{
    font-size: 1.25rem;
    font-weight: 800;
    margin-bottom: 20px;
  }

  .breakdown-row{
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
  }

  .breakdown-row:last-child{
    margin-bottom: 0;
  }

  .breakdown-label{
    width: 56px;
    flex-shrink: 0;
    font-size: 0.8125rem;
    font-weight: 600;
    color: var(--body-text);
    display: flex;
    align-items: center;
    gap: 4px;
    white-space: nowrap;
  }

  .breakdown-label i{
    color: #f4b400;
    font-size: 0.75rem;
  }

  .breakdown-track{
    flex: 1;
    height: 10px;
    background-color: #ececf5;
    border-radius: 9999px;
    overflow: hidden;
  }

  .breakdown-fill{
    height: 100%;
    background-color: var(--brand-blue);
    border-radius: 9999px;
    width: 0%;
    transition: width .5s ease;
  }

  .breakdown-count{
    width: 84px;
    flex-shrink: 0;
    text-align: right;
    font-size: 0.8125rem;
    color: var(--muted);
    font-weight: 600;
  }

  @media (max-width: 767px){
    .prof-name{ font-size: 2.5rem; }
    .stats-bar .row > div{ margin-bottom: 1rem; }
    .search-wrap{ display: none; }
    .breakdown-count{ width: 64px; }
  }
</style>
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
          <a href="ratings.php" class="btn btn-brand px-4 py-3">Rate</a>
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

    <!-- Ratings breakdown -->
    <section class="pt-5">
      <div class="breakdown-card">
        <p class="breakdown-title mb-0">Rating Breakdown</p>
        <div id="breakdownRows" data-counts="5:24,4:10,3:5,2:2,1:1">
          <!-- rows are rendered by JS from data-counts -->
        </div>
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

</script>

</body>
</html>