# ZEIT Survey – SoSci Quota Logic

PHP quota control code for the ZEIT project survey (Germany only) hosted on SoSci Survey. Panel: Bilendi.

## Structure

| File | Description |
|------|-------------|
| `total_quota_page.php` | Overall quota check (1000 completes) and QuotaFull redirect |
| `dimension_quota_page.php` | Screens out under-18s and respondents living outside Germany, then checks gender/age/education/region quotas and redirects to QuotaFull |
| `penultimate_write_page.php` | Writes completed interview data to the internal QC variables |
| `reporting_page.php` | Reporting/debug output: filled/target/left per quota cell, plus completes not counted in each dimension |
| `final_page.html` | End-of-survey page with the Bilendi Complete link |
| `quota_zeit.csv` | Quota targets (Bilendi, best4planning, population-representative M/F 18-75) |
| `Quota_control_internal-variable.xml` | SoSci import file for the internal variable `QC01` |
| `test/` | TEST-project version: every quota cell = 1, total = 10, dynamic Bilendi links via `DA03_RV1` (see `test/README.md`) |

## Quotas (n=1000)

| Dimension | Variable | Groups | Bypass |
|-----------|----------|--------|--------|
| Gender | `SD05` | Mann 500, Frau 500 | 3-6 (non-binary, agender, other, no answer) |
| Age | `SD03_01` | 18-29: 180, 30-39: 170, 40-49: 160, 50-59: 210, 60+: 280 | under 18 → Screenout |
| Education | `SD08` | gering (1, 2, 10): 290, mittel (3, 4): 310, hoch (5, 6, 7, 9): 400 | 8 (Keine Angabe) |
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

## Bilendi Redirect Links (static)

| Status | URL |
|--------|-----|
| Complete | `https://survey.maximiles.com/static-complete?p=98327_8107c9fe` |
| QuotaFull | `https://survey.maximiles.com/static-quotasfull?p=98327_21a4610c` |
| Screenout | `https://survey.maximiles.com/static-screenout?p=98327_69cadaeb` |

## Notes
- Import `Quota_control_internal-variable.xml` so that it becomes `QC01`; the pages expect the item IDs `QC01_01`-`QC01_05`.
- `SD03` (age) must be a required question with a SoSci response check of numbers only, range 1-110: an unanswered or non-numeric age reads as below 18 and is screened out, and a typed birth year (e.g. 1985) would count as 60+. Keep the minimum at 1, not 18, so under-18s are screened out instead of typing 18 to get past the check.
- `SD06` (region) should be required: an unanswered region bypasses the region quota.
- `SD06` codes are not the official Bundesland numbers (e.g. 03 = Mecklenburg-Vorpommern), and `SD08` codes 9 and 10 are out of order. Always map by answer code.
- Ages 76+ count towards the top age band (Bilendi's quota band is 60-75).
- Bilendi's education split adds up to 99% (29/31/39); hoch was raised to 40% so the cells total 1000.
- Quotas are programmed at 100%. Bilendi suggested 110% as a quality buffer; this was not applied.
- The screening page (`SD17 Screenout`) also needs the Screenout link.
- Bilendi steers invitations from a daily quota update (target and actual per cell); copy it from the reporting page.
