// ===== QUOTA CONFIG =====
$QUOTA_LT     = 1000;
$QUOTA_URL_LT = 'https://webs.norstatsurveys.com/end/245a908c9de14a4e9f959286f322dcf2/QuotaFull';

// Completed count for LT (via internal variable)
$finishedLT = statistic('count', 'QC01_01', 2);

if ($finishedLT >= $QUOTA_LT) {
    debug('quota LT reached! redirecting to: ' . $QUOTA_URL_LT);
    redirect($QUOTA_URL_LT);
}
