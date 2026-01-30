#!/usr/bin/env bash
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
set -euo pipefail
ROOT="${1:-.}"
node "${ROOT%/}/.gate/linting/js/layer-mirror-check.js" --path "$ROOT"
