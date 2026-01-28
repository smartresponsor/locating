#!/usr/bin/env bash
set -euo pipefail

# ------------------------------------------------------------
# gate fix proposal (NDJSON) -> .report/gate-fix-proposal.ndjson
# ------------------------------------------------------------

GATE_PROPOSAL_FILE="${GATE_PROPOSAL_FILE:-.report/gate-fix-proposal.ndjson}"

gate_proposal_init() {
  mkdir -p ".report"
  : > "$GATE_PROPOSAL_FILE"
}

gate_proposal_add() {
  mkdir -p ".report"
  printf "%s\n" "$1" >> "$GATE_PROPOSAL_FILE"
}

gate_json_file_append_lines() {
  local path="$1"; shift
  python3 - "$path" "$@" <<'PY'
import json, sys
path = sys.argv[1]
lines = sys.argv[2:]
obj = {
  "op": "file.append_lines",
  "level": "error",
  "path": path,
  "lines": lines,
  "note": "gate suggestion"
}
print(json.dumps(obj, ensure_ascii=False))
PY
}

gate_json_file_ensure_exists() {
  local path="$1"
  python3 - "$path" <<'PY'
import json, sys
path = sys.argv[1]
obj = {
  "op": "file.ensure_exists",
  "level": "error",
  "path": path,
  "note": "gate suggestion"
}
print(json.dumps(obj, ensure_ascii=False))
PY
}

gate_json_file_write_text_missing_only() {
  local path="$1"
  local text="$2"
  python3 - "$path" "$text" <<'PY'
import json, sys
path = sys.argv[1]
text = sys.argv[2]
obj = {
  "op": "file.write_text",
  "level": "error",
  "path": path,
  "guard": "missing_only",
  "text": text,
  "note": "gate suggestion"
}
print(json.dumps(obj, ensure_ascii=False))
PY
}

gate_json_path_ensure_dir() {
  local path="$1"
  python3 - "$path" <<'PY'
import json, sys
path = sys.argv[1]
obj = {
  "op": "path.ensure_dir",
  "level": "info",
  "path": path,
  "note": "gate suggestion"
}
print(json.dumps(obj, ensure_ascii=False))
PY
}

gate_json_chmod_add_x() {
  local path="$1"
  python3 - "$path" <<'PY'
import json, sys
path = sys.argv[1]
obj = {
  "op": "chmod.add_x",
  "level": "error",
  "path": path,
  "note": "gate suggestion"
}
print(json.dumps(obj, ensure_ascii=False))
PY
}

gate_json_report_print() {
  local text="$1"
  python3 - "$text" <<'PY'
import json, sys
text = sys.argv[1]
obj = {
  "op": "report.print",
  "level": "warn",
  "text": text,
  "note": "gate hint"
}
print(json.dumps(obj, ensure_ascii=False))
PY
}

gate_json_agent_required() {
  local prompt="$1"
  python3 - "$prompt" <<'PY'
import json, sys
prompt = sys.argv[1]
obj = {
  "op": "agent.required",
  "level": "error",
  "scope": ["repo"],
  "prompt": prompt,
  "note": "needs reasoning"
}
print(json.dumps(obj, ensure_ascii=False))
PY
}

gate_proposal_print_summary() {
  if [[ -f "$GATE_PROPOSAL_FILE" ]]; then
    echo "[gate] proposal file: $GATE_PROPOSAL_FILE"
    echo "[gate] proposal entries:"
    cat "$GATE_PROPOSAL_FILE" || true
  else
    echo "[gate] proposal file: (none)"
  fi
}

gate_run() {
  local title="$1"; shift
  set +e
  "$@"
  local code=$?
  set -e
  if [[ $code -ne 0 ]]; then
    echo "[gate] FAIL step=$title code=$code"
    gate_proposal_add "$(gate_json_agent_required "Step failed: $title. Review logs and apply proposals; if not enough, perform targeted fixes.")"
    gate_proposal_print_summary
    exit $code
  fi
}

# start proposal file
gate_proposal_init

# ------------------------------------------------------------
# baseline proposals (SAFE)
# ------------------------------------------------------------

gate_proposal_add "$(gate_json_path_ensure_dir ".report")"

# chmod +x for gate entrypoints
gate_proposal_add "$(gate_json_chmod_add_x ".gate/gate.sh")"

# gitattributes LF normalization
gate_proposal_add "$(gate_json_file_ensure_exists ".gitattributes")"
gate_proposal_add "$(gate_json_file_append_lines ".gitattributes" "* text=auto eol=lf")"
gate_proposal_add "$(gate_json_report_print "Suggested: enforce LF via .gitattributes (* text=auto eol=lf)")"

# chmod +x for all .gate/**/*.sh
while IFS= read -r f; do
  [[ -n "$f" ]] || continue
  gate_proposal_add "$(gate_json_chmod_add_x "$f")"
done < <(find ".gate" -type f -name "*.sh" 2>/dev/null || true)

# gitignore template baseline
gate_proposal_add "$(gate_json_file_ensure_exists ".gitignore")"
gate_proposal_add "$(gate_json_file_append_lines ".gitignore" ".DS_Store" "Thumbs.db" "node_modules/" "/.env.local" "/.env.*.local")"
gate_proposal_add "$(gate_json_report_print "Suggested: append missing .gitignore template lines (OS junk + node + env locals)")"

# README + MANIFEST (missing_only)
gate_proposal_add "$(gate_json_file_ensure_exists "README.md")"
gate_proposal_add "$(gate_json_file_write_text_missing_only "README.md" "# Repo\n\nGate proposal: README created.\n")"
gate_proposal_add "$(gate_json_file_ensure_exists "MANIFEST.json")"
gate_proposal_add "$(gate_json_file_write_text_missing_only "MANIFEST.json" "{\n  \"name\": \"\",\n  \"version\": \"0.0.0\",\n  \"note\": \"Gate proposal: manifest created\"\n}\n")"
gate_proposal_add "$(gate_json_report_print "Suggested: ensure README.md + MANIFEST.json exist (created only if missing)")"

# ------------------------------------------------------------

REPO_ROOT="${1:-$(pwd)}"
QUALITY="${QUALITY:-0}"

MODE="consumer"
if [[ "${GITHUB_REPOSITORY:-}" == */canonization ]]; then
  MODE="canon"
fi

echo "[gate] repo=${GITHUB_REPOSITORY:-local} mode=$MODE root=$REPO_ROOT"

# Contract
if [[ "$MODE" == "canon" ]]; then
  gate_run "root-contract-check" bash "$REPO_ROOT/.gate/contract/sh/root-contract-check.sh" "$REPO_ROOT"
else
  echo "[gate] skip root-contract-check (consumer repo)"
fi

gate_run "gitignore-template-check" bash "$REPO_ROOT/.gate/contract/sh/gitignore-template-check.sh" "$REPO_ROOT"

# Linting (fast checks) - JS checks require node
if command -v node >/dev/null 2>&1; then
  gate_run "no-plural-check" node "$REPO_ROOT/.gate/linting/js/no-plural-check.js" "$REPO_ROOT"
  gate_run "layer-mirror-check-js" node "$REPO_ROOT/.gate/linting/js/layer-mirror-check.js" "$REPO_ROOT"
  gate_run "doc-name-check-js" node "$REPO_ROOT/.gate/linting/js/doc-name-check.js" "$REPO_ROOT"
  gate_run "archive-name-check-js" node "$REPO_ROOT/.gate/linting/js/archive-name-check.js" "$REPO_ROOT"
else
  echo "[gate] node not found, skipping JS linting checks"
fi

gate_run "copyright-header-check-sh" bash "$REPO_ROOT/.gate/linting/sh/copyright-header-check.sh" "$REPO_ROOT"
gate_run "layer-mirror-check-sh" bash "$REPO_ROOT/.gate/linting/sh/layer-mirror-check.sh" "$REPO_ROOT"
gate_run "doc-name-check-sh" bash "$REPO_ROOT/.gate/linting/sh/doc-name-check.sh" "$REPO_ROOT"
gate_run "archive-flat-root-check-sh" bash "$REPO_ROOT/.gate/linting/sh/archive-flat-root-check.sh" "$REPO_ROOT"

if [[ "$QUALITY" == "1" ]]; then
  gate_run "quality-run" bash "$REPO_ROOT/.gate/quality/sh/quality-run.sh" "$REPO_ROOT"
fi

echo "[gate] Gate OK"
gate_proposal_print_summary
