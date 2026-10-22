# Quotas for SoSci Surveys

PHP quota control code for surveys hosted on SoSci Survey. Each survey lives in its own folder; the code is copy-pasted into the SoSci page editors.

## Surveys

| Folder | Survey | Countries | Panel | Quota |
|--------|--------|-----------|-------|-------|
| [`who-amr-survey/`](who-amr-survey/) | WHO Textual Messages AMR survey | DE, LT, SR, UZ | Norstat | 1000 per country |
| [`zeit-survey/`](zeit-survey/) | ZEIT project | DE only | Bilendi | 1000 |

## Notes
- **Create the QC internal variable first.** Before pasting quota code or copying a SoSci project (e.g. to a TEST project), create a question-catalog section with the ID `QC` and import the survey's `Quota_control_internal-variable.xml` into it, so it becomes `QC01`. Otherwise every quota check fails ("Konnte Frage bzw. Item mit der Kennung QC01_01 nicht finden!") and every respondent is redirected to QuotaFull.
- Variables must be declared on each page directly — PHP Functions tab does not pass variables to page scope in SoSci.
- Use low limits during testing; restore production values before go-live.
