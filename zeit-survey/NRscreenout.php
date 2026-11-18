// ===== NR SCREENOUT (after pre-screening page) =====
$PANELIST_ID   = (string) value('DA03_RV1');
if ($PANELIST_ID == '') {
    debug('WARNING: DA03_RV1 empty (no SPM_PANELIST_ID in the start URL) - Bilendi links get an empty &m=');
}
$SCREENOUT_URL = 'https://survey.maximiles.com/screenout?p=175243_83181740&m=' . urlencode($PANELIST_ID);

// ===== DEBUG RAW VALUES =====
debug('RAW: DA03_RV1=' . $PANELIST_ID . ', NR01=' . value('NR01') . ', NR03=' . value('NR03') . ', NR04=' . value('NR04'));

$nr01 = (int) value('NR01');
$nr03 = (int) value('NR03');
$nr04 = (int) value('NR04');

// screenout conditions (from the filters)
$nr01Hit = ($nr01 == 1 || $nr01 == 2);
$nr03Hit = ($nr03 == 1);
$nr04Hit = ($nr04 == 2);

if ($nr01Hit) {
    $nr01HitStr = 'YES';
} else {
    $nr01HitStr = 'NO';
}

if ($nr03Hit) {
    $nr03HitStr = 'YES';
} else {
    $nr03HitStr = 'NO';
}

if ($nr04Hit) {
    $nr04HitStr = 'YES';
} else {
    $nr04HitStr = 'NO';
}

debug('HITS: NR01=' . $nr01HitStr . ', NR03=' . $nr03HitStr . ', NR04=' . $nr04HitStr);

// one if so a respondent can only ever get one redirect
if ($nr01Hit || $nr03Hit || $nr04Hit) {
    debug('TRIGGER: NR screenout (NR01=' . $nr01 . ', NR03=' . $nr03 . ', NR04=' . $nr04 . ') - redirecting to screenout: ' . $SCREENOUT_URL);
    redirect($SCREENOUT_URL);
}
