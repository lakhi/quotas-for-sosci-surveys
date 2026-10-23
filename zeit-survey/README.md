# ZEIT Survey – SoSci Quota Logic

PHP quota control code for the ZEIT project survey (Germany only) hosted on SoSci Survey. Panel: Bilendi.

## Structure

| File | Description |
|------|-------------|
| `total_quota_page.php` | Overall quota check (1100 completes) and QuotaFull redirect |
| `dimension_quota_page.php` | Screens out under-18s and respondents living outside Germany, then checks gender/age/education/region quotas and redirects to QuotaFull |
| `penultimate_write_page.php` | Writes completed interview data to the internal QC variables |
| `penultimate_complete_link.php` | Second PHP element on the penultimate page: sets the `%panelistID%` placeholder for the Complete link |
| `final_page.html` | Last Page text with the dynamic Bilendi Complete link |
| `sd17_screenout.php` | Screenout redirect for the screening question SD17 (answer code still a placeholder) |
| `reporting_page.php` | Reporting/debug output: filled/target/left per quota cell, plus completes not counted in each dimension |
| `quota_zeit.csv` | Quota targets (Bilendi, best4planning, population-representative M/F 18-75), programmed at 110% |
| `Quota_control_internal-variable.xml` | SoSci import file for the internal variable `QC01` |
| `test/` | TEST-project version: every quota cell = 1, total = 10, test-only fallback panelist ID (see `test/README.md`) |

## Which file goes on which page

| Page | Paste from |
|------|-----------|
| page 01 | question `DA03` (device and request variables, reads `SPM_PANELIST_ID`) |
| page after SD17 | `sd17_screenout.php` |
| total quota page | `total_quota_page.php` |
| dimension quota page | `dimension_quota_page.php` |
| penultimate write page | `penultimate_write_page.php` |
| penultimate write page, 2nd PHP element | `penultimate_complete_link.php` (below the QC writes) |
| final page (Last Page text) | `final_page.html` |
| reporting page | `reporting_page.php` |

## Quotas (n=1100, 110% of 1000)

Each cell is Bilendi's target ×1.1, so the quality buffer is collected directly.

| Dimension | Variable | Groups | Bypass |
|-----------|----------|--------|--------|
| Gender | `SD05` | Mann 550, Frau 550 | 3-6 (non-binary, agender, other, no answer) |
| Age | `SD03_01` | 18-29: 198, 30-39: 187, 40-49: 176, 50-59: 231, 60+: 308 | under 18 → Screenout |
| Education | `SD08` | gering (1, 2, 10): 319, mittel (3, 4): 341, hoch (5, 6, 7, 9): 440 | 8 (Keine Angabe) |
| Region | `SD06` | 16 Bundeslaender, see `quota_zeit.csv` | 17 (lives outside Germany) → Screenout |

Gender, age, education and region are checked independently; a respondent is redirected to QuotaFull if *any* of their cells is full.

## QC Variables

| Item | Content | Values |
|------|---------|--------|
| `QC01_01` | completed | 1 |
| `QC01_02` | gender group | 1-2 |
| `QC01_03` | age group | 1-5 |
| `QC01_04` | education group | 1-3 |
| `QC01_05` | region group | 1-16 (= `SD06` code) |

## Bilendi Redirect Links (dynamic)

`%SPM_PANELIST_ID%` is replaced by `urlencode(value('DA03_RV1'))`.

| Status | URL |
|--------|-----|
| Complete | `https://survey.maximiles.com/complete?p=98327_8107c9fe&m=%SPM_PANELIST_ID%` |
| QuotaFull | `https://survey.maximiles.com/quotasfull?p=98327_21a4610c&m=%SPM_PANELIST_ID%` |
| Screenout | `https://survey.maximiles.com/screenout?p=98327_69cadaeb&m=%SPM_PANELIST_ID%` |

## Before Going Live

- [ ] `QC01` exists: question-catalog section `QC` with `Quota_control_internal-variable.xml` imported.
- [ ] `DA03` is on page 01 (GET parameters are only read on the first page).
- [ ] SD17's screenout answer code is filled into `sd17_screenout.php`, and any old SoSci filter with a static Screenout URL is removed.
- [ ] The links match the final ones Bilendi sends; ask Bilendi for the 4th link (likely "Quality") and where it is needed.
- [ ] One debug run with `&SPM_PANELIST_ID=TEST001` appended to the start link shows `m=TEST001` in every redirect.

## Notes
- `SD03` (age) must be a required question with a SoSci response check of numbers only, range 1-110: an unanswered or non-numeric age reads as below 18 and is screened out, and a typed birth year (e.g. 1985) would count as 60+. Keep the minimum at 1, not 18, so under-18s are screened out instead of typing 18 to get past the check.
- `SD06` (region) should be required: an unanswered region bypasses the region quota.
- `SD06` codes are not the official Bundesland numbers (e.g. 03 = Mecklenburg-Vorpommern), and `SD08` codes 9 and 10 are out of order. Always map by answer code.
- Ages 76+ count towards the top age band (Bilendi's quota band is 60-75); the researcher confirmed leaving it open.
- Bilendi's education split adds up to 99% (29/31/39); hoch was raised to 40% so the cells total 100%.
- A respondent without `SPM_PANELIST_ID` (e.g. a debug-mode start) is let through; the pages emit a `WARNING: DA03_RV1 empty` debug line and the Bilendi links end in an empty `&m=`. Real Bilendi invitations always carry the ID.
- SoSci's Last Page cannot run PHP, so the Complete link's ID comes from the `%panelistID%` placeholder set on the penultimate page.
- Bilendi steers invitations from a daily quota update (target and actual per cell); copy it from the reporting page.
