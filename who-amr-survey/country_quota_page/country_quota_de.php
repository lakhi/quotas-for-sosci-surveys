// ===== QUOTA CONFIG =====
$QUOTA_DE     = 1000;
$QUOTA_URL_DE = 'https://webs.norstatsurveys.com/end/e8ca6da139244b20b52f7efc79f9b7a3/QuotaFull';

// Completed count for DE (via internal variable)
$finishedDE = statistic('count', 'QC01_01', 1);

debug($finishedDE);
debug($QUOTA_DE);
debug(value('QUESTNNR'));

if ($finishedDE >= $QUOTA_DE) {
    debug('quota DE reached! redirecting to: ' . $QUOTA_URL_DE);
    redirect($QUOTA_URL_DE);
}
