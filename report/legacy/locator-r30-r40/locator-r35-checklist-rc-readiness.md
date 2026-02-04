Locator R35 – RC readiness checklist

Use this checklist before creating RC branch.

1. Business logic coverage
   - [ ] All critical FeatureKey entries from R30 implemented.
   - [ ] For unfinished features there is an explicit backlog item with priority and scope.

2. Tests
   - [ ] Each critical business case has at least one integration or e2e test.
   - [ ] Dirty input and boundary tests exist for the most sensitive flows.

3. Demo and fixtures
   - [ ] At least one end-to-end demo flow can be run without manual preparation.
   - [ ] Fixtures cover main regions and at least one complex address pattern.

4. Observability
   - [ ] Metrics and logs are wired and visible on dashboard.
   - [ ] SLOs are written and at least one alert is configured.

5. Benchmark awareness
   - [ ] Documented where Locator is weaker than external providers.
   - [ ] These gaps are consciously accepted for RC1 or scheduled as follow-ups.

6. Decision
   - [ ] Product/engineering sign-off that RC scope is frozen.
   - [ ] RC branch and tag naming convention selected.
