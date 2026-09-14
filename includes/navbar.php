<?php
$basePath = $basePath ?? '';
if (!isset($conn)) {
    require_once __DIR__ . '/../config/dbconn.php';
}
require_once __DIR__ . '/auth.php';
$studentEmail = currentStudentEmail($conn);
?>
<nav class="navbar navbar-expand-lg bg-white py-3">
        <div class="container">
            <a class="navbar-brand brand-name" href="<?= $basePath ?>index.php">Rate My Teacher</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navContent">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link nav-link-custom active" href="<?= $basePath ?>pages/search.php">Browse</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-custom" href="<?= $basePath ?>pages/search.php?sort=highest">Top Lists</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-custom" href="<?= $basePath ?>index.php#about">About</a></li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <?php if ($studentEmail): ?>
                        <span class="account-email text-truncate" title="<?= htmlspecialchars($studentEmail) ?>"><?= htmlspecialchars($studentEmail) ?></span>
                        <a class="btn btn-logout px-3" href="<?= $basePath ?>pages/logout.php">Log out</a>
                    <?php else: ?>
                        <a class="nav-link-custom text-decoration-none" href="<?= $basePath ?>pages/login.php">Sign In</a>
                        <a class="btn btn-brand px-3" href="<?= $basePath ?>pages/register.php">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
</nav>