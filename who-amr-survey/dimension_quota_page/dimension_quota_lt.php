// ===== LITHUANIA QUOTA CONFIG =====
$QUOTA_URL_LT    = 'https://webs.norstatsurveys.com/end/245a908c9de14a4e9f959286f322dcf2/QuotaFull';
$QUOTA_LT_GENDER = [1 => 473, 2 => 527];
$QUOTA_LT_AGE    = [1 => 113, 2 => 155, 3 => 162, 4 => 184, 5 => 159, 6 => 227];
$QUOTA_LT_EDU    = [1 => 119, 2 => 881];

// ===== DEBUG RAW VALUES =====
debug('RAW: SD05=' . value('SD05') . ', SD03_01=' . value('SD03_01') . ', SD08=' . value('SD08'));

// current respondent groups
$g   = (int) value('SD05');
$age = (int) value('SD03_01');
$edu = (int) value('SD08');

// age group
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

// education group
if ($edu == 1 || $edu == 2) {
    $eduGroup = 1;
} elseif ($edu >= 3 && $edu <= 7) {
    $eduGroup = 2;
} else {
    $eduGroup = 0;
}

// counts
$countAge = statistic('count', 'QC03_02', $ageGroup);

if ($eduGroup == 1 || $eduGroup == 2) {
    $countEdu = statistic('count', 'QC03_03', $eduGroup);
} else {
    $countEdu = 0;
}

$genderQuotaFull = false;
$countGender     = 0;
if ($g == 1 || $g == 2) {
    $countGender     = statistic('count', 'QC03_01', $g);
    $genderQuotaFull = ($countGender >= $QUOTA_LT_GENDER[$g]);
}

$ageQuotaFull = ($countAge >= $QUOTA_LT_AGE[$ageGroup]);

if ($eduGroup == 1 || $eduGroup == 2) {
    $eduQuotaFull = ($countEdu >= $QUOTA_LT_EDU[$eduGroup]);
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

if ($genderQuotaFull || $ageQuotaFull) {
    debug('TRIGGER: LT gender/age quota hit - redirecting (edu not enforced)');
    redirect($QUOTA_URL_LT);
}
