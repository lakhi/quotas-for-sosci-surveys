// ===== TEST QUOTA CONFIG (TEST project only: total = 10) =====
$QUOTA_TOTAL   = 10;
$PANELIST_ID   = (string) value('DA03_RV1');
// TEST only: debug-mode starts carry no SPM_PANELIST_ID, so use a per-case test ID instead
if ($PANELIST_ID == '') {
    $PANELIST_ID = 'TEST-' . caseNumber();
    debug('FALLBACK ID USED: ' . $PANELIST_ID);
}
$QUOTAFULL_URL = 'https://survey.maximiles.com/quotasfull?p=98327_21a4610c&m=' . urlencode($PANELIST_ID);

// Completed count (via internal variable)
$finished = statistic('count', 'QC01_01', 1);

debug('DA03_RV1 (panelist ID)=' . $PANELIST_ID);
debug($finished);
debug($QUOTA_TOTAL);

if ($finished >= $QUOTA_TOTAL) {
    debug('quota TOTAL reached! redirecting to: ' . $QUOTAFULL_URL);
    redirect($QUOTAFULL_URL);
}
