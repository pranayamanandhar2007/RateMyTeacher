<?php
require_once __DIR__ . '/../config/dbconn.php';
require_once __DIR__ . '/../includes/admin.php';
requireAdmin();

$teachers = (int)one($conn, 'SELECT COUNT(*) c FROM teachers')['c'];
$rc = one($conn, "SELECT SUM(status='Approved') a, SUM(status='Pending') p FROM ratings");
$approved = (int)$rc['a']; $pending = (int)$rc['p'];
$depts = (int)one($conn, 'SELECT COUNT(DISTINCT course) c FROM subjects')['c'];

// Last 7 days: new reviews vs. reports
$days = [];
for ($i = 6; $i >= 0; $i--) { $days[date('Y-m-d', strtotime("-$i day"))] = ['new' => 0, 'rep' => 0]; }
foreach (q($conn, 'SELECT review_date d, COUNT(*) c FROM ratings WHERE review_date >= CURDATE() - INTERVAL 6 DAY GROUP BY review_date') as $r) {
    if (isset($days[$r['d']])) { $days[$r['d']]['new'] = (int)$r['c']; }
}
foreach (q($conn, 'SELECT DATE(created_at) d, COUNT(*) c FROM rating_reports WHERE created_at >= CURDATE() - INTERVAL 6 DAY GROUP BY DATE(created_at)') as $r) {
    if (isset($days[$r['d']])) { $days[$r['d']]['rep'] = (int)$r['c']; }
}
$max = max(1, ...array_map(fn($d) => max($d['new'], $d['rep']), array_values($days)));
$pts = function (string $k) use ($days, $max): string {
    $o = []; $i = 0;
    foreach ($days as $d) { $o[] = round($i++ * 100, 1) . ',' . round(180 - $d[$k] / $max * 160, 1); }
    return implode(' ', $o);
};

$cover = q($conn, 'SELECT s.course, COUNT(DISTINCT ts.t_id) n FROM subjects s JOIN teacher_subjects ts ON ts.subject_id = s.subject_id GROUP BY s.course ORDER BY n DESC LIMIT 4');
$spot = one($conn, "SELECT t.t_id, t.t_name, AVG(r.quality_rating) aq, AVG(r.difficulty_rating) ad, COUNT(*) n,
        100 * SUM(LOWER(r.take_again) = 'yes') / COUNT(*) wt
    FROM teachers t JOIN ratings r ON r.t_id = t.t_id AND r.status = 'Approved'
    GROUP BY t.t_id HAVING n >= 3 ORDER BY aq DESC, n DESC LIMIT 1");
$queue = q($conn, "SELECT r.r_id, r.quality_rating, r.difficulty_rating, r.review, r.review_date, t.t_id, t.t_name, s.subject_name, s.course,
        (SELECT reason FROM rating_reports rr WHERE rr.r_id = r.r_id ORDER BY rr.created_at DESC LIMIT 1) reason
    FROM ratings r JOIN teachers t ON t.t_id = r.t_id JOIN subjects s ON s.subject_id = r.subject_id
    WHERE r.status = 'Pending' ORDER BY r.review_date DESC, r.r_id DESC LIMIT 3");
$flagged = (int)one($conn, "SELECT COUNT(DISTINCT r_id) c FROM rating_reports WHERE COALESCE(status,'Pending') <> 'Resolved'")['c'];
$barColors = ['#00288e', '#0060ac', '#64a8fe', '#b8c4ff'];

adminHeader('Dashboard', 'dashboard', $conn);
?>
<div class="adm-head">
    <div>
        <div class="eyebrow"><b>Executive overview</b><span class="sep"></span><?= date('F j, Y') ?></div>
        <h1 class="adm-title">Moderation &amp; Analytics</h1>
        <p class="adm-sub">Status monitoring, faculty metrics and the priority moderation queue.</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a class="btn-a primary" href="professors.php?add=1"><i class="fa-solid fa-plus"></i> Add New Professor</a>
        <a class="btn-a soft" href="reviews.php?tab=flagged"><i class="fa-solid fa-flag"></i> Review Flagged Posts
            <?php if ($flagged): ?><span class="pill solid"><?= $flagged ?></span><?php endif; ?></a>
    </div>
</div>

<div class="stats">
    <div class="card-a stat"><div class="lbl">Total Professors <span class="ico"><i class="fa-solid fa-chalkboard-user"></i></span></div>
        <div class="num"><?= number_format($teachers) ?></div><div class="note">Active directory</div></div>
    <div class="card-a stat"><div class="lbl">Approved Reviews <span class="ico"><i class="fa-solid fa-comments"></i></span></div>
        <div class="num"><?= number_format($approved) ?></div><div class="note">Publicly visible</div></div>
    <div class="card-a stat alert"><div class="lbl">Pending Moderation <span class="ico"><i class="fa-solid fa-bell"></i></span></div>
        <div class="num"><?= number_format($pending) ?></div><div class="note tone-bad">Awaiting approval</div></div>
    <div class="card-a stat"><div class="lbl">Active Departments <span class="ico"><i class="fa-solid fa-building-columns"></i></span></div>
        <div class="num"><?= number_format($depts) ?></div><div class="note">Across all courses</div></div>
</div>

<div class="grid-12">
    <section class="card-a card-p c-8">
        <div class="d-flex justify-content-between flex-wrap gap-2 mb-3">
            <div>
                <h2 class="card-h">Submissions &amp; Reports</h2>
                <p class="card-d">New reviews versus reports filed, last 7 days</p>
            </div>
            <div class="legend"><span><i style="background:var(--a-brand)"></i>New reviews</span><span><i style="background:#64a8fe"></i>Reports</span></div>
        </div>
        <div class="chart">
            <svg viewBox="0 0 600 190" preserveAspectRatio="none" role="img" aria-label="Reviews and reports per day">
                <?php foreach ([20, 66, 112, 158] as $y): ?><line x1="0" x2="600" y1="<?= $y ?>" y2="<?= $y ?>" stroke="#eceef0" stroke-width="1" vector-effect="non-scaling-stroke"/><?php endforeach; ?>
                <polygon points="0,180 <?= $pts('new') ?> 600,180" fill="#00288e" fill-opacity=".08"/>
                <polyline points="<?= $pts('new') ?>" fill="none" stroke="#00288e" stroke-width="3" vector-effect="non-scaling-stroke" stroke-linejoin="round"/>
                <polyline points="<?= $pts('rep') ?>" fill="none" stroke="#64a8fe" stroke-width="3" vector-effect="non-scaling-stroke" stroke-linejoin="round"/>
            </svg>
            <div class="days"><?php foreach (array_keys($days) as $d): ?><span><?= date('D', strtotime($d)) ?></span><?php endforeach; ?></div>
        </div>
    </section>

    <div class="c-4 stack">
        <section class="card-a card-p">
            <div class="d-flex justify-content-between align-items-center"><h2 class="card-h">Department Coverage</h2><span class="pill">Top <?= max(1, count($cover)) ?></span></div>
            <p class="card-d mt-1">Rated professors across courses.</p>
            <div class="cov">
                <?php foreach ($cover as $i => $c): $pc = $teachers ? round($c['n'] / $teachers * 100) : 0; ?>
                    <div>
                        <div class="row-t"><span><?= h($c['course']) ?></span><span><?= (int)$c['n'] ?> faculty (<?= $pc ?>%)</span></div>
                        <div class="bar-bg"><div style="width:<?= $pc ?>%;background:<?= $barColors[$i] ?>"></div></div>
                    </div>
                <?php endforeach; ?>
                <?php if (!$cover): ?><div class="card-d">No professors are assigned to subjects yet.</div><?php endif; ?>
            </div>
        </section>

        <section class="card-a card-p">
            <h2 class="card-h">Faculty Spotlight</h2>
            <p class="card-d">Highest rated professor (3+ reviews).</p>
            <?php if ($spot): ?>
                <a class="spot" href="../pages/teacher-profile.php?t_id=<?= (int)$spot['t_id'] ?>" style="color:inherit">
                    <span class="avatar"><?= h(initials($spot['t_name'])) ?></span>
                    <div>
                        <div style="font-weight:600;font-size:16px;color:var(--a-brand)"><?= h($spot['t_name']) ?></div>
                        <div class="tone-mid" style="font-weight:700"><i class="fa-solid fa-star"></i> <?= number_format($spot['aq'], 2) ?>
                            <small style="color:var(--a-soft);font-weight:400">(<?= (int)$spot['n'] ?> reviews)</small></div>
                    </div>
                </a>
                <div class="d-flex justify-content-between pt-3 card-d">
                    <span>Would take again: <b style="color:var(--a-brand)"><?= round($spot['wt']) ?>%</b></span>
                    <span>Difficulty: <b style="color:var(--a-brand)"><?= number_format($spot['ad'], 1) ?> / 5</b></span>
                </div>
            <?php else: ?><div class="card-d mt-3">Not enough approved reviews yet.</div><?php endif; ?>
        </section>
    </div>
</div>

<section class="tbl-card mb-4">
    <div class="tbl-head">
        <div>
            <div class="d-flex align-items-center gap-2"><h2 class="card-h">Pending Moderation Queue</h2>
                <?php if ($pending): ?><span class="pill solid"><?= $pending ?> Action Needed</span><?php endif; ?></div>
            <p class="card-d">Newest submissions awaiting an administrative decision.</p>
        </div>
        <a class="btn-a soft" href="reviews.php">Open full queue</a>
    </div>
    <div class="tbl-scroll"><table class="tbl tbl-muted">
        <thead><tr><th>Professor / Course</th><th>Submitted</th><th>Scores</th><th>Review snippet</th><th>Flag reason</th><th class="r">Actions</th></tr></thead>
        <tbody>
        <?php foreach ($queue as $r): ?>
            <tr>
                <td><div class="d-flex align-items-center gap-3"><span class="avatar sm"><?= h(initials($r['t_name'])) ?></span>
                    <div><a class="fw-semibold" style="color:var(--a-brand)" href="../pages/teacher-profile.php?t_id=<?= (int)$r['t_id'] ?>"><?= h($r['t_name']) ?></a>
                    <div class="sub"><?= h($r['course']) ?> • <?= h($r['subject_name']) ?></div></div></div></td>
                <td><?= h(date('M j, Y', strtotime($r['review_date']))) ?></td>
                <td><div class="sub"><b class="<?= $r['quality_rating'] <= 2 ? 'tone-bad' : 'tone-mid' ?>"><?= (int)$r['quality_rating'] ?>.0</b> Quality</div>
                    <div class="sub"><b style="color:var(--a-brand)"><?= (int)$r['difficulty_rating'] ?>.0</b> Difficulty</div></td>
                <td class="snip">“<?= h(mb_strimwidth($r['review'], 0, 90, '…')) ?>”</td>
                <td><?= $r['reason'] ? '<span class="pill red">' . h(mb_strimwidth($r['reason'], 0, 40, '…')) . '</span>' : '<span class="pill">New submission</span>' ?></td>
                <td class="r">
                    <?php foreach (['approve' => ['fa-circle-check', 'ok', 'Approve'], 'reject' => ['fa-circle-xmark', 'del', 'Reject']] as $act => [$ic, $cl, $tt]): ?>
                        <form method="post" action="reviews.php" class="d-inline"><?= csrfField() ?>
                            <input type="hidden" name="id" value="<?= (int)$r['r_id'] ?>"><input type="hidden" name="act" value="<?= $act ?>"><input type="hidden" name="back" value="dashboard.php">
                            <button class="icon-btn <?= $cl ?>" title="<?= $tt ?>" aria-label="<?= $tt ?>"><i class="fa-solid <?= $ic ?>"></i></button></form>
                    <?php endforeach; ?>
                    <a class="icon-btn" title="Open in queue" href="reviews.php"><i class="fa-solid fa-eye"></i></a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table></div>
    <?php if (!$queue): ?><div class="empty">Nothing is waiting for review.</div><?php endif; ?>
    <div class="tbl-foot"><span>Showing <?= count($queue) ?> of <?= $pending ?> pending items</span></div>
</section>
<?php adminFooter(); ?>
