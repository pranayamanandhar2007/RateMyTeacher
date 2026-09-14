<?php
$teacherId = filter_input(INPUT_GET, 't_id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$location = $teacherId ? 'rating.php?t_id=' . $teacherId : 'search.php';
header('Location: ' . $location, true, 301);
exit;
