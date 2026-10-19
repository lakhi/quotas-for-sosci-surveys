# Quotas for SoSci Surveys

PHP quota control code for surveys hosted on SoSci Survey. Each survey lives in its own folder; the code is copy-pasted into the SoSci page editors.

## Surveys

| Folder | Survey | Countries | Panel | Quota |
|--------|--------|-----------|-------|-------|
| [`who-amr-survey/`](who-amr-survey/) | WHO Textual Messages AMR survey | DE, LT, SR, UZ | Norstat | 1000 per country |
| [`zeit-survey/`](zeit-survey/) | ZEIT project | DE only | Bilendi | 1000 |

## Notes
- Variables must be declared on each page directly — PHP Functions tab does not pass variables to page scope in SoSci.
- Use low limits during testing; restore production values before go-live.
