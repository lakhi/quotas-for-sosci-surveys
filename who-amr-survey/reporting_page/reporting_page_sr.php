// SR quota reporting block for debug mode (read-only, no writes or redirects).

$QUOTA_SR_COUNTRY = 1000;
$QUOTA_SR_GENDER  = [
    1 => 486,
    2 => 514,
];
$QUOTA_SR_AGE = [
    1 => 98,
    2 => 162,
    3 => 167,
    4 => 164,
    5 => 186,
    6 => 223,
];
$QUOTA_SR_EDU = [
    1 => 191,
    2 => 809,
];

$GENDER_LABELS = [
    1 => 'Male',
    2 => 'Female',
];
$AGE_LABELS = [
    1 => '18-24',
    2 => '25-34',
    3 => '35-44',
    4 => '45-54',
    5 => '55-64',
    6 => '65+',
];
$EDU_LABELS = [
    1 => 'Low (SD08=1-2)',
    2 => 'High (SD08=3-7)',
];

/**
 * Emit one debug line using a variable so SoSci does not display verbose expression text.
 */
function emitDebug($text)
{
    $line = (string) $text;
    debug($line);
}

/**
 * Print one compact quota status line.
 */
function debugQuotaLine($label, $filled, $total)
{
    $filled = (int) $filled;
    $total  = (int) $total;
    $left   = $total - $filled;
    if ($left < 0) {
        $left = 0;
    }

    $line = $label . ': ' . $filled . '/' . $total . ' (left ' . $left . ')';
    emitDebug($line);
}

emitDebug('SR QUOTA REPORT');

$countryFilled = statistic('count', 'QC01_01', 3);
debugQuotaLine('COUNTRY SR', $countryFilled, $QUOTA_SR_COUNTRY);

emitDebug('GENDER');
foreach ($QUOTA_SR_GENDER as $group => $total) {
    $filled = statistic('count', 'QC04_01', $group);
    $label  = 'G' . $group . ' ' . $GENDER_LABELS[$group];
    debugQuotaLine($label, $filled, $total);
}

emitDebug('AGE');
foreach ($QUOTA_SR_AGE as $group => $total) {
    $filled = statistic('count', 'QC04_02', $group);
    $label  = 'A' . $group . ' ' . $AGE_LABELS[$group];
    debugQuotaLine($label, $filled, $total);
}

emitDebug('EDUCATION');
foreach ($QUOTA_SR_EDU as $group => $total) {
    $filled = statistic('count', 'QC04_03', $group);
    $label  = 'E' . $group . ' ' . $EDU_LABELS[$group];
    debugQuotaLine($label, $filled, $total);
}
