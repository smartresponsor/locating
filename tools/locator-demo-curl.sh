#!/usr/bin/env bash
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
#
# Simple demo script for the Locator HTTP API.
# It assumes that the HTTP server is running and exposes the /locator routes.
#
# Environment:
#   LOCATOR_BASE_URL   Base URL (default: http://localhost:8000)
#   LOCATOR_TENANT     Tenant id header (default: demo)

set -euo pipefail

BASE_URL="${LOCATOR_BASE_URL:-http://localhost:8000}"
TENANT="${LOCATOR_TENANT:-demo}"

echo "Using base URL: ${BASE_URL}"
echo "Using tenant  : ${TENANT}"
echo

status() {
  echo "---- GET /locator/status ----"
  curl -sS "${BASE_URL}/locator/status" \
    -H "Accept: application/json" \
    -H "X-SR-Tenant: ${TENANT}" | jq .
  echo
}

suggest_us() {
  echo "---- GET /locator/address/suggest (US, checkout style query) ----"
  curl -sS "${BASE_URL}/locator/address/suggest" \
    -G \
    --data-urlencode "query=1600 Pennsylvania Ave NW Washington" \
    --data-urlencode "country=US" \
    --data-urlencode "limit=5" \
    -H "Accept: application/json" \
    -H "X-SR-Tenant: ${TENANT}" | jq .
  echo
}

suggest_gb() {
  echo "---- GET /locator/address/suggest (GB, Downing Street) ----"
  curl -sS "${BASE_URL}/locator/address/suggest" \
    -G \
    --data-urlencode "query=10 Downing Street London" \
    --data-urlencode "country=GB" \
    --data-urlencode "limit=5" \
    -H "Accept: application/json" \
    -H "X-SR-Tenant: ${TENANT}" | jq .
  echo
}

reverse_houston() {
  echo "---- GET /locator/address/reverse (Houston, TX) ----"
  curl -sS "${BASE_URL}/locator/address/reverse" \
    -G \
    --data-urlencode "lat=29.7604" \
    --data-urlencode "lon=-95.3698" \
    --data-urlencode "country=US" \
    -H "Accept: application/json" \
    -H "X-SR-Tenant: ${TENANT}" | jq .
  echo
}

reverse_kyiv() {
  echo "---- GET /locator/address/reverse (Kyiv) ----"
  curl -sS "${BASE_URL}/locator/address/reverse" \
    -G \
    --data-urlencode "lat=50.4501" \
    --data-urlencode "lon=30.5234" \
    --data-urlencode "country=UA" \
    -H "Accept: application/json" \
    -H "X-SR-Tenant: ${TENANT}" | jq .
  echo
}

run_all() {
  status
  suggest_us
  suggest_gb
  reverse_houston
  reverse_kyiv
}

case "${1:-all}" in
  status) status ;;
  suggest-us) suggest_us ;;
  suggest-gb) suggest_gb ;;
  reverse-houston) reverse_houston ;;
  reverse-kyiv) reverse_kyiv ;;
  all) run_all ;;
  *)
    echo "Usage: $0 [status|suggest-us|suggest-gb|reverse-houston|reverse-kyiv|all]" >&2
    exit 1
    ;;
esac
