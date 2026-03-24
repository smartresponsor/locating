Locator (Smartresponsor) — winner repository

This folder is a consolidated "winner" snapshot assembled from multiple archived Locator artifacts.

What was done
- Unpacked all provided archives and normalized project root (no wrapper folder in the final ZIP).
- Selected the baseline from locator-src-current.zip (kept as the primary winner).
- Imported the R30–R40 planning/report documents into report/legacy/locator-r30-r40/.
- Imported the sketches meta index files into report/legacy/location-sketches15-30/.
- Repaired composer.json to be valid JSON (the same invalid composer.json was present in all inputs).

How to use (quick)
1) composer validate
2) composer install
3) composer test

Merge evidence
See report/merge/ for collision index (paths where archives differed) and build statistics.

Canon notes
- The final ZIP is flat-root (project files are at ZIP root, no outer wrapper folder).
- .git and IDE folders were removed from the winner snapshot.



Engineering hardening plan
- See `docs/locator-engineering-plan-2026-02.md` for a prioritized production-hardening backlog and commit units.

Current canonization note (r01)
- This current slice is under active migration toward the Locating/Location Symfony-oriented canon.
- The executable protocol gate added in `.gate/check/location-protocol-canon.php` is the starting control rail for the next cumulative waves.

Current canonization note (r02)
- Composer identity is now aligned toward `locating/location` and `App\` as the target production root.
- `Smartresponsor\` remains only as a temporary compatibility bridge while cumulative namespace migration is still in progress.
- Forbidden `src/Console` was evacuated in favor of canonical commands under `src/Command/Location/`.
