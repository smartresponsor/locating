# Locator F1 – Demo fixtures for addresses

This envelope adds a canonical demo dataset for the Locator component.

File:

- `fixtures/locator-demo.ndjson`

Format:

- NDJSON (one JSON object per line).
- Each record:

  ```json
  {
    "id": "addr-001",
    "label": "US – clean multi-line address (Houston apartment)",
    "raw": "1944 Katy Fort Bend Rd Apt 5201, Katy, TX 77493, USA",
    "data": {
      "countryCode": "US",
      "region": "TX",
      "city": "Katy",
      "postalCode": "77493",
      "line1": "1944 Katy Fort Bend Rd",
      "line2": "Apt 5201"
    },
    "expectedStatus": "VERIFIED",
    "tags": ["us", "apartment", "shipping", "billing"]
  }
  ```

Fields:

- `id` – stable identifier for the demo address.
- `label` – human-readable description (for docs/UI only).
- `raw` – input string as it would be received from external client.
- `data` – structured payload that can be passed as `data` to `AddressInput::fromArray()`:
  - `countryCode`
  - `region`
  - `city`
  - `postalCode`
  - `line1`
  - `line2` (optional)
  - other optional keys like `building`.
- `expectedStatus` – reference expected outcome (`VERIFIED`, `NEEDS_REVIEW`, `INVALID`).
- `tags` – list of markers (country, edge-case type, etc.).

Typical usage:

- Unit/integration tests:
  - Read the NDJSON file.
  - For each record:
    - call `AddressInput::fromArray(['raw' => $rec['raw'], 'data' => $rec['data'] ?? []])`,
    - pass it through `AddressPipelineInterface`,
    - compare the resulting `AddressResult::status()` with `expectedStatus` where meaningful.
- Demo/seed:
  - Used by future F2/D1 envelopes to populate a demo loop and UI/console demo.

The dataset covers:

- US addresses for apartment, house, mall, PO Box, building name.
- EU examples (DE/FR) with Unicode/accents.
- UA examples with Cyrillic.
- Incomplete/invalid addresses (missing postal code, only city+country, messy formatting)
  for normalization and error-handling scenarios.
