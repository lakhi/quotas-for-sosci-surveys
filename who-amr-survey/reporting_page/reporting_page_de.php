// DE quota reporting block for debug mode (read-only, no writes or redirects).

$QUOTA_DE_COUNTRY = 1000;
$QUOTA_DE_GENDER  = [
    1 => 493,
    2 => 507,
];
$QUOTA_DE_AGE = [
    1 => 92,
    2 => 150,
    3 => 146,
    4 => 199,
    5 => 162,
    6 => 251,
];
$QUOTA_DE_EDU = [
    1 => 218,
    2 => 782,
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

emitDebug('DE QUOTA REPORT');

$countryFilled = statistic('count', 'QC01_01', 1);
debugQuotaLine('COUNTRY DE', $countryFilled, $QUOTA_DE_COUNTRY);

emitDebug('GENDER');
foreach ($QUOTA_DE_GENDER as $group => $total) {
    $filled = statistic('count', 'QC02_01', $group);
    $label  = 'G' . $group . ' ' . $GENDER_LABELS[$group];
    debugQuotaLine($label, $filled, $total);
}

emitDebug('AGE');
foreach ($QUOTA_DE_AGE as $group => $total) {
    $filled = statistic('count', 'QC02_02', $group);
    $label  = 'A' . $group . ' ' . $AGE_LABELS[$group];
    debugQuotaLine($label, $filled, $total);
}

emitDebug('EDUCATION');
foreach ($QUOTA_DE_EDU as $group => $total) {
    $filled = statistic('count', 'QC02_03', $group);
    $label  = 'E' . $group . ' ' . $EDU_LABELS[$group];
    debugQuotaLine($label, $filled, $total);
}
