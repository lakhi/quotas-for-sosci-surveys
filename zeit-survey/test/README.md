# ZEIT Survey – TEST project version

For the separate **TEST - ZEIT-Project** in SoSci only. Never paste these files into the live project.

## What differs from `zeit-survey/`

- Every quota cell = **1** (gender, age, education, each of the 16 regions); total = **10**.
- **Dynamic Bilendi links**: `&m=` carries the panelist ID from `DA03_RV1` (Bilendi ID: question `DA03`, a device and request variable that reads the URL parameter `SPM_PANELIST_ID`).
- The **Complete link** gets the ID through a placeholder: SoSci's Last Page cannot run PHP, so `penultimate_complete_link.php` (a second PHP element on the penultimate page) calls `replace('%panelistID%', …)`, and `final_page.html` uses `%panelistID%` in its `href`.
- **Test-only ID fallback**: a debug-mode start has no `SPM_PANELIST_ID` in its URL, so an empty `DA03_RV1` becomes `TEST-<case number>` (debug line `FALLBACK ID USED`). Do **not** copy this into production, where a missing ID must stay visible. To test the real path, add `&SPM_PANELIST_ID=TEST001` to the debug link (`…/?act=<token>&SPM_PANELIST_ID=TEST001`) before answering page 01.

| Status | URL (`%SPM_PANELIST_ID%` replaced by `DA03_RV1`) |
|--------|-----|
| Complete | `https://survey.maximiles.com/complete?p=98327_8107c9fe&m=%SPM_PANELIST_ID%` |
| QuotaFull | `https://survey.maximiles.com/quotasfull?p=98327_21a4610c&m=%SPM_PANELIST_ID%` |
| Screenout | `https://survey.maximiles.com/screenout?p=98327_69cadaeb&m=%SPM_PANELIST_ID%` |

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
