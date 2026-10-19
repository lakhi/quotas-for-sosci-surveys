// Single-country survey: every complete counts towards the total
put('QC01_01', 1);
debug('QC write OK. Saved to QC01_01.');

$g = (int) value('SD05');
if ($g == 1 || $g == 2) {
    put('QC01_02', $g);
    debug('QC write QC01_02=' . $g);
} else {
    debug('QC write QC01_02 skipped. SD05=' . $g);
}

$age = (int) value('SD03_01');
if ($age < 18) {
    $ageGroup = 0;
} elseif ($age <= 29) {
    $ageGroup = 1;
} elseif ($age <= 39) {
    $ageGroup = 2;
} elseif ($age <= 49) {
    $ageGroup = 3;
} elseif ($age <= 59) {
    $ageGroup = 4;
} else {
    $ageGroup = 5;
}
if ($ageGroup > 0) {
    put('QC01_03', $ageGroup);
    debug('QC write QC01_03=' . $ageGroup);
} else {
    debug('QC write QC01_03 skipped. SD03_01=' . $age);
}

$edu = (int) value('SD08');
if ($edu == 1 || $edu == 2 || $edu == 10) {
    put('QC01_04', 1);
    debug('QC write QC01_04=1');
} elseif ($edu == 3 || $edu == 4) {
    put('QC01_04', 2);
    debug('QC write QC01_04=2');
} elseif ($edu == 5 || $edu == 6 || $edu == 7 || $edu == 9) {
    put('QC01_04', 3);
    debug('QC write QC01_04=3');
} else {
    debug('QC write QC01_04 skipped. SD08=' . $edu);
}

$region = (int) value('SD06');
if ($region >= 1 && $region <= 16) {
    put('QC01_05', $region);
    debug('QC write QC01_05=' . $region);
} else {
    debug('QC write QC01_05 skipped. SD06=' . $region);
}
