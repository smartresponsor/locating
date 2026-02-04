Locator R40 – RC branch and release plan

Goal: define how you freeze scope and move Locator into RC without accidentally expanding features.

Sections:

1. Branching strategy
   - RC branch name (example: locator-rc-1-stab).
   - Rules for what can be merged into RC (only fixes/tests/observability, no new features).
   - Relation to master/main branch.

2. Tagging strategy
   - Tag format for RC builds (example: locator-rc-1-p1, locator-rc-1-p2).
   - When tags are applied (after smoke tests, after SLO check, etc.).

3. Checklists integration
   - How locator-r35-checklist-rc-readiness.md will be used at each RC iteration.
   - Who signs off before promoting RC to GA.

4. Rollback and hotfix policy
   - What happens if a serious bug is found in RC.
   - How you cut hotfixes versus normal future work.

5. Final acceptance
   - Conditions for promoting RC to GA.
