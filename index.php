<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rate My Teacher</title>

    <!-- Bootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Our own custom styles (must come AFTER bootstrap) -->
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-white py-3">
        <div class="container">
            <a class="navbar-brand brand-name" href="#">Rate My Teacher</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navContent">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link nav-link-custom active" href="#">Browse</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-custom" href="#">Top Lists</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-custom" href="#">About</a></li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <a href="#" class="nav-link-custom text-decoration-none">Sign In</a>
                    <button class="btn btn-brand px-3">Register</button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero">
        <div class="container text-center py-5 mt-3" style="max-width: 700px;">
            <h1 class="display-5 hero-title mb-4">Bring transparency to higher education.</h1>
            <p class="hero-subtitle mb-4">Find your professor, read honest reviews, and share your own experiences.
                Simple, verified, and community-driven.</p>
            <div class="input-group rounded-pill hero-search mx-auto mb-4" style="max-width: 600px;">
                <input class="form-control" type="search" placeholder="Search for a professor or department"
                    aria-label="Search">
                <button class="btn btn-brand rounded-pill px-4 m-1" type="submit">Search</button>
            </div>

            <div class="trust-row d-flex justify-content-center flex-wrap">
                <span><i class="fa-solid fa-certificate"></i>2M+ Reviews</span>
                <span><i class="fa-solid fa-lock"></i>Anonymous</span>
                <span><i class="fa-solid fa-graduation-cap"></i>Verified Schools</span>
            </div>
        </div>
    </section>

    <!-- Popular Courses -->
    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="section-title mb-0">Popular Courses</h2>
                <a href="#" class="view-all">View all</a>
            </div>
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <div class="course-card">
                        <div class="course-icon"><i class="fa-solid fa-laptop"></i></div>
                        <h5>BCA</h5>
                        <p>400+ Reviews</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="course-card">
                        <div class="course-icon"><i class="fa-solid fa-chart-line"></i></div>
                        <h5>BBA</h5>
                        <p>900+ Reviews</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="course-card">
                        <div class="course-icon"><i class="fa-solid fa-briefcase"></i></div>
                        <h5>BBM</h5>
                        <p>200+ Reviews</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="course-card">
                        <div class="course-icon"><i class="fa-solid fa-flask"></i></div>
                        <h5>BA/BBS</h5>
                        <p>100+ Reviews</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Built for students -->
    <section class="built-for">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="mb-3">Built for students.</h2>
                    <p class="intro mb-4">We believe every student deserves to know what to expect before stepping into
                        a classroom. Our platform is built on three simple pillars.</p>

                    <div class="feature-item">
                        <i class="fa-solid fa-circle-check"></i>
                        <div>
                            <h6>Verified Identities</h6>
                            <p>We use university email verification to ensure only real students leave reviews.</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="fa-solid fa-circle-check"></i>
                        <div>
                            <h6>Simple Grading</h6>
                            <p>Easy-to-understand metrics for clarity, helpfulness, and course difficulty.</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="fa-solid fa-circle-check"></i>
                        <div>
                            <h6>Community Moderation</h6>
                            <p>Our community helps flag unhelpful or abusive content to maintain quality.</p>
                        </div>
                    </div>

                    <button class="btn btn-brand px-4 py-2 mt-2" href="/pages/search.html">Start Browsing
                        Professors</button>
                </div>
                <div class="col-lg-6 d-none d-lg-block"></div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-5 mb-4 mb-lg-0">
                    <div class="brand-name mb-2">Rate My Teacher</div>
                    <p class="copyright">© 2026 Rate My Teacher Academic. All rights reserved.</p>
                </div>
                <div class="col-6 col-lg-2">
                    <h6>Platform</h6>
                    <a href="#">Browse</a>
                    <a href="#">Top Lists</a>
                    <a href="#">Schools</a>
                </div>
                <div class="col-6 col-lg-2">
                    <h6>Legal</h6>
                    <a href="#">Privacy</a>
                    <a href="#">Terms</a>
                    <a href="#">Guidelines</a>
                </div>
                <div class="col-6 col-lg-2">
                    <h6>Support</h6>
                    <a href="#">Help Center</a>
                    <a href="#">Contact</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
</body>

</html>