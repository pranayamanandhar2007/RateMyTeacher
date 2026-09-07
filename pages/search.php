<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Professors — Rate My Teacher</title>

    <!-- Bootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Our own custom styles (must come AFTER bootstrap) -->
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-white py-3">
        <div class="container">
            <a class="navbar-brand brand-name" href="index.html">Rate My Teacher</a>
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
                    <button class="btn btn-brand px-3">Sign In</button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page header + search -->
    <section class="page-header">
        <div class="container">
            <h1 class="page-title mb-3">Search Professors</h1>
            <div class="input-group input-group-lg rounded-pill search-page-bar" style="max-width: 100%;">
                <span class="input-group-text ps-4"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input class="form-control" type="search" value="Computer Science" aria-label="Search">
            </div>
        </div>
    </section>

    <!-- Filters + Results -->
    <section class="py-4">
        <div class="container">
            <div class="row g-4">

                <!-- Filters sidebar -->
                <div class="col-lg-3">
                    <div class="filters-title text-uppercase mb-3">Filters</div>

                    <div class="mb-4">
                        <div class="filter-label">Course</div>
                        <select class="form-select form-select-custom">
                            <option>BCA</option>
                            <option>BBA</option>
                            <option>BBM</option>
                            <option>BA/BBS</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <div class="filter-label">Minimum Rating</div>
                        <div class="form-check form-check-custom mb-2">
                            <input class="form-check-input" type="checkbox" id="rating4" checked>
                            <label class="form-check-label" for="rating4">4.0+ Stars</label>
                        </div>
                        <div class="form-check form-check-custom">
                            <input class="form-check-input" type="checkbox" id="rating3">
                            <label class="form-check-label" for="rating3">3.0+ Stars</label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="filter-label">Difficulty</div>
                        <div class="difficulty-pills">
                            <span class="difficulty-pill">Easy</span>
                            <span class="difficulty-pill">Average</span>
                            <span class="difficulty-pill">Hard</span>
                        </div>
                    </div>

                    <a href="#" class="clear-filters"><i class="fa-solid fa-rotate-left"></i> Clear all filters</a>
                </div>

                <!-- Results -->
                <div class="col-lg-9">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <div class="results-meta">142 results for "Computer Science"</div>
                        <div class="results-meta">Sort by: <a href="#" class="sort-link">Highest Rated</a></div>
                    </div>

                    <div class="d-flex flex-column gap-3">

                        <div class="professor-card">
                            <div>
                                <div class="professor-name">Teksan Gharti Magar</div>
                                <div class="professor-course">Web Technology</div>
                                <div class="professor-meta">
                                    <span>Difficulty: 3.2</span>
                                    <span>94% would take again</span>
                                </div>
                            </div>
                            <div class="professor-rating">
                                <div class="score">4.9</div>
                                <div class="label">RATING</div>
                            </div>
                        </div>

                        <div class="professor-card">
                            <div>
                                <div class="professor-name">Rajit Bhusal</div>
                                <div class="professor-course">Intro to Artificial Intelligence</div>
                                <div class="professor-meta">
                                    <span>Difficulty: 4.8</span>
                                    <span>78% would take again</span>
                                </div>
                            </div>
                            <div class="professor-rating">
                                <div class="score">4.2</div>
                                <div class="label">RATING</div>
                            </div>
                        </div>

                        <div class="professor-card">
                            <div>
                                <div class="professor-name">Sushil Khadka</div>
                                <div class="professor-course">Statistics and Probablity</div>
                                <div class="professor-meta">
                                    <span>Difficulty: 2.5</span>
                                    <span>98% would take again</span>
                                </div>
                            </div>
                            <div class="professor-rating">
                                <div class="score">4.7</div>
                                <div class="label">RATING</div>
                            </div>
                        </div>

                        <div class="professor-card">
                            <div>
                                <div class="professor-name">Bishal Lama</div>
                                <div class="professor-course">Data Structures &amp; Algorithms</div>
                                <div class="professor-meta">
                                    <span>Difficulty: 4.1</span>
                                    <span>82% would take again</span>
                                </div>
                            </div>
                            <div class="professor-rating">
                                <div class="score">4.5</div>
                                <div class="label">RATING</div>
                            </div>
                        </div>

                    </div>

                    <!-- Pagination -->
                    <div class="pagination-custom mt-4">
                        <a href="#"><i class="fa-solid fa-chevron-left"></i> Previous</a>
                        <a href="#" class="page-number active">1</a>
                        <a href="#" class="page-number">2</a>
                        <a href="#" class="page-number">3</a>
                        <a href="#">Next <i class="fa-solid fa-chevron-right"></i></a>
                    </div>
                </div>

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