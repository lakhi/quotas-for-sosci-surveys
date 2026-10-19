// UZ quota reporting block for debug mode (read-only, no writes or redirects).

$QUOTA_UZ_COUNTRY = 1000;
$QUOTA_UZ_GENDER  = [
    1 => 504,
    2 => 496,
];
$QUOTA_UZ_AGE = [
    1 => 268,
    2 => 256,
    3 => 187,
    4 => 139,
    5 => 58,
    6 => 92,
];
$QUOTA_UZ_EDU = [
    1 => 218,
    2 => 782,
];

$GENDER_LABELS = [
    1 => 'Male',
    2 => 'Female',
];
$AGE_LABELS = [
    1 => '18-29',
    2 => '30-39',
    3 => '40-49',
    4 => '50-59',
    5 => '60-64',
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

emitDebug('UZ QUOTA REPORT');

$countryFilled = statistic('count', 'QC01_01', 4);
debugQuotaLine('COUNTRY UZ', $countryFilled, $QUOTA_UZ_COUNTRY);

emitDebug('GENDER');
foreach ($QUOTA_UZ_GENDER as $group => $total) {
    $filled = statistic('count', 'QC05_01', $group);
    $label  = 'G' . $group . ' ' . $GENDER_LABELS[$group];
    debugQuotaLine($label, $filled, $total);
}

emitDebug('AGE');
foreach ($QUOTA_UZ_AGE as $group => $total) {
    $filled = statistic('count', 'QC05_02', $group);
    $label  = 'A' . $group . ' ' . $AGE_LABELS[$group];
    debugQuotaLine($label, $filled, $total);
}

emitDebug('EDUCATION');
foreach ($QUOTA_UZ_EDU as $group => $total) {
    $filled = statistic('count', 'QC05_03', $group);
    $label  = 'E' . $group . ' ' . $EDU_LABELS[$group];
    debugQuotaLine($label, $filled, $total);
}
