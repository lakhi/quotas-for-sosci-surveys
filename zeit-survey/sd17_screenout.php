// PHP code element on the page AFTER the screening question SD17 (PHP runs before the page is shown,
// so it only sees answers from earlier pages). Replaces a SoSci filter with a hard-coded static Screenout URL,
// which cannot carry the panelist ID.
// TODO: set $SD17_SCREENOUT_CODE to SD17's screenout answer code (question catalog → SD17 → answer options).
$SD17_SCREENOUT_CODE = 0;
$PANELIST_ID   = (string) value('DA03_RV1');
$SCREENOUT_URL = 'https://survey.maximiles.com/screenout?p=98327_69cadaeb&m=' . urlencode($PANELIST_ID);

debug('RAW: DA03_RV1=' . $PANELIST_ID . ', SD17=' . value('SD17'));

if ((int) value('SD17') == $SD17_SCREENOUT_CODE) {
    debug('TRIGGER: SD17 screenout - redirecting to screenout: ' . $SCREENOUT_URL);
    redirect($SCREENOUT_URL);
}
