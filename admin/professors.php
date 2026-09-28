<?php
require_once __DIR__ . '/../config/dbconn.php';
require_once __DIR__ . '/../includes/admin.php';
requireAdmin();

/* ---------- Writes ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfCheck();
    $act = $_POST['act'] ?? '';
    $tid = (int)($_POST['t_id'] ?? 0);
    try {
        if ($act === 'save') {
            $name = trim($_POST['name'] ?? '');
            $subjects = array_values(array_unique(array_filter(array_map('intval', (array)($_POST['subjects'] ?? [])))));
            if (mb_strlen($name) < 2 || mb_strlen($name) > 100) {
                flash('Professor name must be 2–100 characters.', 'danger');
            } else {
                if ($tid) {
                    x($conn, 'UPDATE teachers SET t_name = ? WHERE t_id = ?', 'si', [$name, $tid]);
                    x($conn, 'DELETE FROM teacher_subjects WHERE t_id = ?', 'i', [$tid]);
                } else {
                    x($conn, 'INSERT INTO teachers (t_name) VALUES (?)', 's', [$name]);
                    $tid = (int)$conn->insert_id;
                }
                foreach ($subjects as $sid) {
                    x($conn, 'INSERT IGNORE INTO teacher_subjects (t_id, subject_id) VALUES (?, ?)', 'ii', [$tid, $sid]);
                }
                flash('Professor saved.');
            }
        } elseif ($act === 'delete' && $tid) {
            if ((int)one($conn, 'SELECT COUNT(*) c FROM ratings WHERE t_id = ?', 'i', [$tid])['c'] > 0) {
                flash('This professor has reviews and cannot be deleted.', 'danger');
            } else {
                x($conn, 'DELETE FROM teacher_subjects WHERE t_id = ?', 'i', [$tid]);
                x($conn, 'DELETE FROM teachers WHERE t_id = ?', 'i', [$tid]);
                flash('Professor deleted.');
            }
        }
    } catch (mysqli_sql_exception $e) {
        flash('Database error: the change was not saved.', 'danger');
    }
    header('Location: ' . safeBack($_POST['back'] ?? '', 'professors.php'));
    exit;
}

/* ---------- Filters ---------- */
$qs = trim($_GET['q'] ?? '');
$course = trim($_GET['course'] ?? '');
$sorts = [
    'highest' => ['Highest Rating (5.0 → 1.0)', 'aq IS NULL, aq DESC, n DESC'],
    'lowest'  => ['Lowest Rating (1.0 → 5.0)', 'aq IS NULL, aq ASC, n DESC'],
    'reviews' => ['Most Reviewed', 'n DESC, t.t_name ASC'],
    'name'    => ['Name (A → Z)', 't.t_name ASC'],
];
$sort = isset($sorts[$_GET['sort'] ?? '']) ? $_GET['sort'] : 'highest';
$page = max(1, (int)($_GET['page'] ?? 1));
$per = 10;

$where = ['1=1']; $types = ''; $par = [];
if ($qs !== '') { $where[] = 't.t_name LIKE ?'; $types .= 's'; $par[] = '%' . $qs . '%'; }
if ($course !== '') {
    $where[] = 'EXISTS (SELECT 1 FROM teacher_subjects x1 JOIN subjects s1 ON s1.subject_id = x1.subject_id WHERE x1.t_id = t.t_id AND s1.course = ?)';
    $types .= 's'; $par[] = $course;
}
$whereSql = implode(' AND ', $where);
$total = (int)one($conn, "SELECT COUNT(*) c FROM teachers t WHERE $whereSql", $types, $par)['c'];
$pages = max(1, (int)ceil($total / $per));
$page = min($page, $pages);

$base = "SELECT t.t_id, t.t_name, COUNT(r.r_id) n, AVG(r.quality_rating) aq,
    (SELECT GROUP_CONCAT(DISTINCT s2.course ORDER BY s2.course SEPARATOR ', ') FROM teacher_subjects x2 JOIN subjects s2 ON s2.subject_id = x2.subject_id WHERE x2.t_id = t.t_id) courses,
    (SELECT GROUP_CONCAT(x3.subject_id) FROM teacher_subjects x3 WHERE x3.t_id = t.t_id) subject_ids,
    (SELECT COUNT(*) FROM teacher_subjects x4 WHERE x4.t_id = t.t_id) subj_n
    FROM teachers t LEFT JOIN ratings r ON r.t_id = t.t_id AND r.status = 'Approved'
    WHERE $whereSql GROUP BY t.t_id ORDER BY {$sorts[$sort][1]}";

/* ---------- CSV export (same filters, no paging) ---------- */
if (isset($_GET['export'])) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="professors.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['Professor', 'Departments', 'Subjects', 'Avg quality', 'Approved reviews']);
    foreach (q($conn, $base, $types, $par) as $r) {
        fputcsv($out, [$r['t_name'], $r['courses'], $r['subj_n'], $r['aq'] !== null ? round($r['aq'], 2) : '', $r['n']]);
    }
    exit;
}

$rows = q($conn, $base . ' LIMIT ' . $per . ' OFFSET ' . (($page - 1) * $per), $types, $par);
$courses = array_column(q($conn, 'SELECT DISTINCT course FROM subjects ORDER BY course'), 'course');
$allSubjects = q($conn, 'SELECT subject_id, subject_name, course, semester FROM subjects ORDER BY course, semester, subject_name');

$stat = one($conn, "SELECT (SELECT COUNT(*) FROM teachers) t, AVG(CASE WHEN status='Approved' THEN quality_rating END) aq,
    SUM(status='Pending') p, SUM(status='Approved') a FROM ratings");
$params = array_filter(['q' => $qs, 'course' => $course, 'sort' => $sort === 'highest' ? '' : $sort], fn($v) => $v !== '');

adminHeader('Manage Professors', 'professors', $conn);
?>
<div class="adm-head">
    <div>
        <div class="eyebrow"><span class="pill blue" style="letter-spacing:.05em;text-transform:uppercase">Faculty administration</span></div>
        <h1 class="adm-title">Manage Professors</h1>
        <p class="adm-sub">Add, edit and remove professors, and see how each one is rated across the platform.</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a class="btn-a soft" href="?<?= h(http_build_query($params + ['export' => 1])) ?>"><i class="fa-solid fa-download"></i> Export CSV</a>
        <button class="btn-a primary js-edit" type="button" data-id="0" data-name="" data-subjects=""><i class="fa-solid fa-plus"></i> Add New Professor</button>
    </div>
</div>

<div class="stats">
    <div class="card-a stat"><div class="lbl">Total Faculty Listed <span class="ico"><i class="fa-solid fa-address-card"></i></span></div><div class="num"><?= number_format((int)$stat['t']) ?></div></div>
    <div class="card-a stat"><div class="lbl">Platform Average Rating <span class="ico"><i class="fa-solid fa-star"></i></span></div>
        <div class="num"><?= $stat['aq'] !== null ? number_format($stat['aq'], 2) : '—' ?><small>/ 5.0</small></div></div>
    <div class="card-a stat alert"><div class="lbl">Pending Moderation <span class="ico"><i class="fa-solid fa-clipboard-list"></i></span></div><div class="num"><?= number_format((int)$stat['p']) ?><small>awaiting</small></div></div>
    <div class="card-a stat"><div class="lbl">Approved Reviews <span class="ico"><i class="fa-solid fa-comments"></i></span></div><div class="num"><?= number_format((int)$stat['a']) ?></div></div>
</div>

<form class="bar" method="get">
    <div class="fld"><i class="fa-solid fa-magnifying-glass"></i><input type="search" name="q" value="<?= h($qs) ?>" placeholder="Filter by name..." aria-label="Filter by name"></div>
    <select name="course" onchange="this.form.submit()" aria-label="Department">
        <option value="">All Departments</option>
        <?php foreach ($courses as $c): ?><option <?= $c === $course ? 'selected' : '' ?>><?= h($c) ?></option><?php endforeach; ?>
    </select>
    <span class="sortlbl">Sort By:</span>
    <select name="sort" class="sort" onchange="this.form.submit()" aria-label="Sort">
        <?php foreach ($sorts as $k => [$label]): ?><option value="<?= $k ?>" <?= $k === $sort ? 'selected' : '' ?>><?= h($label) ?></option><?php endforeach; ?>
    </select>
</form>

<section class="tbl-card">
    <div class="tbl-scroll"><table class="tbl">
        <thead><tr><th>Professor</th><th>Department</th><th>Student rating</th><th>Reviews</th><th class="r">Actions</th></tr></thead>
        <tbody>
        <?php foreach ($rows as $r): ?>
            <tr>
                <td><div class="d-flex align-items-center gap-3"><span class="avatar"><?= h(initials($r['t_name'])) ?></span>
                    <div><a class="name" href="../pages/teacher-profile.php?t_id=<?= (int)$r['t_id'] ?>"><?= h($r['t_name']) ?></a>
                    <div class="sub"><?= (int)$r['subj_n'] ?> subject<?= $r['subj_n'] == 1 ? '' : 's' ?> assigned</div></div></div></td>
                <td><span style="font-weight:600;color:var(--a-brand)"><?= h($r['courses'] ?: '—') ?></span></td>
                <td><?= rateBadge($r['aq'] !== null ? (float)$r['aq'] : null) ?></td>
                <td><b style="color:var(--a-brand)"><?= (int)$r['n'] ?></b> <span class="sub">approved</span></td>
                <td class="r">
                    <a class="icon-btn" title="View public profile" href="../pages/teacher-profile.php?t_id=<?= (int)$r['t_id'] ?>"><i class="fa-solid fa-eye"></i></a>
                    <button class="icon-btn js-edit" type="button" title="Edit" data-id="<?= (int)$r['t_id'] ?>" data-name="<?= h($r['t_name']) ?>" data-subjects="<?= h($r['subject_ids']) ?>"><i class="fa-solid fa-pen-to-square"></i></button>
                    <form method="post" class="d-inline" onsubmit="return confirm('Delete this professor?')"><?= csrfField() ?>
                        <input type="hidden" name="act" value="delete"><input type="hidden" name="t_id" value="<?= (int)$r['t_id'] ?>"><input type="hidden" name="back" value="<?= h(selfUrl()) ?>">
                        <button class="icon-btn del" title="Delete" aria-label="Delete"><i class="fa-solid fa-trash"></i></button></form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table></div>
    <?php if (!$rows): ?><div class="empty">No professors match these filters.</div><?php endif; ?>
    <div class="tbl-foot">
        <span class="pg-info">Showing <b><?= $total ? ($page - 1) * $per + 1 : 0 ?> – <?= min($total, $page * $per) ?></b> of <b><?= $total ?></b> faculty records</span>
        <?= pagerHtml($page, $pages, $params) ?>
    </div>
</section>

<div class="modal fade" id="profModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable"><form class="modal-content" method="post"><?= csrfField() ?>
        <input type="hidden" name="act" value="save"><input type="hidden" name="t_id" id="pf-id"><input type="hidden" name="back" value="<?= h(selfUrl()) ?>">
        <div class="modal-header"><h2 class="card-h" id="pf-title">Add New Professor</h2><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <div class="modal-body">
            <label class="form-label fw-semibold" for="pf-name">Full name</label>
            <input class="form-control mb-3" id="pf-name" name="name" required minlength="2" maxlength="100">
            <div class="form-label fw-semibold">Subjects taught</div>
            <?php $cur = null; foreach ($allSubjects as $s): if ($s['course'] !== $cur): $cur = $s['course']; ?>
                <div class="rv-lbl mt-2 mb-1"><?= h($cur) ?></div>
            <?php endif; ?>
                <div class="form-check"><input class="form-check-input pf-sub" type="checkbox" name="subjects[]" id="sub<?= (int)$s['subject_id'] ?>" value="<?= (int)$s['subject_id'] ?>">
                    <label class="form-check-label" for="sub<?= (int)$s['subject_id'] ?>"><?= h($s['subject_name']) ?> <span class="sub">· Sem <?= h($s['semester']) ?></span></label></div>
            <?php endforeach; ?>
        </div>
        <div class="modal-footer"><button type="button" class="btn-a soft" data-bs-dismiss="modal">Cancel</button><button class="btn-a primary">Save</button></div>
    </form></div>
</div>
<?php
adminFooter(<<<'JS'
const modal = () => bootstrap.Modal.getOrCreateInstance(document.getElementById('profModal'));
document.querySelectorAll('.js-edit').forEach(b => b.addEventListener('click', () => {
    const ids = (b.dataset.subjects || '').split(',');
    document.getElementById('pf-id').value = b.dataset.id;
    document.getElementById('pf-name').value = b.dataset.name;
    document.getElementById('pf-title').textContent = b.dataset.id === '0' ? 'Add New Professor' : 'Edit Professor';
    document.querySelectorAll('.pf-sub').forEach(c => c.checked = ids.includes(c.value));
    modal().show();
}));
if (new URLSearchParams(location.search).get('add')) document.querySelector('.js-edit[data-id="0"]').click();
JS);
