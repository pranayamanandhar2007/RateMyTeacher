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
            <form id="registerForm" class="pt-2" novalidate>

                <!-- Display Name -->
                <div class="field-block">
                    <label for="displayName" class="field-label">Username</label>
                    <div class="input-icon-wrap">
                        <span class="leading-icon">
                            <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M8 8A3.5 3.5 0 1 0 8 1a3.5 3.5 0 0 0 0 7Zm0 1.5c-2.67 0-6 1.34-6 4V15h12v-1.5c0-2.66-3.33-4-6-4Z"
                                    fill="currentColor" />
                            </svg>
                        </span>
                        <input type="text" class="form-control" id="displayName" placeholder="e.g. QuantumScholar24"
                            required>
                    </div>
                    <div class="helper-text">

                        <span>Your real name is never shown publicly.</span>
                    </div>
                </div>

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
                        <input type="email" class="form-control" id="universityEmail" placeholder="you@university.edu"
                            required>
                    </div>
                    <div class="helper-text">

                        <span>Used only for student verification badge.</span>
                    </div>
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
                        <input type="password" class="form-control" id="password" placeholder="At least 8 characters"
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
                        I agree to the <a href="#">Community Guidelines</a> and <a href="#">Terms of Service</a>,
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
                Already have an account? <a href="#">Log in</a>
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
                alert('Back pressed — wire this up to your previous page/route.');
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

        // Basic client-side validation on submit
        document.getElementById('registerForm').addEventListener('submit', (e) => {
            e.preventDefault();
            const form = e.target;
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }
            alert('Account created! (This is a front-end demo — hook this up to your backend.)');
            form.reset();
            strengthFill.style.width = '0%';
            strengthLabel.textContent = 'Password strength';
        });
    </script>
</body>

</html>