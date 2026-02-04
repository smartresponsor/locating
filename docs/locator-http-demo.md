# Locator HTTP demo (R20)

This R20 envelope adds a small HTTP demo kit for the Locator component.

Files:

- `tools/locator-http-demo.http` – REST client collection for IntelliJ / VS Code.
- `tools/locator-demo-curl.sh` – curl-based shell demo.

## REST client collection

Open `tools/locator-http-demo.http` in your IDE and set:

```http
@baseUrl = http://localhost:8000
@tenant = demo
```

Then run individual requests:

- `/locator/status`
- `/locator/address/suggest`
- `/locator/address/reverse`

All requests send `X-SR-Tenant: {{tenant}}` header so you can exercise the
tenant quota and context features added in earlier R versions.

## Curl demo script

Requirements:

- `curl`
- `jq` for pretty-printing JSON

Usage:

```bash
chmod +x tools/locator-demo-curl.sh
./tools/locator-demo-curl.sh
```

Environment:

- `LOCATOR_BASE_URL` – base URL (default: `http://localhost:8000`)
- `LOCATOR_TENANT` – tenant id (default: `demo`)

The script:

1. Calls `/locator/status`
2. Calls `/locator/address/suggest` for a US query
3. Calls `/locator/address/suggest` for a GB query
4. Calls `/locator/address/reverse` for a Houston coordinate

This gives a quick end-to-end smoke from a developer workstation without
needing to learn any of the internal endpoints or payload details.
