<?php
require_once __DIR__ . '/../config/dbconn.php';
require_once __DIR__ . '/../includes/admin.php';
requireAdmin();

// A "department" is a course (BCA, BBA, ...) as stored in subjects.course.
$rows = q($conn, "SELECT s.course,
        COUNT(DISTINCT s.subject_id) subjects,
        COUNT(DISTINCT ts.t_id) profs,
        (SELECT COUNT(*) FROM ratings r JOIN subjects s2 ON s2.subject_id = r.subject_id WHERE s2.course = s.course AND r.status = 'Approved') reviews,
        (SELECT AVG(r.quality_rating) FROM ratings r JOIN subjects s3 ON s3.subject_id = r.subject_id WHERE s3.course = s.course AND r.status = 'Approved') aq
    FROM subjects s LEFT JOIN teacher_subjects ts ON ts.subject_id = s.subject_id
    GROUP BY s.course ORDER BY s.course");

adminHeader('Departments', 'departments', $conn);
?>
<div class="adm-head">
    <div>
        <div class="eyebrow"><span class="pill blue" style="letter-spacing:.05em;text-transform:uppercase">Faculty administration</span></div>
        <h1 class="adm-title">Departments</h1>
        <p class="adm-sub">Courses with their subjects, assigned professors and review activity.</p>
    </div>
</div>
<section class="tbl-card">
    <div class="tbl-scroll"><table class="tbl">
        <thead><tr><th>Department</th><th>Subjects</th><th>Professors</th><th>Approved reviews</th><th>Avg quality</th><th class="r">Actions</th></tr></thead>
        <tbody>
        <?php foreach ($rows as $r): ?>
            <tr>
                <td><span class="name"><?= h($r['course']) ?></span></td>
                <td><?= (int)$r['subjects'] ?></td>
                <td><?= (int)$r['profs'] ?></td>
                <td><?= (int)$r['reviews'] ?></td>
                <td><?= rateBadge($r['aq'] !== null ? (float)$r['aq'] : null) ?></td>
                <td class="r"><a class="icon-btn" title="View professors" href="professors.php?course=<?= urlencode($r['course']) ?>"><i class="fa-solid fa-eye"></i></a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table></div>
    <?php if (!$rows): ?><div class="empty">No subjects have been added yet.</div><?php endif; ?>
    <div class="tbl-foot"><span><?= count($rows) ?> department<?= count($rows) === 1 ? '' : 's' ?></span></div>
</section>
<?php adminFooter(); ?>
