// ZEIT quota reporting block for debug mode (read-only, no writes or redirects).

$QUOTA_TOTAL  = 1000;
$QUOTA_GENDER = [
    1 => 500,
    2 => 500,
];
$QUOTA_AGE = [
    1 => 180,
    2 => 170,
    3 => 160,
    4 => 210,
    5 => 280,
];
$QUOTA_EDU = [
    1 => 290,
    2 => 310,
    3 => 400,
];
$QUOTA_REGION = [
    1  => 40,
    2  => 20,
    3  => 20,
    4  => 30,
    5  => 40,
    6  => 20,
    7  => 10,
    8  => 100,
    9  => 220,
    10 => 70,
    11 => 30,
    12 => 50,
    13 => 50,
    14 => 10,
    15 => 130,
    16 => 160,
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

emitDebug('ZEIT QUOTA REPORT');

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
