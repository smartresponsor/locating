param(
    [Parameter(Mandatory = $false)]
    [Alias('Path','Root','Target','Repo')]
    [string]$RepoRoot = (Get-Location).Path,

    [Parameter(Mandatory = $false)]
    [switch]$Quality
)

$ErrorActionPreference = "Stop"

function GateEnsureDir([string]$path) {
    if (!(Test-Path -LiteralPath $path)) {
        New-Item -ItemType Directory -Path $path | Out-Null
    }
}

function GateProposalInit([string]$filePath) {
    GateEnsureDir (Split-Path -Parent $filePath)
    Set-Content -LiteralPath $filePath -Value "" -NoNewline
}

function GateProposalAdd([string]$filePath, [hashtable]$obj) {
    $json = ($obj | ConvertTo-Json -Compress)
    Add-Content -LiteralPath $filePath -Value $json
}

function GateProposalPrint([string]$filePath) {
    if (Test-Path -LiteralPath $filePath) {
        Write-Host "[gate] proposal file: $filePath"
        Write-Host "[gate] proposal entries:"
        Get-Content -LiteralPath $filePath | ForEach-Object { Write-Host $_ }
    } else {
        Write-Host "[gate] proposal file: (none)"
    }
}

$proposalFile = Join-Path $RepoRoot ".report/gate-fix-proposal.ndjson"
GateProposalInit $proposalFile

# baseline SAFE proposals
GateProposalAdd $proposalFile @{ op="path.ensure_dir"; level="info"; path=".report"; note="gate suggestion" }
GateProposalAdd $proposalFile @{ op="chmod.add_x"; level="error"; path=".gate/gate.sh"; note="gate suggestion" }

GateProposalAdd $proposalFile @{ op="file.ensure_exists"; level="error"; path=".gitattributes"; note="gate suggestion" }
GateProposalAdd $proposalFile @{ op="file.append_lines"; level="error"; path=".gitattributes"; lines=@("* text=auto eol=lf"); note="gate suggestion" }

GateProposalAdd $proposalFile @{ op="file.ensure_exists"; level="error"; path=".gitignore"; note="gate suggestion" }
GateProposalAdd $proposalFile @{ op="file.append_lines"; level="error"; path=".gitignore"; lines=@(".DS_Store","Thumbs.db","node_modules/","/.env.local","/.env.*.local"); note="gate suggestion" }

GateProposalAdd $proposalFile @{ op="file.ensure_exists"; level="error"; path="README.md"; note="gate suggestion" }
GateProposalAdd $proposalFile @{ op="file.write_text"; level="error"; path="README.md"; guard="missing_only"; text="# Repo`n`nGate proposal: README created.`n"; note="gate suggestion" }

GateProposalAdd $proposalFile @{ op="file.ensure_exists"; level="error"; path="MANIFEST.json"; note="gate suggestion" }
GateProposalAdd $proposalFile @{ op="file.write_text"; level="error"; path="MANIFEST.json"; guard="missing_only"; text="{`n  `"name`": `"`",`n  `"version`": `"0.0.0`",`n  `"note`": `"Gate proposal: manifest created`"`n}`n"; note="gate suggestion" }

# mode
$mode = "consumer"
if ($env:GITHUB_REPOSITORY -match "/canonization$") {
    $mode = "canon"
}

Write-Host "[gate] repo=$env:GITHUB_REPOSITORY mode=$mode root=$RepoRoot"

function GateRun([string]$title, [scriptblock]$fn) {
    try {
        & $fn
    } catch {
        Write-Host "[gate] FAIL step=$title"
        GateProposalAdd $proposalFile @{
            op="agent.required"
            level="error"
            scope=@("repo")
            prompt="Step failed: $title. Review logs and apply proposals; if not enough, perform targeted fixes."
            note="needs reasoning"
        }
        GateProposalPrint $proposalFile
        throw
    }
}

# Contract
if ($mode -eq "canon") {
    GateRun "root-contract-check" { & (Join-Path $RepoRoot ".gate/contract/ps1/root-contract-check.ps1") -RepoRoot $RepoRoot }
} else {
    Write-Host "[gate] skip root-contract-check (consumer repo)"
}

GateRun "gitignore-template-check" { & (Join-Path $RepoRoot ".gate/contract/ps1/gitignore-template-check.ps1") -RepoRoot $RepoRoot }

# Linting
GateRun "copyright-header-check" { & (Join-Path $RepoRoot ".gate/linting/ps1/copyright-header-check.ps1") -RepoRoot $RepoRoot }
GateRun "layer-mirror-check" { & (Join-Path $RepoRoot ".gate/linting/ps1/layer-mirror-check.ps1") -RepoRoot $RepoRoot }
GateRun "doc-name-check" { & (Join-Path $RepoRoot ".gate/linting/ps1/doc-name-check.ps1") -RepoRoot $RepoRoot }
GateRun "archive-flat-root-check" { & (Join-Path $RepoRoot ".gate/linting/ps1/archive-flat-root-check.ps1") -RepoRoot $RepoRoot }

if ($Quality) {
    GateRun "quality-run" { & (Join-Path $RepoRoot ".gate/quality/ps1/quality-run.ps1") -RepoRoot $RepoRoot }
}

Write-Host "[gate] Gate OK"
GateProposalPrint $proposalFile
