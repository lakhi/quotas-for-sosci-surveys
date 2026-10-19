// ===== SERBIA QUOTA CONFIG =====
$QUOTA_URL_SR    = 'https://webs.norstatsurveys.com/end/1d37c4b3c27740d881490df8f5420853/QuotaFull';
$QUOTA_SR_GENDER = [1 => 486, 2 => 514];
$QUOTA_SR_AGE    = [1 => 98, 2 => 162, 3 => 167, 4 => 164, 5 => 186, 6 => 223];
$QUOTA_SR_EDU    = [1 => 191, 2 => 809];

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
$countAge = statistic('count', 'QC04_02', $ageGroup);

if ($eduGroup == 1 || $eduGroup == 2) {
    $countEdu = statistic('count', 'QC04_03', $eduGroup);
} else {
    $countEdu = 0;
}

$genderQuotaFull = false;
$countGender     = 0;
if ($g == 1 || $g == 2) {
    $countGender     = statistic('count', 'QC04_01', $g);
    $genderQuotaFull = ($countGender >= $QUOTA_SR_GENDER[$g]);
}

$ageQuotaFull = ($countAge >= $QUOTA_SR_AGE[$ageGroup]);

if ($eduGroup == 1 || $eduGroup == 2) {
    $eduQuotaFull = ($countEdu >= $QUOTA_SR_EDU[$eduGroup]);
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
    debug('TRIGGER: SR gender/age quota hit - redirecting (edu not enforced)');
    redirect($QUOTA_URL_SR);
}
