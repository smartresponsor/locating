# Locator quickstart with Docker

This document describes a minimal way to run the Locator component in a
Docker-based environment for local development and testing.

> Adjust the image name and paths to match your actual repository layout.

## 1. Example docker-compose snippet

```yaml
version: '3.9'

services:
  locator:
    image: php:8.2-cli
    working_dir: /app
    volumes:
      - ./:/app
    command: php -S 0.0.0.0:8000 -t public
    ports:
      - '8000:8000'
    environment:
      APP_ENV: dev
      LOCATOR_DEFAULT_TENANT: demo
```

With this setup the Locator HTTP API is available on
`http://localhost:8000`.

## 2. Basic smoke

Once the container is running, you can call:

- `GET /locator/status`
- `GET /locator/address/suggest`
- `GET /locator/address/reverse`

For example:

```bash
curl -sS 'http://localhost:8000/locator/status' \
  -H 'Accept: application/json'
```

Or use the existing tools:

- `tools/locator-http-demo.http`
- `tools/locator-demo-curl.sh`
- `tools/locator-fixtures-run.php`

## 3. Next steps

For a more production-like setup you can:

- use a dedicated web server image (e.g. nginx + PHP-FPM);
- add health checks and metrics scraping for `/metrics`;
- wire the service into your staging/prod Kubernetes cluster using the same
  HTTP contract `openapi/locator-v1.yaml`.

This quickstart is intentionally minimal and focused on helping developers
to get a working Locator instance on their machines in a few minutes.

## 4. Canonical deploy file location

The canonical nginx configuration for Locating now lives at:

```text
deploy/nginx/nginx.conf
```

The older `config/nginx.conf` location is treated as a retired root-structure artifact.
Do not add new Docker, nginx, compose, or deployment files under `config/`; keep runtime
application configuration in `config/` and deploy/runtime envelope files under `deploy/`.
