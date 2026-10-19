# WHO AMR Survey – SoSci Quota Logic

PHP quota control code for the WHO Textual Messages AMR survey hosted on SoSci Survey.

## Structure

| Folder | Description |
|--------|-------------|
| `country_quota_page/` | Per-country overall quota check and redirect (one file per country) |
| `dimension_quota_page/` | Per-country gender/age/education quota check and redirect (one file per country) |
| `penultimate_write_page/` | Writes completed interview data to internal QC variables (one file per country group) |
| `reporting_page/` | Per-country reporting/debug output page for quota counts and status |
| `final_page/` | End-of-survey final pages with country-specific completion redirect links |

## Countries
| Code | Country | Quota |
|------|---------|-------|
| 1 | Germany (DE) | 1000 |
| 2 | Lithuania (LT) | 1000 |
| 3 | Serbia (SR) | 1000 |
| 4 | Uzbekistan (UZ) | 1000 |

## Country Dimension Quotas
Gender (man/woman only), age group (6 bands), and education group (2 bands) are checked independently. Non-binary/other gender responses bypass gender quota but are still subject to age and education quotas. Participants who select "prefer not to answer" for education group also bypass the education quota check.

## Notes
- Variables must be declared on each page directly — PHP Functions tab does not pass variables to page scope in SoSci.
- Age group bands for Uzbekistan differ from those used for Germany, Lithuania, and Serbia.
- Use low limits during testing; restore production values before go-live.