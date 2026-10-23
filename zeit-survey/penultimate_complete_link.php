// Second PHP code element on the penultimate page (below the QC writes).
// The Last Page cannot run PHP, so the panelist ID for its Complete link is set here as a placeholder:
// final_page.html uses %panelistID% in the href.
$PANELIST_ID = (string) value('DA03_RV1');
if ($PANELIST_ID == '') {
    debug('WARNING: DA03_RV1 empty (no SPM_PANELIST_ID in the start URL) - Complete link gets an empty &m=');
}
replace('%panelistID%', urlencode($PANELIST_ID));

debug('COMPLETE link: https://survey.maximiles.com/complete?p=98327_8107c9fe&m=' . urlencode($PANELIST_ID));
