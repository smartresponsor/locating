Locator demo loop – R27

This document describes a simple discovery path for trying out the Locator
component from scratch. It is aimed at engineers, product managers and
other stakeholders who want to see the system in action without reading
all the internals.

1. Prerequisites

- PHP 8.1+ with Composer installed.
- A clone of the Locator project.
- Basic terminal access (bash or PowerShell).

2. Install dependencies

From the project root:

```bash
composer install
```

If you already have a working environment for other components of the
SmartResponsor suite, you can reuse it. The important part is that the
HTTP server can serve the Locator controllers.

3. Start the HTTP server

For a quick local run you can use the built-in PHP server:

```bash
php -S 0.0.0.0:8000 -t public
```

Adjust the document root (`public`) and port according to your Symfony
configuration if needed.

Once the server is running, the Locator HTTP API should be available at:

- `http://localhost:8000/locator/status`
- `http://localhost:8000/locator/address/suggest`
- `http://localhost:8000/locator/address/reverse`

4. Run the demo script (curl)

The repository provides a small helper script:

- `tools/locator-demo-curl.sh`

Example:

```bash
export LOCATOR_BASE_URL="http://localhost:8000"
export LOCATOR_TENANT="demo"

./tools/locator-demo-curl.sh all
```

This will perform:

- a status call,
- suggestions for US and GB examples,
- reverse geocoding for Houston and Kyiv.

Each response is printed as JSON to the terminal (assuming `jq` is
installed). You can also call individual subcommands:

```bash
./tools/locator-demo-curl.sh status
./tools/locator-demo-curl.sh suggest-us
./tools/locator-demo-curl.sh reverse-houston
```

5. Use the HTTP demo file in an IDE

If your IDE supports HTTP files (for example PhpStorm), you can open:

- `tools/locator-http-demo.http`

and trigger the requests directly from the editor. The file contains
ready-to-use examples for status, suggest and reverse operations and
uses simple variables for the base URL and tenant.

6. Map to business scenarios

The demo loop touches several scenarios from the R26 portrait analysis:

- S1 (checkout address validation)
  - US and GB suggestion examples simulate user input on a checkout form.
- S4 (store locator and pickup points)
  - reverse geocoding for Houston and Kyiv shows how to attach behavior
    to coordinates from a map or device location.
- S5 (multi-tenant SaaS)
  - `X-SR-Tenant` header is used to route and track tenant usage.

For each scenario, the next step is to add your own addresses and
countries, and to connect the responses to your front-end or back-end
flows.

7. Next steps

Once you are comfortable with the demo loop:

- enable metrics scraping on `/metrics` and configure dashboards;
- experiment with different tenants and quota settings;
- extend the demo with your own addresses and test cases;
- combine the demo loop with the SLO and golden fixture tools in future
  R-iterations to build a full product-grade QA and observability story.
