<?php
// Shared admin shell. Every admin page: require this, call requireAdmin(), then adminHeader()/adminFooter().
require_once __DIR__ . '/../config/dbconn.php';
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function h($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

/** SELECT helper: prepared statement -> all rows. */
function q(mysqli $c, string $sql, string $types = '', array $p = []): array {
    $s = $c->prepare($sql);
    if ($types !== '') { $s->bind_param($types, ...$p); }
    $s->execute();
    $r = $s->get_result();
    $rows = $r ? $r->fetch_all(MYSQLI_ASSOC) : [];
    $s->close();
    return $rows;
}
/** Write helper: prepared statement -> affected rows. */
function x(mysqli $c, string $sql, string $types = '', array $p = []): int {
    $s = $c->prepare($sql);
    if ($types !== '') { $s->bind_param($types, ...$p); }
    $s->execute();
    $n = $s->affected_rows;
    $s->close();
    return $n;
}
function one(mysqli $c, string $sql, string $types = '', array $p = []): array { return q($c, $sql, $types, $p)[0] ?? []; }

function requireAdmin(): void {
    if (empty($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
}
function csrfToken(): string {
    if (empty($_SESSION['admin_csrf'])) { $_SESSION['admin_csrf'] = bin2hex(random_bytes(32)); }
    return $_SESSION['admin_csrf'];
}
function csrfField(): string { return '<input type="hidden" name="csrf" value="' . h(csrfToken()) . '">'; }
function csrfCheck(): void {
    if (!hash_equals($_SESSION['admin_csrf'] ?? '', $_POST['csrf'] ?? '')) { http_response_code(400); exit('Invalid request.'); }
}
function flash(string $msg, string $type = 'success'): void { $_SESSION['admin_flash'] = [$type, $msg]; }

/** Same-folder redirect target only (blocks open redirects). */
function safeBack(string $b, string $fallback): string {
    return preg_match('/^[a-z]+\.php(\?[\w=&%.+-]*)?$/i', $b) ? $b : $fallback;
}
function selfUrl(): string {
    return basename($_SERVER['PHP_SELF']) . (($_SERVER['QUERY_STRING'] ?? '') !== '' ? '?' . $_SERVER['QUERY_STRING'] : '');
}
function initials(string $name): string {
    $name = preg_replace('/^(dr|prof|mr|mrs|ms)\.?\s+/i', '', trim($name));
    $parts = preg_split('/\s+/', $name);
    $s = mb_substr($parts[0] ?? '', 0, 1) . (count($parts) > 1 ? mb_substr(end($parts), 0, 1) : '');
    return mb_strtoupper($s ?: '?');
}
function rateClass(?float $v): string { return $v === null ? 'none' : ($v >= 4.5 ? 'r-hi' : ($v >= 4 ? 'r-mid' : 'r-lo')); }
function rateBadge($v): string {
    if ($v === null) { return '<span class="rate none">—</span>'; }
    $v = (float)$v;
    return '<span class="rate ' . rateClass($v) . '"><i class="fa-solid fa-star" style="font-size:11px"></i> ' . number_format($v, 1) . '</span>';
}

/** Numbered pager. $params = current query params (without page). */
function pagerHtml(int $page, int $pages, array $params): string {
    if ($pages <= 1) { return ''; }
    $u = fn(int $n) => '?' . http_build_query($params + ['page' => $n]);
    $out = '<nav class="pager">';
    $out .= $page > 1 ? '<a href="' . h($u($page - 1)) . '">Previous</a>' : '<span class="off">Previous</span>';
    $shown = array_unique(array_filter([1, $page - 1, $page, $page + 1, $pages], fn($n) => $n >= 1 && $n <= $pages));
    sort($shown);
    $prev = 0;
    foreach ($shown as $n) {
        if ($n - $prev > 1) { $out .= '<span class="gap">…</span>'; }
        $out .= $n === $page ? '<span class="cur">' . $n . '</span>' : '<a href="' . h($u($n)) . '">' . $n . '</a>';
        $prev = $n;
    }
    $out .= $page < $pages ? '<a href="' . h($u($page + 1)) . '">Next</a>' : '<span class="off">Next</span>';
    return $out . '</nav>';
}

function adminHeader(string $title, string $active, mysqli $conn): void {
    $open = (int)(one($conn, "SELECT COUNT(DISTINCT r_id) c FROM rating_reports WHERE COALESCE(status,'Pending') <> 'Resolved'")['c'] ?? 0);
    $name = $_SESSION['admin_name'] ?? 'Admin';
    $nav = [
        'dashboard'   => ['dashboard.php',   'fa-table-columns',      'Overview / Dashboard'],
        'professors'  => ['professors.php',  'fa-chalkboard-user',    'Manage Professors'],
        'reviews'     => ['reviews.php',     'fa-comment-dots',       'Moderate Reviews'],
        'departments' => ['departments.php', 'fa-building-columns',   'Departments'],
    ];
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h($title) ?> · Rate My Teacher Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="../css/admin.css" rel="stylesheet">
</head>
<body class="adm">
<aside class="adm-side">
    <div class="adm-brand">
        <div class="adm-logo"><i class="fa-solid fa-graduation-cap"></i></div>
        <div><div class="brand-t">Rate My Teacher</div><div class="brand-s">Admin Portal</div></div>
    </div>
    <div class="adm-nav-label">Main management</div>
    <nav class="adm-nav">
        <?php foreach ($nav as $key => [$href, $icon, $label]): ?>
            <a href="<?= $href ?>" class="<?= $key === $active ? 'active' : '' ?>"><i class="fa-solid <?= $icon ?>"></i><?= $label ?></a>
        <?php endforeach; ?>
    </nav>
    <div class="adm-user"><div>
        <span class="avatar sm" style="width:32px;height:32px;background:var(--a-brand);color:#fff;border-radius:50%;font-size:12px"><?= h(initials($name)) ?></span>
        <div class="who text-truncate"><?= h($name) ?><small>Administrator</small></div>
        <a class="out" href="../pages/logout.php" title="Log out"><i class="fa-solid fa-right-from-bracket"></i></a>
    </div></div>
</aside>
<header class="adm-top">
    <form class="adm-search" action="professors.php" method="get" role="search">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="search" name="q" placeholder="Quick search professors..." aria-label="Search professors">
    </form>
    <div class="adm-top-r">
        <span class="pill blue" style="letter-spacing:.05em;text-transform:uppercase">Admin mode</span>
        <a class="adm-bell" href="reviews.php?tab=flagged" title="Open reports"><i class="fa-solid fa-bell"></i><?php if ($open): ?><span class="dot"></span><?php endif; ?></a>
    </div>
</header>
<main class="adm-main"><div class="adm-wrap">
<?php
    if (!empty($_SESSION['admin_flash'])) {
        [$type, $msg] = $_SESSION['admin_flash'];
        unset($_SESSION['admin_flash']);
        echo '<div class="alert-a ' . h($type) . '">' . h($msg) . '</div>';
    }
}

function adminFooter(string $js = ''): void {
    ?>
</div></main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php if ($js !== ''): ?><script><?= $js ?></script><?php endif; ?>
</body>
</html>
<?php
}
