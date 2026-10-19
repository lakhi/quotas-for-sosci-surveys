// ===== QUOTA CONFIG =====
$QUOTA_SR     = 1000;
$QUOTA_URL_SR = 'https://webs.norstatsurveys.com/end/1d37c4b3c27740d881490df8f5420853/QuotaFull';

// Completed count for SR (via internal variable)
$finishedSR = statistic('count', 'QC01_01', 3);

if ($finishedSR >= $QUOTA_SR) {
    debug('quota SR reached! redirecting to: ' . $QUOTA_URL_SR);
    redirect($QUOTA_URL_SR);
}
