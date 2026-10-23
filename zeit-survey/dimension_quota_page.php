// ===== ZEIT QUOTA CONFIG (110%, n=1100) =====
$PANELIST_ID   = (string) value('DA03_RV1');
if ($PANELIST_ID == '') {
    debug('WARNING: DA03_RV1 empty (no SPM_PANELIST_ID in the start URL) - Bilendi links get an empty &m=');
}
$QUOTAFULL_URL = 'https://survey.maximiles.com/quotasfull?p=98327_21a4610c&m=' . urlencode($PANELIST_ID);
$SCREENOUT_URL = 'https://survey.maximiles.com/screenout?p=98327_69cadaeb&m=' . urlencode($PANELIST_ID);
$QUOTA_GENDER  = [1 => 550, 2 => 550];
$QUOTA_AGE     = [1 => 198, 2 => 187, 3 => 176, 4 => 231, 5 => 308];
$QUOTA_EDU     = [1 => 319, 2 => 341, 3 => 440];
// keys are the SD06 answer codes, not the official Bundesland numbers
$QUOTA_REGION  = [
    1  => 44,  // Schleswig-Holstein
    2  => 22,  // Hamburg
    3  => 22,  // Mecklenburg-Vorpommern
    4  => 33,  // Brandenburg
    5  => 44,  // Berlin
    6  => 22,  // Sachsen-Anhalt
    7  => 11,  // Bremen
    8  => 110, // Niedersachsen
    9  => 242, // Nordrhein-Westfalen
    10 => 77,  // Hessen
    11 => 33,  // Thüringen
    12 => 55,  // Sachsen
    13 => 55,  // Rheinland-Pfalz
    14 => 11,  // Saarland
    15 => 143, // Baden-Württemberg
    16 => 176, // Bayern
];

// ===== DEBUG RAW VALUES =====
debug('RAW: DA03_RV1=' . $PANELIST_ID . ', SD05=' . value('SD05') . ', SD03_01=' . value('SD03_01') . ', SD08=' . value('SD08') . ', SD06=' . value('SD06'));


// current respondent groups (group 0 = not counted for that dimension)
$g      = (int) value('SD05');
$age    = (int) value('SD03_01');
$edu    = (int) value('SD08');
$region = (int) value('SD06');

// gender group (3-6 = non-binary/other/no answer)
if ($g == 1 || $g == 2) {
    $genderGroup = $g;
} else {
    $genderGroup = 0;
}

// age group (under 18 = screenout, 76+ counts in the top band)
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

// education group (SD08 codes are not in order: 10 = gering, 9 = hoch, 8 = no answer)
if ($edu == 1 || $edu == 2 || $edu == 10) {
    $eduGroup = 1;
} elseif ($edu == 3 || $edu == 4) {
    $eduGroup = 2;
} elseif ($edu == 5 || $edu == 6 || $edu == 7 || $edu == 9) {
    $eduGroup = 3;
} else {
    $eduGroup = 0;
}

// region group (SD06 codes 1-16 are the Bundeslaender, 17 = lives outside Germany = screenout)
if ($region >= 1 && $region <= 16) {
    $regionGroup = $region;
} else {
    $regionGroup = 0;
}

// counts
$countGender     = 0;
$countAge        = 0;
$countEdu        = 0;
$countRegion     = 0;
$genderQuotaFull = false;
$ageQuotaFull    = false;
$eduQuotaFull    = false;
$regionQuotaFull = false;

if ($genderGroup > 0) {
    $countGender     = statistic('count', 'QC01_02', $genderGroup);
    $genderQuotaFull = ($countGender >= $QUOTA_GENDER[$genderGroup]);
}

if ($ageGroup > 0) {
    $countAge     = statistic('count', 'QC01_03', $ageGroup);
    $ageQuotaFull = ($countAge >= $QUOTA_AGE[$ageGroup]);
}

if ($eduGroup > 0) {
    $countEdu     = statistic('count', 'QC01_04', $eduGroup);
    $eduQuotaFull = ($countEdu >= $QUOTA_EDU[$eduGroup]);
}

if ($regionGroup > 0) {
    $countRegion     = statistic('count', 'QC01_05', $regionGroup);
    $regionQuotaFull = ($countRegion >= $QUOTA_REGION[$regionGroup]);
}

// debug counts and hits
if ($genderQuotaFull) {
    $genderHitStr = 'YES';
} else {
    $genderHitStr = 'NO';
}

if ($ageQuotaFull) {
    $ageHitStr = 'YES';
} else {
    $ageHitStr = 'NO';
}

if ($eduQuotaFull) {
    $eduHitStr = 'YES';
} else {
    $eduHitStr = 'NO';
}

if ($regionQuotaFull) {
    $regionHitStr = 'YES';
} else {
    $regionHitStr = 'NO';
}

debug('GROUPS: gender=' . $genderGroup . ', ageGroup=' . $ageGroup . ', eduGroup=' . $eduGroup . ', regionGroup=' . $regionGroup);
debug('COUNTS: gender=' . $countGender . ', age=' . $countAge . ', edu=' . $countEdu . ', region=' . $countRegion);
debug('HITS: gender=' . $genderHitStr . ', age=' . $ageHitStr . ', edu=' . $eduHitStr . ', region=' . $regionHitStr);

// one if/elseif so a respondent can only ever get one redirect
if ($age < 18 || $region == 17) {
    debug('TRIGGER: outside target group (SD03_01=' . $age . ', SD06=' . $region . ') - redirecting to screenout: ' . $SCREENOUT_URL);
    redirect($SCREENOUT_URL);
} elseif ($genderQuotaFull || $ageQuotaFull || $eduQuotaFull || $regionQuotaFull) {
    debug('TRIGGER: ZEIT dimension quota hit - redirecting to quotafull: ' . $QUOTAFULL_URL);
    redirect($QUOTAFULL_URL);
}
