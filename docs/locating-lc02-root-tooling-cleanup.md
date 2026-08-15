# Locating LC-02 Root Tooling Cleanup

## Scope

This wave normalizes the root-level development tooling surface without moving runtime source classes yet.

## Decisions

- Keep `.php-cs-fixer.dist.php` as the single canonical PHP-CS-Fixer config.
- Retire `.php-cs-fixer.php` and `php-cs-fixer.dist.php` as duplicate root configs.
- Keep the fixer rule set conservative for now: PSR-12, strict types, imports, short arrays, quotes, whitespace, trailing commas.
- Do not enable broad risky Symfony fixer presets during the class-form cleanup phase.
- Preserve descriptive docblocks. Formatting tools must not erase narrative documentation blocks.

## Why this comes before mass class movement

The repository currently has hundreds of PHP classes and a transitional namespace bridge. A duplicated fixer surface creates unstable local results while class names, folders, and namespaces are being canonicalized. LC-02 makes future waves reproducible before moving or renaming source files.

## Post-apply commands

```bash
composer validate
composer dump-autoload
composer cs:check
composer canon:root-tooling
composer canon:structure
```

## Expected result

`composer canon:root-tooling` should fail if either legacy fixer config is still present. The apply script backs up and removes only those two explicitly named files.
