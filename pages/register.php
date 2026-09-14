<?php
require_once __DIR__ . '/../config/dbconn.php';
require_once __DIR__ . '/../includes/auth.php';

$errorMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['s_email'] ?? '');
    $password = $_POST['password'] ?? '';
    $course = trim($_POST['s_course'] ?? 'BCA');
    $enrollmentYear = filter_input(INPUT_POST, 'enrollment_year', FILTER_VALIDATE_INT);

    if (!isKmcenEmail($email) || $course === '' || !$enrollmentYear || $enrollmentYear < 2000 || $enrollmentYear > (int) date('Y') || strlen($password) < 8) {
        $errorMessage = 'Use a valid KMC email ending in @kmcen.edu.np, course, enrollment year, and password of at least 8 characters.';
    } else {
        $statement = mysqli_prepare($conn, 'INSERT INTO students (s_course, enrollment_year, s_email, password_hash) VALUES (?, ?, ?, ?)');
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        mysqli_stmt_bind_param($statement, 'siss', $course, $enrollmentYear, $email, $passwordHash);
        if (mysqli_stmt_execute($statement)) {
            $_SESSION['s_id'] = mysqli_insert_id($conn);
            $_SESSION['s_email'] = $email;
            header('Location: search.php');
            exit;
        }
        $errorMessage = mysqli_errno($conn) === 1062 ? 'That email is already registered.' : 'The account could not be created.';
        mysqli_stmt_close($statement);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Rate My Teacher</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@600&family=Inter:wght@400;500;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../css/register.css">
</head>

<body>

    <div class="register-wrap">
        <div class="register-card">

            <!-- Back -->
            <button type="button" class="back-btn" id="backBtn">
                <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13 8H3M7 4 3 8l4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                <span>Back</span>
            </button>

            <!-- Brand & Header -->
            <div class="brand-icon">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M12 3 1 9l11 6 9-4.91V17h2V9L12 3zm0 13.18L5 12.4V10.1l7 3.82 7-3.82v2.3l-7 3.78zM5 14.5v3c0 .8 3.13 2.5 7 2.5s7-1.7 7-2.5v-3l-7 3.82L5 14.5z" />
                </svg>
            </div>
            <h1 class="title">Join Rate My Teacher</h1>

            <!-- Form -->
            <form id="registerForm" class="pt-2" method="post" action="register.php" novalidate>

                <!-- University Email -->
                <div class="field-block">
                    <label for="universityEmail" class="field-label">Email</label>
                    <div class="input-icon-wrap">
                        <span class="leading-icon">
                            <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 3h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1Z"
                                    stroke="currentColor" stroke-width="1.2" />
                                <path d="m1.5 4 6.5 5 6.5-5" stroke="currentColor" stroke-width="1.2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <input type="email" class="form-control" id="universityEmail" name="s_email" placeholder="you@kmcen.edu.np"
                            pattern="^[^@\s]+@kmcen\.edu\.np$" required>
                    </div>
                    <div class="helper-text">

                        <span>Use your KMC email ending in @kmcen.edu.np.</span>
                    </div>
                </div>

                <div class="field-block">
                    <label for="course" class="field-label">Course</label>
                    <select class="form-control" id="course" name="s_course" required>
                        <option value="">Select your course</option>
                        <option value="BCA">BCA</option>
                        <option value="BBA">BBA</option>
                        <option value="BBM">BBM</option>
                        <option value="BA/BBS">BA/BBS</option>
                    </select>
                </div>

                <div class="field-block">
                    <label for="enrollmentYear" class="field-label">Course Enrollment Year</label>
                    <input class="form-control" type="number" id="enrollmentYear" name="enrollment_year"
                        min="2000" max="<?= (int) date('Y') ?>" placeholder="e.g. <?= (int) date('Y') - 1 ?>" required>
                </div>

                <!-- Password -->
                <div class="field-block">
                    <label for="password" class="field-label">Create Password</label>
                    <div class="input-icon-wrap">
                        <span class="leading-icon">
                            <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="3" y="7" width="10" height="7" rx="1.2" stroke="currentColor"
                                    stroke-width="1.2" />
                                <path d="M5 7V4.5a3 3 0 0 1 6 0V7" stroke="currentColor" stroke-width="1.2" />
                            </svg>
                        </span>
                        <input type="password" class="form-control" id="password" name="password" placeholder="At least 8 characters"
                            required minlength="8" style="padding-right:40px;">
                        <button type="button" class="toggle-visibility" id="togglePassword"
                            aria-label="Toggle password visibility">
                            <svg id="eyeIcon" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 8s2.7-5 7-5 7 5 7 5-2.7 5-7 5-7-5-7-5Z" stroke="currentColor"
                                    stroke-width="1.2" />
                                <circle cx="8" cy="8" r="2" stroke="currentColor" stroke-width="1.2" />
                            </svg>
                        </button>
                    </div>

                </div>

                <!-- Terms -->
                <div class="terms-row form-check">
                    <input class="form-check-input" type="checkbox" id="agreeTerms" required>
                    <label class="form-check-label" for="agreeTerms">
                        I agree to the <a href="../index.php#about">Community Guidelines</a> and <a href="../index.php#about">Terms of Service</a>,
                        and pledge to post honest, constructive reviews.
                    </label>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn-submit">
                    <span>Create Account</span>
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>
            </form>
            <?php if ($errorMessage): ?><div class="alert alert-danger mt-3" role="alert"><?= htmlspecialchars($errorMessage) ?></div><?php endif; ?>

            <!-- Trust badge -->
            <div class="trust-badge">
                <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="3" y="7" width="10" height="7" rx="1.2" stroke="currentColor" stroke-width="1.2" />
                    <path d="M5 7V4.5a3 3 0 0 1 6 0V7" stroke="currentColor" stroke-width="1.2" />
                </svg>
                <span>100% Student Privacy &amp; Anonymous Reviews Guaranteed</span>
            </div>

            <!-- Login redirect -->
            <div class="login-row">
                Already have an account? <a href="login.php">Log in</a>
            </div>

        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
    <script>
        // Back button
        document.getElementById('backBtn').addEventListener('click', () => {
            if (window.history.length > 1) {
                window.history.back();
            } else {
                window.location.href = 'search.php';
            }
        });

        // Password visibility toggle
        const passwordInput = document.getElementById('password');
        const toggleBtn = document.getElementById('togglePassword');
        const eyeIcon = document.getElementById('eyeIcon');

        toggleBtn.addEventListener('click', () => {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            eyeIcon.innerHTML = isPassword
                ? '<path d="M1 8s2.7-5 7-5 7 5 7 5-2.7 5-7 5-7-5-7-5Z" stroke="currentColor" stroke-width="1.2"/><circle cx="8" cy="8" r="2" stroke="currentColor" stroke-width="1.2"/><line x1="2" y1="14" x2="14" y2="2" stroke="currentColor" stroke-width="1.2"/>'
                : '<path d="M1 8s2.7-5 7-5 7 5 7 5-2.7 5-7 5-7-5-7-5Z" stroke="currentColor" stroke-width="1.2"/><circle cx="8" cy="8" r="2" stroke="currentColor" stroke-width="1.2"/>';
        });

        // Password strength meter
        const strengthFill = document.getElementById('strengthFill');
        const strengthLabel = document.getElementById('strengthLabel');

        function scorePassword(pw) {
            let score = 0;
            if (pw.length >= 8) score++;
            if (pw.length >= 12) score++;
            if (/[A-Z]/.test(pw)) score++;
            if (/[0-9]/.test(pw)) score++;
            if (/[^A-Za-z0-9]/.test(pw)) score++;
            return score;
        }

        passwordInput.addEventListener('input', () => {
            const pw = passwordInput.value;
            if (!pw) {
                strengthFill.style.width = '0%';
                strengthFill.style.backgroundColor = '#dc3545';
                strengthLabel.textContent = 'Password strength';
                return;
            }
            const score = scorePassword(pw);
            const pct = Math.min((score / 5) * 100, 100);
            strengthFill.style.width = pct + '%';

            let label, color;
            if (score <= 1) { label = 'Weak'; color = '#dc3545'; }
            else if (score <= 3) { label = 'Fair'; color = '#fd7e14'; }
            else if (score === 4) { label = 'Good'; color = '#ffc107'; }
            else { label = 'Strong'; color = '#198754'; }

            strengthFill.style.backgroundColor = color;
            strengthLabel.textContent = label;
            strengthLabel.style.color = color;
        });

        document.getElementById('registerForm').addEventListener('submit', (e) => {
            const form = e.target;
            if (!form.checkValidity()) {
                e.preventDefault();
                form.classList.add('was-validated');
            }
        });
    </script>
</body>

</html>