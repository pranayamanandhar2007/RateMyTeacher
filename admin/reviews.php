<?php
require_once __DIR__ . '/../config/dbconn.php';
require_once __DIR__ . '/../includes/admin.php';
requireAdmin();
$me = (int)$_SESSION['admin_id'];

/* ---------- Moderation actions ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfCheck();
    $act = $_POST['act'] ?? '';
    $ids = array_values(array_filter(array_map('intval', (array)($_POST['ids'] ?? []))));
    if (isset($_POST['id'])) { $ids = [(int)$_POST['id']]; }
    $back = safeBack($_POST['back'] ?? '', 'reviews.php');

    if ($ids && in_array($act, ['approve', 'reject'], true)) {
        $status = $act === 'approve' ? 'Approved' : 'Rejected';
        $in = implode(',', array_fill(0, count($ids), '?'));
        $t = str_repeat('i', count($ids));
        x($conn, "UPDATE ratings SET status = ?, admin_id = ? WHERE r_id IN ($in)", 'si' . $t, array_merge([$status, $me], $ids));
        x($conn, "UPDATE rating_reports SET status = 'Resolved' WHERE r_id IN ($in)", $t, $ids);
        flash(count($ids) . ' review' . (count($ids) === 1 ? '' : 's') . ' ' . strtolower($status) . '.');
    } elseif ($ids && $act === 'redact') {
        $text = trim($_POST['review'] ?? '');
        if ($text === '' || mb_strlen($text) > 2000) {
            flash('Review text must be 1–2000 characters.', 'danger');
        } else {
            x($conn, "UPDATE ratings SET review = ?, status = 'Approved', admin_id = ? WHERE r_id = ?", 'sii', [$text, $me, $ids[0]]);
            x($conn, "UPDATE rating_reports SET status = 'Resolved' WHERE r_id = ?", 'i', [$ids[0]]);
            flash('Review redacted and approved.');
        }
    }
    header('Location: ' . $back);
    exit;
}

/* ---------- Filters ---------- */
$tab = in_array($_GET['tab'] ?? '', ['pending', 'flagged', 'approved', 'rejected'], true) ? $_GET['tab'] : 'pending';
$qs = trim($_GET['q'] ?? '');
$course = trim($_GET['course'] ?? '');
$daysF = (int)($_GET['days'] ?? 0);
if (!in_array($daysF, [7, 30], true)) { $daysF = 0; }
$page = max(1, (int)($_GET['page'] ?? 1));
$per = 10;

$open = "EXISTS (SELECT 1 FROM rating_reports rr WHERE rr.r_id = r.r_id AND COALESCE(rr.status,'Pending') <> 'Resolved')";
$where = [['pending' => "r.status = 'Pending'", 'approved' => "r.status = 'Approved'", 'rejected' => "r.status = 'Rejected'", 'flagged' => $open][$tab]];
$types = ''; $par = [];
if ($qs !== '') {
    $where[] = '(t.t_name LIKE ? OR s.subject_name LIKE ? OR r.review LIKE ?)';
    $types .= 'sss'; array_push($par, "%$qs%", "%$qs%", "%$qs%");
}
if ($course !== '') { $where[] = 's.course = ?'; $types .= 's'; $par[] = $course; }
if ($daysF) { $where[] = 'r.review_date >= CURDATE() - INTERVAL ? DAY'; $types .= 'i'; $par[] = $daysF; }
$from = 'FROM ratings r JOIN teachers t ON t.t_id = r.t_id JOIN subjects s ON s.subject_id = r.subject_id WHERE ' . implode(' AND ', $where);

$total = (int)one($conn, "SELECT COUNT(*) c $from", $types, $par)['c'];
$pages = max(1, (int)ceil($total / $per));
$page = min($page, $pages);
$rows = q($conn, "SELECT r.r_id, r.quality_rating, r.difficulty_rating, r.take_again, r.review, r.review_date, r.semester, r.status,
        t.t_id, t.t_name, s.subject_name, s.course,
        (SELECT COUNT(*) FROM rating_reports rr WHERE rr.r_id = r.r_id) flags
    $from ORDER BY r.review_date DESC, r.r_id DESC LIMIT $per OFFSET " . (($page - 1) * $per), $types, $par);

// Report reasons for the visible cards. Reporter identity is never selected.
$reasons = [];
if ($rows) {
    $in = implode(',', array_map('intval', array_column($rows, 'r_id')));
    foreach (q($conn, "SELECT r_id, reason FROM rating_reports WHERE r_id IN ($in) AND COALESCE(status,'Pending') <> 'Resolved' ORDER BY created_at DESC") as $x) {
        $reasons[$x['r_id']][] = $x['reason'];
    }
}

$cnt = one($conn, "SELECT SUM(status='Pending') p, SUM(status='Approved') a, SUM(status='Rejected') j,
    SUM(review_date >= CURDATE() - INTERVAL 6 DAY) wk,
    (SELECT COUNT(DISTINCT r_id) FROM rating_reports WHERE COALESCE(status,'Pending') <> 'Resolved') f,
    (SELECT COUNT(*) FROM rating_reports WHERE created_at >= NOW() - INTERVAL 7 DAY) wf FROM ratings");
$courses = array_column(q($conn, 'SELECT DISTINCT course FROM subjects ORDER BY course'), 'course');
$tabs = ['pending' => ['Pending Reviews', $cnt['p'], ''], 'flagged' => ['Flagged by Users', $cnt['f'], 'flag'], 'approved' => ['Approved', $cnt['a'], ''], 'rejected' => ['Rejected', $cnt['j'], '']];
$params = array_filter(['tab' => $tab, 'q' => $qs, 'course' => $course, 'days' => $daysF ?: ''], fn($v) => $v !== '');

adminHeader('Moderate Reviews', 'reviews', $conn);
?>
<div class="adm-head">
    <div>
        <div class="eyebrow"><b>Moderation</b><span class="sep" style="background:var(--a-line)"></span>Anonymous review queue</div>
        <h1 class="adm-title">Review Moderation Queue</h1>
    </div>
    <div class="dropdown">
        <button class="btn-a primary" id="batchBtn" type="button" data-bs-toggle="dropdown" disabled><i class="fa-solid fa-list-check"></i> Batch Action (<span id="batchN">0</span>)</button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><button class="dropdown-item" form="batchForm" name="act" value="approve">Approve selected</button></li>
            <li><button class="dropdown-item text-danger" form="batchForm" name="act" value="reject">Reject selected</button></li>
        </ul>
    </div>
</div>
<form id="batchForm" method="post"><?= csrfField() ?><input type="hidden" name="back" value="<?= h(selfUrl()) ?>"></form>

<div class="stats" style="grid-template-columns:repeat(3,1fr)">
    <div class="card-a stat"><div class="lbl">Pending Reviews</div><div class="num"><?= (int)$cnt['p'] ?></div></div>
    <div class="card-a stat"><div class="lbl">Reviews This Week</div><div class="num"><?= (int)$cnt['wk'] ?></div></div>
    <div class="card-a stat"><div class="lbl">Weekly Flag Volume</div><div class="num"><?= (int)$cnt['wf'] ?></div></div>
</div>

<nav class="tabs" aria-label="Review status">
    <?php foreach ($tabs as $k => [$label, $n, $cls]): ?>
        <a class="<?= $k === $tab ? 'on' : '' ?> <?= $cls ?>" href="?tab=<?= $k ?>"><?= $label ?> <span class="n"><?= number_format((int)$n) ?></span></a>
    <?php endforeach; ?>
</nav>

<form class="bar" method="get">
    <input type="hidden" name="tab" value="<?= h($tab) ?>">
    <div class="fld" style="max-width:none;flex:2 1 300px"><i class="fa-solid fa-magnifying-glass"></i><input type="search" name="q" value="<?= h($qs) ?>" placeholder="Search by professor, subject or review text..."></div>
    <select name="course" aria-label="Department"><option value="">All Departments</option>
        <?php foreach ($courses as $c): ?><option <?= $c === $course ? 'selected' : '' ?>><?= h($c) ?></option><?php endforeach; ?></select>
    <select name="days" aria-label="Date range">
        <option value="0">All time</option><option value="7" <?= $daysF === 7 ? 'selected' : '' ?>>Last 7 days</option><option value="30" <?= $daysF === 30 ? 'selected' : '' ?>>Last 30 days</option></select>
    <button class="btn-a primary" style="padding:10px 16px" aria-label="Apply filters"><i class="fa-solid fa-filter"></i></button>
</form>

<?php foreach ($rows as $r): $flags = (int)$r['flags']; ?>
<article class="rv <?= $flags ? 'flagged' : '' ?>">
    <div class="rv-top">
        <div class="rv-who">
            <input type="checkbox" name="ids[]" value="<?= (int)$r['r_id'] ?>" form="batchForm" class="js-pick" aria-label="Select review">
            <div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <a class="card-h" style="font-weight:700" href="../pages/teacher-profile.php?t_id=<?= (int)$r['t_id'] ?>"><?= h($r['t_name']) ?></a>
                    <span class="pill blue"><?= h($r['subject_name']) ?></span><span class="chip-mono"><?= h($r['course']) ?></span>
                </div>
                <div class="card-d">Semester <?= h($r['semester']) ?></div>
            </div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <?php if ($flags): ?><span class="pill red"><i class="fa-solid fa-flag"></i> Flagged <?= $flags ?> time<?= $flags === 1 ? '' : 's' ?></span>
            <?php elseif ($r['status'] === 'Pending'): ?><span class="pill">Initial moderation</span>
            <?php else: ?><span class="pill <?= $r['status'] === 'Approved' ? 'blue' : 'red' ?>"><?= h($r['status']) ?></span><?php endif; ?>
            <span style="font-family:ui-monospace,monospace;font-size:13px;color:var(--a-soft)">ID: #R-<?= (int)$r['r_id'] ?></span>
        </div>
    </div>

    <div class="rv-meta">
        <div><div class="k">Submitted</div><div class="v"><?= h(date('F j, Y', strtotime($r['review_date']))) ?></div></div>
        <div><div class="k">Quality &amp; Difficulty</div><div class="v">
            <span style="color:<?= $r['quality_rating'] <= 2 ? 'var(--a-err)' : 'var(--a-brand)' ?>">Quality: <?= (int)$r['quality_rating'] ?>.0<small>/5</small></span>
            <span style="color:var(--a-warn);margin-left:10px">Diff: <?= (int)$r['difficulty_rating'] ?>.0<small>/5</small></span></div></div>
        <div><div class="k">Would take again</div><div class="v"><?= strtolower($r['take_again']) === 'yes' ? 'Yes' : 'No' ?></div></div>
    </div>

    <div>
        <div class="rv-lbl">Submitted review text</div>
        <div class="rv-text"><?= h($r['review']) ?></div>
    </div>

    <?php if (!empty($reasons[$r['r_id']])): ?>
    <div>
        <div class="rv-lbl">Report reasons</div>
        <ul class="rv-reasons"><?php foreach ($reasons[$r['r_id']] as $why): ?><li><?= h($why) ?></li><?php endforeach; ?></ul>
    </div>
    <?php endif; ?>

    <div class="rv-act">
        <?php if ($r['status'] !== 'Approved'): ?>
        <form method="post"><?= csrfField() ?><input type="hidden" name="id" value="<?= (int)$r['r_id'] ?>"><input type="hidden" name="back" value="<?= h(selfUrl()) ?>">
            <button class="btn-a primary sm" name="act" value="approve"><i class="fa-solid fa-circle-check"></i> Approve Review</button></form>
        <?php endif; ?>
        <button class="btn-a soft sm js-redact" type="button" data-id="<?= (int)$r['r_id'] ?>" data-review="<?= h($r['review']) ?>"><i class="fa-solid fa-pen-to-square"></i> Redact &amp; Approve</button>
        <?php if ($r['status'] !== 'Rejected'): ?>
        <form method="post"><?= csrfField() ?><input type="hidden" name="id" value="<?= (int)$r['r_id'] ?>"><input type="hidden" name="back" value="<?= h(selfUrl()) ?>">
            <button class="btn-a danger sm" name="act" value="reject"><i class="fa-solid fa-xmark"></i> Reject</button></form>
        <?php endif; ?>
    </div>
</article>
<?php endforeach; ?>
<?php if (!$rows): ?><div class="card-a empty">No reviews in this view.</div><?php endif; ?>

<div class="pager-box d-flex justify-content-between align-items-center flex-wrap gap-2">
    <span class="card-d pg-info" style="font-weight:500">Showing <b><?= $total ? ($page - 1) * $per + 1 : 0 ?> – <?= min($total, $page * $per) ?></b> of <b><?= $total ?></b> submissions</span>
    <?= pagerHtml($page, $pages, $params) ?>
</div>

<div class="modal fade" id="redactModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog"><form class="modal-content" method="post"><?= csrfField() ?>
        <input type="hidden" name="act" value="redact"><input type="hidden" name="id" id="rd-id"><input type="hidden" name="back" value="<?= h(selfUrl()) ?>">
        <div class="modal-header"><h2 class="card-h">Redact &amp; Approve</h2><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <div class="modal-body"><p class="card-d mb-2">Remove personal or abusive details, then approve the edited review.</p>
            <textarea class="form-control" name="review" id="rd-text" rows="7" maxlength="2000" required></textarea></div>
        <div class="modal-footer"><button type="button" class="btn-a soft" data-bs-dismiss="modal">Cancel</button><button class="btn-a primary">Save &amp; Approve</button></div>
    </form></div>
</div>
<?php
adminFooter(<<<'JS'
document.querySelectorAll('.js-redact').forEach(b => b.addEventListener('click', () => {
    document.getElementById('rd-id').value = b.dataset.id;
    document.getElementById('rd-text').value = b.dataset.review;
    bootstrap.Modal.getOrCreateInstance(document.getElementById('redactModal')).show();
}));
const picks = () => [...document.querySelectorAll('.js-pick:checked')].length;
document.querySelectorAll('.js-pick').forEach(c => c.addEventListener('change', () => {
    document.getElementById('batchN').textContent = picks();
    document.getElementById('batchBtn').disabled = picks() === 0;
}));
JS);
