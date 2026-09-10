<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In — RateMyTeacher</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@600;700&family=Inter:wght@400;500;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>

    <div class="login-wrap">
        <div class="login-card">

            <!-- Back -->
            <button type="button" class="back-btn" id="backBtn">
                <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13 8H3M7 4 3 8l4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                <span>Back</span>
            </button>

            <!-- Brand -->
            <div class="brand-row">
                <div class="brand-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M12 3 1 9l11 6 9-4.91V17h2V9L12 3zm0 13.18L5 12.4V10.1l7 3.82 7-3.82v2.3l-7 3.78zM5 14.5v3c0 .8 3.13 2.5 7 2.5s7-1.7 7-2.5v-3l-7 3.82L5 14.5z" />
                    </svg>
                </div>
                <span class="brand-name">RateMyTeacher</span>
            </div>

            <h1 class="title">Welcome back</h1>

            <!-- Form -->
            <form id="loginForm" novalidate>

                <div class="field-block">
                    <div class="field-header">
                        <label for="schoolEmail" class="field-label">School Email or Username</label>
                        <span class="field-tag">Institutional ID</span>
                    </div>
                    <div class="input-icon-wrap">
                        <span class="leading-icon">
                            <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="8" cy="8" r="6.5" stroke="currentColor" stroke-width="1.2" />
                                <path
                                    d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Zm0 0v.8a1.3 1.3 0 0 0 2.6 0V8a5.1 5.1 0 1 0-2 4.06"
                                    stroke="currentColor" stroke-width="1.2" stroke-linecap="round" />
                            </svg>
                        </span>
                        <input type="text" class="form-control" id="schoolEmail" placeholder="student@university.edu"
                            required>
                    </div>
                </div>

                <div class="field-block">
                    <div class="field-header">
                        <label for="password" class="field-label">Password</label>
                        <a href="#" class="field-link">Forgot password?</a>
                    </div>
                    <div class="input-icon-wrap">
                        <span class="leading-icon">
                            <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="3" y="7" width="10" height="7" rx="1.2" stroke="currentColor"
                                    stroke-width="1.2" />
                                <path d="M5 7V4.5a3 3 0 0 1 6 0V7" stroke="currentColor" stroke-width="1.2" />
                            </svg>
                        </span>
                        <input type="password" class="form-control" id="password" placeholder="••••••••" required
                            style="padding-right:44px;">
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

                <button type="submit" class="btn-submit">
                    <span>Sign In</span>
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>
            </form>

            <!-- Trust callout -->
            <div class="trust-callout">
                <div class="trust-icon">
                    <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 1.5 2.5 3.5v3.8c0 3.4 2.3 6.4 5.5 7.2 3.2-.8 5.5-3.8 5.5-7.2V3.5L8 1.5Z"
                            stroke="currentColor" stroke-width="1.2" stroke-linejoin="round" />
                        <path d="m5.8 8 1.5 1.5 2.9-3.1" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="trust-text">
                    <div class="t1">Verified Student &amp; Faculty Reviews</div>
                    <div class="t2">Institutional credentials keep our ratings honest.</div>
                </div>
            </div>

            <!-- Sign up redirect -->
            <div class="signup-row">
                Don't have an account? <a href="#">Sign up now</a>
            </div>

        </div>
    </div>

    <script>
        // Back
        document.getElementById('backBtn').addEventListener('click', () => {
            if (window.history.length > 1) window.history.back();
            else alert('Back pressed — wire this up to your previous page/route.');
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

        // Basic client-side validation on submit
        document.getElementById('loginForm').addEventListener('submit', (e) => {
            e.preventDefault();
            const form = e.target;
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }
            alert('Signed in! (This is a front-end demo — hook this up to your backend.)');
        });
    </script>
</body>

</html>