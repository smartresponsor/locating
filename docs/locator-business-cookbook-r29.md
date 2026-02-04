Locator business cookbook – R29

This cookbook gives concrete recipes for using Locator in typical product
and integration scenarios. It builds on top of the technical primitives
(pipeline, suggest, reverse, batch, quotas, metrics) and connects them to
everyday business flows.

The goal is to minimise the distance between "we have an address API" and
"we have a working checkout, profile and bulk import that use it".

1. Scenario C1 – e-commerce checkout address validation

Context

A user fills a shipping address on a checkout page. The goal is to help
them enter a valid address quickly and avoid failed deliveries.

Inputs and outputs

- Input: free-form address text and optionally selected country.
- Output: a structured, validated address record (for example
  AddressResult) stored alongside the order.

Minimal flow

1) Suggest while typing

- Front-end calls the suggest endpoint whenever the user types or changes
  the address line:

  - `GET {BASE_URL}/locator/address/suggest`
  - query parameters:
    - `query` – the text the user typed,
    - `country` – ISO country code (optional but recommended),
    - `limit` – number of suggestions (5–10 is typical).

- Response contains a list of suggestion items with enough data to show a
  human-friendly address string and an identifier that can be passed back
  to the server.

2) Confirm and normalize

- When the user selects a suggestion, the back-end either:
  - uses the suggestion payload as-is to build AddressResult; or
  - calls an internal pipeline/normalization service to enrich and
    validate the suggested address.

- The resulting AddressResult is stored in the order and can be logged for
  later analysis (delivery failures, corrections, etc.).

3) Observe quality and performance

- Use the metrics from the observability pack (R28) to monitor:

  - request counts and error rates for `operation="suggest"`;
  - p95 latency for suggest;
  - quota denials for tenants that are close to their limits.

- Combine checkout logs with Locator metrics to understand which address
  patterns cause most friction for users.

2. Scenario C2 – customer address profile (account settings)

Context

A user manages their saved addresses in an account profile. The goal is to
keep address data clean over time and avoid inconsistencies between
checkout and profile.

Inputs and outputs

- Input: existing stored addresses and user edits.
- Output: updated structured addresses that remain compatible with the
  checkout flow.

Minimal flow

1) Prefill and edit

- When opening the address edit form, the front-end pre-fills the fields
  from the stored AddressResult.
- While the user edits, the same suggest mechanism from C1 is used to
  offer better or more precise addresses.

2) Validate on save

- On form submission, the back-end uses Locator to:

  - check that required fields are not empty;
  - confirm that the combination of street, city, region and postcode is
    consistent for the given country;

  and returns either:

  - a validated AddressResult; or
  - a list of issues (for example "postcode does not match city").

3) Align with checkout

- Use the same transformation and validation logic in both checkout and
  profile flows.
- If AddressResult evolves (new fields, scoring), make sure both flows
  adopt the changes together.

3. Scenario C3 – bulk address validation (CSV import)

Context

An operator uploads a CSV file with many addresses (for example from a
legacy CRM). The goal is to validate and standardize these addresses
without timeouts and with clear reporting.

Inputs and outputs

- Input: CSV file with raw address lines and columns such as street, city,
  postcode, country.
- Output: result file with:

  - normalized address fields;
  - validation status (ok, partial, invalid);
  - error messages for problematic rows.

Minimal flow

1) Pre-process the CSV

- Convert the CSV into a JSON payload suitable for Locator batch
  processing, for example:

  - `items`: list of objects with raw address and optional metadata
    (source system, country, row identifier).

2) Submit a batch job

- Call a batch endpoint or service (for example via `BatchController` or
  message-based batch pipeline):

  - provide the list of items;
  - specify a deadline or timeout per batch.

- The batch service enqueues or processes the items, using the same
  pipeline as the synchronous API but with appropriate timeouts and
  retries.

3) Collect results

- Once processing is complete, fetch the result set:

  - each item has a status (`ok`, `partial`, `invalid`);
  - normalized address fields are included when available;
  - error messages describe why a row could not be validated.

4) Feed back to the business process

- Provide the result CSV back to the operator.
- Define rules for:

  - when to accept a partial address;
  - when a row must be corrected or escalated.

4. Scenario C4 – store locator and pickup points (reverse geocoding)

Context

A user wants to find a nearby pickup point or store. Their location is
known (device location or map coordinates), and the goal is to show a list
of nearby locations with usable addresses.

Inputs and outputs

- Input: latitude and longitude, optionally country and search radius.
- Output: list of locations with human-friendly addresses and distance
  information.

Minimal flow

1) From coordinates to address

- Use a reverse geocoding capability of Locator to translate coordinates
  into a nearby address:

  - `GET {BASE_URL}/locator/address/reverse`
  - query parameters:
    - `lat`, `lon`,
    - optional `country`.

- The response contains one or more nearby addresses that can be used as a
  visible label or as a starting point for a search.

2) Overlay on store data

- Combine reverse geocoding results with your own store or pickup point
  database:

  - filter stores within a certain radius from the user location;
  - sort by distance and optionally by store attributes (opening hours,
    availability, rating).

3) Cache and performance

- Cache reverse geocoding results on a short TTL to avoid unnecessary
  provider calls.
- Use Locator metrics to watch for spikes in reverse calls and latency.

5. Scenario C5 – multi-tenant SaaS quotas and metrics

Context

Locator is used as a shared component across many tenants in a SaaS
platform. The goal is to enforce fair usage and provide clear visibility
into how each tenant uses the service.

Inputs and outputs

- Input: tenant identifiers passed in HTTP headers or tokens.
- Output: per-tenant limits and usage metrics that can be monitored and
  adjusted.

Minimal flow

1) Identify tenants

- For each request, extract the tenant identifier (for example from
  `X-SR-Tenant` header or an authenticated principal).
- Pass the tenant id to the quota and metric components.

2) Enforce quotas

- Use the quota guard and manager to decide whether to allow or deny a
  request:

  - soft limits: return a response with an indication that the quota was
    reached but without breaking the UI;
  - hard limits: fail with a well-defined status and error body.

3) Measure and adjust

- Track per-tenant usage and quota decisions in metrics (for example using
  `tenant_quota_*` counters).
- Build dashboards and periodic reports to:

  - identify tenants that constantly approach their limits;
  - propose plan upgrades or configuration changes.

6. How to extend these recipes

The scenarios above are intentionally minimal. In practice you may want to
extend them by:

- adding address quality scores and confidence levels;
- integrating with additional providers for specific countries;
- building admin tools for support teams to inspect problematic addresses.

When adding new flows, try to:

- reuse the primitives already present in Locator (pipeline, suggest,
  reverse, batch);
- keep metrics and quotas in the loop from day one;
- document the flow in the same style as C1–C5 so that future maintainers
  can understand the business intent as well as the API calls.
