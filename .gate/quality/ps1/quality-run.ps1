<<<<<<< HEAD
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
=======
>>>>>>> 94ad96f (first/init commit)
param(
  [Parameter(Mandatory=$false)][string]$RepoRoot = (Get-Location).Path
)

$ErrorActionPreference = "Stop"

& (Join-Path $RepoRoot ".gate/quality/ps1/phpstan-run.ps1") -RepoRoot $RepoRoot
& (Join-Path $RepoRoot ".gate/quality/ps1/rector-run.ps1") -RepoRoot $RepoRoot
