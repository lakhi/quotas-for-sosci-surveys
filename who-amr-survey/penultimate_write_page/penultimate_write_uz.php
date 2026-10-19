$country = (int) value('SD02');
if ($country == 4) {
    put('QC01_01', 4);
    debug('QC write OK. SD02=' . $country . '. Saved to QC01_01.');
}

$g = (int) value('SD05');
if ($g == 1 || $g == 2) {
    put('QC05_01', $g);
    debug('QC write QC05_01=' . $g);
} else {
    debug('QC write QC05_01 skipped. SD05=' . $g);
}

$age = (int) value('SD03_01');
if ($age >= 18 && $age <= 29) {
    $ageGroup = 1;
} elseif ($age <= 39) {
    $ageGroup = 2;
} elseif ($age <= 49) {
    $ageGroup = 3;
} elseif ($age <= 59) {
    $ageGroup = 4;
} elseif ($age <= 64) {
    $ageGroup = 5;
} else {
    $ageGroup = 6;
}
put('QC05_02', $ageGroup);
debug('QC write QC05_02=' . $ageGroup);

$edu = (int) value('SD08');
if ($edu == 1 || $edu == 2) {
    put('QC05_03', 1);
    debug('QC write QC05_03=1');
} elseif ($edu >= 3 && $edu <= 7) {
    put('QC05_03', 2);
    debug('QC write QC05_03=2');
} else {
    debug('QC write QC05_03 skipped. SD08=' . $edu);
}