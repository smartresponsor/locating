# Env
Variables and presets.

## Reproducible Composer dependencies

CI now treats `composer.lock` as the source of truth for dependency resolution. Cache keys are based on the lockfile hash so cache entries rotate when dependency graph changes, preventing stale and "eternal" cache reuse across dependency updates.

Always commit `composer.lock` together with any dependency change in `composer.json` to keep local and CI installs deterministic.
