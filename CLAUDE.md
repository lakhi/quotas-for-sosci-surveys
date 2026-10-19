# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

PHP quota-control logic for surveys hosted on **SoSci Survey**, one top-level folder per survey. There is no build system, test suite, or local runtime — all PHP runs inside SoSci's page-level PHP execution environment. The code is copy-pasted into SoSci page editors.

| Folder | Survey | Countries | Panel |
|--------|--------|-----------|-------|
| `who-amr-survey/` | WHO Textual Messages AMR survey | DE, LT, SR, UZ | Norstat |
| `zeit-survey/` | ZEIT project | DE only | Bilendi |

## Key SoSci API Functions

- `value('VAR')` — read a survey variable
- `put('VAR', val)` — write to an internal variable
- `statistic('count', 'VAR', val)` — count how many completed cases have a given value
- `redirect(url)` — end survey and redirect respondent
- `debug(msg)` — emit debug output (visible only in SoSci debug/pretest mode)

## Shared Constraints

- **No shared scope**: SoSci does not pass variables from the PHP Functions tab to page scope. Every variable must be declared directly on each page.
- **Quota values come from CSV**: each survey folder has the authoritative quota splits as CSV. When updating quotas, update both the CSV and all PHP files that reference the values.
- **Each SoSci project has its own internal variables**, so QC item IDs only need to be unique within one survey.

## WHO AMR Survey (`who-amr-survey/`)

### Architecture

The survey flow for each respondent hits these pages in order:

1. **country_quota_page** — checks overall country quota via `QC01_01`; redirects to panel QuotaFull URL if full.
2. **dimension_quota_page** — checks gender / age / education quotas independently; redirects if *any* dimension is full.
3. *(survey questions run here)*
4. **penultimate_write_page** — on completion, writes the respondent's group codes to QC internal variables so they are counted for future quota checks.
5. **final_page** — static HTML with a country-specific panel completion redirect link.
6. **reporting_page** — read-only debug output showing filled/remaining counts per dimension (not in the respondent flow).

Each page folder has one file per country (DE, LT, SR, UZ); quotas are in `quota_csv/`.

### QC Variable Mapping

Each country uses isolated internal-variable items so quota counts don't interfere:

| Country | Country completion | Gender | Age | Education |
|---------|-------------------|--------|-----|-----------|
| DE (1)  | QC01_01 = 1       | QC02_01 | QC02_02 | QC02_03 |
| LT (2)  | QC01_01 = 2       | QC03_01 | QC03_02 | QC03_03 |
| SR (3)  | QC01_01 = 3       | QC04_01 | QC04_02 | QC04_03 |
| UZ (4)  | QC01_01 = 4       | QC05_01 | QC05_02 | QC05_03 |

All countries share QC01 for the country-level count (distinguished by value), but dimensions use separate QC02–QC05 items per country.

### Survey Variables (respondent data)

- `SD02` — country code (1=DE, 2=LT, 3=SR, 4=UZ)
- `SD05` — gender (1=male, 2=female, 3+=other/non-binary)
- `SD03_01` — age (integer)
- `SD08` — education level (1-2=low, 3-7=high)

### Constraints

- **Age bands differ for Uzbekistan**: DE/LT/SR use 18-24, 25-34, 35-44, 45-54, 55-64, 65+. UZ uses 18-29, 30-39, 40-49, 50-59, 60-64, 65+.
- **Gender bypass**: Non-binary respondents (SD05 >= 3) bypass gender quota but are still subject to age and education quotas.
- **Education bypass**: Respondents with SD08 outside 1-7 (e.g. "prefer not to answer") bypass the education quota check.
- **Panel redirect URLs**: Each country has a unique Norstat QuotaFull URL. These appear in both country_quota and dimension_quota files.

### When Adding a New Country

1. Add a CSV to `who-amr-survey/quota_csv/` with the quota splits.
2. Create files in all five page folders following the existing country pattern.
3. Assign the next QC item range (e.g. QC06_01–03) for dimension variables.
4. Use the correct age-band mapping for that country.
5. Update `who-amr-survey/Quota_control_internal-variable.xml` if new QC items are needed.

## ZEIT Survey (`zeit-survey/`)

### Architecture

Germany only, so there is no country routing and one file per page:

1. **total_quota_page.php** — caps total completes (`QC01_01 = 1`) at 1000; redirects to QuotaFull if full.
2. **dimension_quota_page.php** — screens out under-18s and respondents living outside Germany (SD06 = 17), then checks gender / age / education / region quotas independently; redirects to QuotaFull if *any* dimension is full.
3. *(survey questions run here)*
4. **penultimate_write_page.php** — writes `QC01_01 = 1` for every complete, plus the four group codes.
5. **final_page.html** — static HTML with the Bilendi Complete link.
6. **reporting_page.php** — read-only debug output of filled/target/left per cell plus "not counted" per dimension (source of the daily quota update Bilendi asks for).

### QC Variable Mapping

| Item | Content | Values |
|------|---------|--------|
| QC01_01 | completed | 1 |
| QC01_02 | gender group | 1-2 |
| QC01_03 | age group | 1-5 |
| QC01_04 | education group | 1-3 |
| QC01_05 | region group | 1-16 (= SD06 code) |

### Survey Variables (respondent data)

- `SD05` — gender (1=Mann, 2=Frau, 3-6=non-binary/agender/other/no answer)
- `SD03_01` — age (integer)
- `SD06` — region (1-16 = Bundeslaender, 17 = lives outside Germany → Screenout)
- `SD08` — education (gering = 1, 2, 10; mittel = 3, 4; hoch = 5, 6, 7, 9; 8 = no answer)

### Constraints

- **Group 0 = not counted**: every dimension maps bypassed answers to group 0, which is neither checked nor written.
- **Map by answer code, not order**: SD06 codes are not the official Bundesland numbers (03 = Mecklenburg-Vorpommern), and SD08 codes 9 and 10 are out of sequence, so education uses explicit code lists, never ranges.
- **Age**: under 18 → Bilendi Screenout redirect (also catches an unanswered age); 76+ counts in the top band (60+), although Bilendi's band is 60-75.
- **Quotas at 100%**: Bilendi suggested programming 110% as a quality buffer; the team chose 100%. Bilendi's education split sums to 99%, so hoch was raised from 39% to 40%.
- **Region 17** (lives outside Germany) → Bilendi Screenout redirect, in the same `if` as under-18s.
- **Static Bilendi redirect links** (cookie-based, no panelist ID) were chosen over the dynamic `&m=` links. The QuotaFull URL lives in total_quota_page and dimension_quota_page, the Screenout URL only in dimension_quota_page, and the Complete URL in final_page.

## When Adding a New Survey

1. Create a new top-level folder named after the survey.
2. Add the quota CSV and a `Quota_control_internal-variable.xml` for that SoSci project.
3. Copy the page files from the closest existing survey and adapt variables, answer codes and redirect URLs.
4. Add the survey to the tables in `README.md` and this file.
