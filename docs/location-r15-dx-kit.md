# Locator R15 – DX Kit and OpenAPI v1

Goal
----

Provide a developer-friendly entry point for the Locator service:
- OpenAPI v1 specification,
- simple HTTP examples,
- a small CLI-style demo script.

OpenAPI
-------

The OpenAPI v1.0.0-rc0 document is located at:

- `docs/locator-openapi-v1.yaml`

It describes the currently exposed HTTP endpoints:

- `GET /locator/address/suggest` – ranked address suggestions.
- `GET /locator/status` – service status and basic metric snapshot.
- `GET /locator/metrics` – Prometheus-style metrics text.

Base URL and tenant
-------------------

By default, examples use:

- Base URL: `http://localhost:8000`
- Tenant identifier: `tenant-demo` (if needed at gateway level)

Adjust these according to your deployment and gateway configuration.

Quick HTTP examples
-------------------

Address suggest:

```bash
curl -G "http://localhost:8000/locator/address/suggest" \
  --data-urlencode "query=123 Main St" \
  --data-urlencode "country=US" \
  --data-urlencode "limit=5"
```

Status:

```bash
curl "http://localhost:8000/locator/status"
```

Metrics (Prometheus):

```bash
curl "http://localhost:8000/locator/metrics"
```

For a richer set of examples, see `docs/location-r15-dx-samples.http`.

Postman / HTTP client
---------------------

You can import `docs/locator-openapi-v1.yaml` into most HTTP client tools:

- Postman
- Insomnia
- IntelliJ HTTP client
- VS Code REST Client extension

Each tool can generate requests and small client snippets in various languages
based on the OpenAPI document.

CLI demo script
---------------

A small CLI demo script is available at:

- `tools/locator-demo.php`

Usage:

```bash
# Ensure PHP CLI is available and the script is executable
php tools/locator-demo.php
```

Environment variables:

- `LOCATOR_BASE_URL` – base URL for requests (default `http://localhost:8000`).

The script performs a simple run through:

- `/locator/status`
- `/locator/address/suggest?query=123 Main St&country=US`
- `/locator/metrics`

and prints short summaries of each call.
