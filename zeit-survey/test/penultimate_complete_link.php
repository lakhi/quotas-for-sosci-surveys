// Second PHP code element on the penultimate page (below the QC writes).
// The Last Page cannot run PHP, so the panelist ID for its Complete link is set here as a placeholder:
// final_page.html uses %panelistID% in the href.
$PANELIST_ID = (string) value('DA03_RV1');
// TEST only: debug-mode starts carry no SPM_PANELIST_ID, so use a per-case test ID instead
if ($PANELIST_ID == '') {
    $PANELIST_ID = 'TEST-' . caseNumber();
    debug('FALLBACK ID USED: ' . $PANELIST_ID);
}
replace('%panelistID%', urlencode($PANELIST_ID));

debug('COMPLETE link: https://survey.maximiles.com/complete?p=98327_8107c9fe&m=' . urlencode($PANELIST_ID));
