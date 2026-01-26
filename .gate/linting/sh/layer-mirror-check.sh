#!/usr/bin/env bash
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
set -euo pipefail
ROOT="${1:-.}"
<<<<<<< HEAD
node "${ROOT%/}/.gate/linting/js/layer-mirror-check.js" --path "$ROOT"
=======
node "${ROOT%/}/owner/lint/layer-mirror-check.js" --path "$ROOT"
>>>>>>> 94ad96f (first/init commit)
