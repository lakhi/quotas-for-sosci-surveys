// ===== UZBEKISTAN QUOTA CONFIG =====
$QUOTA_URL_UZ    = 'https://webs.norstatsurveys.com/end/e334c1c82a7c4fd390c8b132a23a00a1/QuotaFull';
$QUOTA_UZ_GENDER = [1 => 504, 2 => 496];
$QUOTA_UZ_AGE    = [1 => 268, 2 => 256, 3 => 187, 4 => 139, 5 => 58, 6 => 92];
$QUOTA_UZ_EDU    = [1 => 218, 2 => 782];

// ===== DEBUG RAW VALUES =====
debug('RAW: SD05=' . value('SD05') . ', SD03_01=' . value('SD03_01') . ', SD08=' . value('SD08'));

// current respondent groups
$g   = (int) value('SD05');
$age = (int) value('SD03_01');
$edu = (int) value('SD08');

// age group
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

// education group
if ($edu == 1 || $edu == 2) {
    $eduGroup = 1;
} elseif ($edu >= 3 && $edu <= 7) {
    $eduGroup = 2;
} else {
    $eduGroup = 0;
}

// counts
$countAge = statistic('count', 'QC05_02', $ageGroup);

if ($eduGroup == 1 || $eduGroup == 2) {
    $countEdu = statistic('count', 'QC05_03', $eduGroup);
} else {
    $countEdu = 0;
}

$genderQuotaFull = false;
$countGender     = 0;
if ($g == 1 || $g == 2) {
    $countGender     = statistic('count', 'QC05_01', $g);
    $genderQuotaFull = ($countGender >= $QUOTA_UZ_GENDER[$g]);
}

$ageQuotaFull = ($countAge >= $QUOTA_UZ_AGE[$ageGroup]);

if ($eduGroup == 1 || $eduGroup == 2) {
    $eduQuotaFull = ($countEdu >= $QUOTA_UZ_EDU[$eduGroup]);
} else {
    $eduQuotaFull = false;
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

debug('GROUPS: g=' . $g . ', ageGroup=' . $ageGroup . ', eduGroup=' . $eduGroup);
debug('COUNTS: gender=' . $countGender . ', age=' . $countAge . ', edu=' . $countEdu);
debug('HITS: gender=' . $genderHitStr . ', age=' . $ageHitStr . ', edu=' . $eduHitStr);

