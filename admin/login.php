<?php
session_start();
require_once __DIR__ . '/../config/dbconn.php';

if (!empty($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$errorMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $statement = mysqli_prepare($conn, 'SELECT admin_id, username, password FROM admin WHERE username = ? LIMIT 1');
    mysqli_stmt_bind_param($statement, 's', $username);
    mysqli_stmt_execute($statement);
    $admin = mysqli_fetch_assoc(mysqli_stmt_get_result($statement));
    mysqli_stmt_close($statement);

    if ($admin && password_verify($password, $admin['password'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = (int) $admin['admin_id'];
        $_SESSION['admin_name'] = $admin['username'];
        header('Location: dashboard.php');
        exit;
    }

    $errorMessage = 'Invalid administrator username or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login · Rate My Teacher</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="../css/admin.css" rel="stylesheet">
</head>
<body class="adm adm-login">
    <main class="login-card bg-white rounded shadow-sm">
        <div class="adm-brand px-0 border-0 mb-4">
            <div class="adm-logo"><i class="fa-solid fa-graduation-cap"></i></div>
            <div><div class="brand-t">Rate My Teacher</div><div class="brand-s">Admin Portal</div></div>
        </div>
        <h1 class="h3 mb-1" style="color:var(--a-brand)">Administrator sign in</h1>
        <p class="text-muted mb-4">Use your administrator account to continue.</p>
        <form method="post" action="login.php">
            <label for="username">Username</label>
            <input class="form-control" id="username" name="username" required autofocus>
            <label for="password">Password</label>
            <input class="form-control" type="password" id="password" name="password" required>
            <?php if ($errorMessage): ?><div class="alert alert-danger mt-3" role="alert"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
            <button class="btn btn-primary w-100 mt-4" type="submit">Sign in</button>
        </form>
    </main>
</body>
</html>
