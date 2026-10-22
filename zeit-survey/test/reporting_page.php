// ZEIT TEST quota reporting block for debug mode (read-only, no writes or redirects).
// TEST project only: every cell = 1, total = 10.

$QUOTA_TOTAL  = 10;
$QUOTA_GENDER = [
    1 => 1,
    2 => 1,
];
$QUOTA_AGE = [
    1 => 1,
    2 => 1,
    3 => 1,
    4 => 1,
    5 => 1,
];
$QUOTA_EDU = [
    1 => 1,
    2 => 1,
    3 => 1,
];
$QUOTA_REGION = [
    1  => 1,
    2  => 1,
    3  => 1,
    4  => 1,
    5  => 1,
    6  => 1,
    7  => 1,
    8  => 1,
    9  => 1,
    10 => 1,
    11 => 1,
    12 => 1,
    13 => 1,
    14 => 1,
    15 => 1,
    16 => 1,
];

$GENDER_LABELS = [
    1 => 'Mann',
    2 => 'Frau',
];
$AGE_LABELS = [
    1 => '18-29',
    2 => '30-39',
    3 => '40-49',
    4 => '50-59',
    5 => '60+',
];
$EDU_LABELS = [
    1 => 'gering (SD08=1,2,10)',
    2 => 'mittel (SD08=3,4)',
    3 => 'hoch (SD08=5,6,7,9)',
];
$REGION_LABELS = [
    1  => 'Schleswig-Holstein',
    2  => 'Hamburg',
    3  => 'Mecklenburg-Vorpommern',
    4  => 'Brandenburg',
    5  => 'Berlin',
    6  => 'Sachsen-Anhalt',
    7  => 'Bremen',
    8  => 'Niedersachsen',
    9  => 'Nordrhein-Westfalen',
    10 => 'Hessen',
    11 => 'Thüringen',
    12 => 'Sachsen',
    13 => 'Rheinland-Pfalz',
    14 => 'Saarland',
    15 => 'Baden-Württemberg',
    16 => 'Bayern',
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

/**
 * Print how many completes are in no cell of a dimension (bypassed answers), so cells + this = TOTAL.
 */
function debugNotCounted($totalFilled, $counted)
{
    $notCounted = (int) $totalFilled - (int) $counted;
    $line       = 'not counted: ' . $notCounted;
    emitDebug($line);
}

emitDebug('ZEIT TEST QUOTA REPORT');

$totalFilled = statistic('count', 'QC01_01', 1);
debugQuotaLine('TOTAL', $totalFilled, $QUOTA_TOTAL);

emitDebug('GENDER');
$counted = 0;
foreach ($QUOTA_GENDER as $group => $total) {
    $filled  = statistic('count', 'QC01_02', $group);
    $counted = $counted + (int) $filled;
    $label   = 'G' . $group . ' ' . $GENDER_LABELS[$group];
    debugQuotaLine($label, $filled, $total);
}
debugNotCounted($totalFilled, $counted);

emitDebug('AGE');
$counted = 0;
foreach ($QUOTA_AGE as $group => $total) {
    $filled  = statistic('count', 'QC01_03', $group);
    $counted = $counted + (int) $filled;
    $label   = 'A' . $group . ' ' . $AGE_LABELS[$group];
    debugQuotaLine($label, $filled, $total);
}
debugNotCounted($totalFilled, $counted);

emitDebug('EDUCATION');
$counted = 0;
foreach ($QUOTA_EDU as $group => $total) {
    $filled  = statistic('count', 'QC01_04', $group);
    $counted = $counted + (int) $filled;
    $label   = 'E' . $group . ' ' . $EDU_LABELS[$group];
    debugQuotaLine($label, $filled, $total);
}
debugNotCounted($totalFilled, $counted);

emitDebug('REGION');
$counted = 0;
foreach ($QUOTA_REGION as $group => $total) {
    $filled  = statistic('count', 'QC01_05', $group);
    $counted = $counted + (int) $filled;
    $label   = 'R' . $group . ' ' . $REGION_LABELS[$group];
    debugQuotaLine($label, $filled, $total);
}
debugNotCounted($totalFilled, $counted);
