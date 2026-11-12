# ZEIT Survey – TEST project version

For the separate **TEST - ZEIT-Project** in SoSci only. Never paste these files into the live project.

## What differs from `zeit-survey/`

- Every quota cell = **1** (gender, age, education, each of the 16 regions); total = **10**.
- **Test-only ID fallback**: a debug-mode start has no `SPM_PANELIST_ID` in its URL, so an empty `DA03_RV1` becomes `TEST-<case number>` (debug line `FALLBACK ID USED`). Production only emits `WARNING: DA03_RV1 empty`, so a missing ID stays visible. To test the real path with an ID, see *Testing the panelist ID* below.

Everything else matches production: dynamic Bilendi links (`&m=` + `DA03_RV1`), and the Complete link's ID passed to the Last Page through the `%panelistID%` placeholder set by `penultimate_complete_link.php`. `final_page.html` is identical to production.

Final links for Bilendi project 175243 (same as production; the unused Quality/Speeder/Duplicate/GeoIP links are listed in `../README.md`).

| Status | URL (`%SPM_PANELIST_ID%` replaced by `DA03_RV1`) |
|--------|-----|
| Complete | `https://survey.maximiles.com/complete?p=175243_9d4a1cee&m=%SPM_PANELIST_ID%` |
| QuotaFull | `https://survey.maximiles.com/quotasfull?p=175243_c3d988f3&m=%SPM_PANELIST_ID%` |
| Screenout | `https://survey.maximiles.com/screenout?p=175243_83181740&m=%SPM_PANELIST_ID%` |

## Testing the panelist ID (verified 2026-11-12)

URL parameters can only be tested through the **public link**. The admin preview (`index.php?o=Questionnaire&a=preview…`) does not pass them on, and editing the link of a case that has already started (`index.php?i=…&rnd=…`) gives *"Ungültiger Link"*.

1. TEST project only: *Project Settings → Publish Survey → Survey administration period*, from today to a few days later, then save. Without a period the public link shows *"Fragebogen noch nicht verfügbar"*.
2. Open `https://sosci.univie.ac.at/zeit-project005405/?SPM_PANELIST_ID=TEST001` (the TEST project's folder) in a private window. Use a new ID and a new private window for every run, otherwise SoSci continues the previous case.
3. Check:
   - Screenout run (age 16) lands on `…/screenout?p=175243_83181740&m=TEST001`.
   - In a complete run, "Weiter" on the last page links to `…/complete?p=175243_9d4a1cee&m=TEST002`.
   - *Collected data* shows the IDs in `DA03_RV1`.
   These runs show no debug output, so the redirect addresses and `DA03_RV1` are the check.
4. Afterwards, delete the test data (completes from the public link fill the TEST cells), and clear both period dates to return to pretest mode.

## Which file goes on which page

**Prerequisite:** the project's question catalog must contain `QC01` (section `QC`, imported from `../Quota_control_internal-variable.xml`). Otherwise every quota check fails and everyone is redirected to QuotaFull on the total quota page. Also, `DA03` must be on page 01.

| Page | Paste from |
|------|-----------|
| total quota page | `test/total_quota_page.php` |
| dimension quota page | `test/dimension_quota_page.php` |
| penultimate write page | `../penultimate_write_page.php` (unchanged) |
| penultimate write page, 2nd PHP element | `test/penultimate_complete_link.php` (below the QC writes) |
| final page (Last Page text) | `test/final_page.html` |
| reporting page | `test/reporting_page.php` |

## Test sequence (total = 10)

| # | Answers (gender, age, education SD08, region SD06) | Expected |
|---|---------|----------|
| 1 | age 16, or SD06 = 17 | Screenout |
| 2 | Mann, 25, 1, 16 Bayern | complete (TOTAL 1/10) |
| 3 | Mann, 35, 3, 5 Berlin | QuotaFull: gender |
| 4 | Frau, 25, 3, 5 Berlin | QuotaFull: age |
| 5 | Frau, 35, 1, 5 Berlin | QuotaFull: education |
| 6 | Frau, 35, 3, 16 Bayern | QuotaFull: region |
| 7 | Frau, 35, 3, 5 Berlin | complete (TOTAL 2/10) |

TOTAL = 10 cannot be reached with every cell = 1: age has 5 bands and no bypass, so at most 5 cases can complete (one per age band). The TOTAL redirect is therefore not tested here; lower `$QUOTA_TOTAL` in `total_quota_page.php` to 2 for one run to test it. Each test row changes exactly one cell from an earlier complete, so the debug `HITS:` line should show exactly one `YES`. Check the reporting page after each complete. To start a fresh round, delete the collected test data (Collected data → Delete data).
