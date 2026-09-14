<?php
require_once __DIR__ . '/../includes/search_backend.php';

function searchUrl(array $overrides = []): string
{
    global $searchParameters;
    $parameters = array_merge($searchParameters, $overrides);
    if (($parameters['page'] ?? 1) === 1) {
        unset($parameters['page']);
    }
    return 'search.php?' . http_build_query(array_filter($parameters, static fn ($value) => $value !== ''));
}
?>
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
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <?php $basePath = '../'; include __DIR__ . '/../includes/navbar.php'; ?>

    <!-- Page header + search -->
    <section class="page-header">
        <div class="container">
            <h1 class="page-title mb-3">Search Teachers</h1>
            <form method="get" action="search.php" class="input-group input-group-lg rounded-pill search-page-bar" style="max-width: 100%;">
                <span class="input-group-text ps-4"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input class="form-control" type="search" name="q" value="<?= htmlspecialchars($searchQuery) ?>" placeholder="Search by teacher name" aria-label="Search teachers">
                <input type="hidden" name="course" value="<?= htmlspecialchars($courseFilter) ?>">
                <input type="hidden" name="min_rating" value="<?= htmlspecialchars((string) ($minRating ?? '')) ?>">
                <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
                <button class="btn btn-brand px-4" type="submit">Search</button>
            </form>
        </div>
    </section>

    <!-- Filters + Results -->
    <section class="py-4">
        <div class="container">
            <div class="row g-4">

                <!-- Filters sidebar -->
                <div class="col-lg-3">
                    <div class="filters-title text-uppercase mb-3">Filters</div>

                    <form method="get" action="search.php">
                    <input type="hidden" name="q" value="<?= htmlspecialchars($searchQuery) ?>">
                    <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
                    <div class="mb-4">
                        <div class="filter-label">Course</div>
                        <select class="form-select form-select-custom" name="course">
                            <option value="">All courses</option>
                            <?php foreach (['BCA', 'BBA', 'BBM', 'BA/BBS'] as $courseOption): ?>
                                <option value="<?= $courseOption ?>" <?= $courseFilter === $courseOption ? 'selected' : '' ?>><?= $courseOption ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <div class="filter-label">Minimum Rating</div>
                        <div class="form-check form-check-custom mb-2">
                            <input class="form-check-input" type="radio" name="min_rating" value="4" id="rating4" <?= $minRating === 4.0 ? 'checked' : '' ?>>
                            <label class="form-check-label" for="rating4">4.0+ Stars</label>
                        </div>
                        <div class="form-check form-check-custom">
                            <input class="form-check-input" type="radio" name="min_rating" value="3" id="rating3" <?= $minRating === 3.0 ? 'checked' : '' ?>>
                            <label class="form-check-label" for="rating3">3.0+ Stars</label>
                        </div>
                        <div class="form-check form-check-custom">
                            <input class="form-check-input" type="radio" name="min_rating" value="" id="ratingAny" <?= $minRating === null ? 'checked' : '' ?>>
                            <label class="form-check-label" for="ratingAny">Any rating</label>
                        </div>
                    </div>

                    <button class="btn btn-brand btn-sm" type="submit">Apply filters</button>
                    <a href="search.php" class="clear-filters"><i class="fa-solid fa-rotate-left"></i> Clear all filters</a>
                    </form>
                </div>

                <!-- Results -->
                <div class="col-lg-9">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <div class="results-meta"><?= $totalTeachers ?> teachers</div>
                        <form method="get" action="search.php" class="d-flex align-items-center gap-2">
                            <input type="hidden" name="q" value="<?= htmlspecialchars($searchQuery) ?>">
                            <input type="hidden" name="course" value="<?= htmlspecialchars($courseFilter) ?>">
                            <input type="hidden" name="min_rating" value="<?= htmlspecialchars((string) ($minRating ?? '')) ?>">
                            <label class="results-meta mb-0" for="sortSelect">Sort by:</label>
                            <select class="form-select form-select-sm" name="sort" id="sortSelect" onchange="this.form.submit()">
                                <option value="highest" <?= $sort === 'highest' ? 'selected' : '' ?>>Highest Rated</option>
                                <option value="lowest" <?= $sort === 'lowest' ? 'selected' : '' ?>>Lowest Rated</option>
                                <option value="reviews" <?= $sort === 'reviews' ? 'selected' : '' ?>>Most Reviewed</option>
                                <option value="name" <?= $sort === 'name' ? 'selected' : '' ?>>Name</option>
                            </select>
                        </form>
                    </div>

                    <div class="d-flex flex-column gap-3">

                        <?php if (!$teachers): ?>
                            <p class="text-muted">No teachers found.</p>
                        <?php else: ?>
                            <?php foreach ($teachers as $teacher): ?>
                                <a href="professor-profile.php?t_id=<?= (int) $teacher['t_id'] ?>" class="professor-card">
                                    <div>
                                        <div class="professor-name"><?= htmlspecialchars($teacher['t_name']) ?></div>
                                        <div class="professor-course"><?= htmlspecialchars($teacher['course']) ?></div>
                                        <div class="professor-meta">
                                            <span>Difficulty: <?= number_format((float) $teacher['average_difficulty'], 1) ?></span>
                                            <span><?= (int) round((float) $teacher['take_again_percentage']) ?>% would take again</span>
                                        </div>
                                    </div>
                                    <div class="professor-rating">
                                        <div class="score"><?= number_format((float) $teacher['average_quality'], 1) ?></div>
                                        <div class="label">RATING</div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>

                    </div>

                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                        <div class="pagination-custom mt-4">
                            <?php if ($page > 1): ?><a href="<?= htmlspecialchars(searchUrl(['page' => $page - 1])) ?>"><i class="fa-solid fa-chevron-left"></i> Previous</a><?php endif; ?>
                            <?php for ($pageNumber = 1; $pageNumber <= $totalPages; $pageNumber++): ?>
                                <a href="<?= htmlspecialchars(searchUrl(['page' => $pageNumber])) ?>" class="page-number <?= $pageNumber === $page ? 'active' : '' ?>"><?= $pageNumber ?></a>
                            <?php endfor; ?>
                            <?php if ($page < $totalPages): ?><a href="<?= htmlspecialchars(searchUrl(['page' => $page + 1])) ?>">Next <i class="fa-solid fa-chevron-right"></i></a><?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </section>

    <?php $basePath = '../'; include __DIR__ . '/../includes/footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
</body>

</html>