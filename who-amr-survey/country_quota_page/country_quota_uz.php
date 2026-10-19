// ===== QUOTA CONFIG =====
$QUOTA_UZ     = 1000;
$QUOTA_URL_UZ = 'https://webs.norstatsurveys.com/end/e334c1c82a7c4fd390c8b132a23a00a1/QuotaFull';

// Completed count for UZ (via internal variable)
$finishedUZ = statistic('count', 'QC01_01', 4);

if ($finishedUZ >= $QUOTA_UZ) {
    debug('quota UZ reached! redirecting to: ' . $QUOTA_URL_UZ);
    redirect($QUOTA_URL_UZ);
}
