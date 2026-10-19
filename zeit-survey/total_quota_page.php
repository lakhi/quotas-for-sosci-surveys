// ===== QUOTA CONFIG =====
$QUOTA_TOTAL   = 1000;
$QUOTAFULL_URL = 'https://survey.maximiles.com/static-quotasfull?p=98327_21a4610c';

// Completed count (via internal variable)
$finished = statistic('count', 'QC01_01', 1);

debug($finished);
debug($QUOTA_TOTAL);

if ($finished >= $QUOTA_TOTAL) {
    debug('quota TOTAL reached! redirecting to: ' . $QUOTAFULL_URL);
    redirect($QUOTAFULL_URL);
}
