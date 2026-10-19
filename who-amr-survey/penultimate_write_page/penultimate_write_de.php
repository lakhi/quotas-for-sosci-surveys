$country = (int) value('SD02');
if ($country == 1) {
    put('QC01_01', 1);
    debug('QC write OK. SD02=' . $country . '. Saved to QC01_01.');
}

$g = (int) value('SD05');
if ($g == 1 || $g == 2) {
    put('QC02_01', $g);
    debug('QC write QC02_01=' . $g);
} else {
    debug('QC write QC02_01 skipped. SD05=' . $g);
}

$age = (int) value('SD03_01');
if ($age >= 18 && $age <= 24) {
    $ageGroup = 1;
} elseif ($age <= 34) {
    $ageGroup = 2;
} elseif ($age <= 44) {
    $ageGroup = 3;
} elseif ($age <= 54) {
    $ageGroup = 4;
} elseif ($age <= 64) {
    $ageGroup = 5;
} else {
    $ageGroup = 6;
}
put('QC02_02', $ageGroup);
debug('QC write QC02_02=' . $ageGroup);

$edu = (int) value('SD08');
if ($edu == 1 || $edu == 2) {
    put('QC02_03', 1);
    debug('QC write QC02_03=1');
} elseif ($edu >= 3 && $edu <= 7) {
    put('QC02_03', 2);
    debug('QC write QC02_03=2');
} else {
    debug('QC write QC02_03 skipped. SD08=' . $edu);
}
