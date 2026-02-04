# Locator R13 – Suggest Ranking

Goal
----

Make the `suggest` flow usable for production autocomplete: combine candidate suggestions from all providers, apply a dedicated ranker, and expose a simple `score` and `reason` in the DTO/JSON while keeping the R12 quota behaviour.

Scope
-----

This iteration introduces:

- `SuggestRankerInterface` and `SuggestRanker` service.
- Extended `AddressSuggestion` DTO with optional `score` and `reason`.
- Integration of the ranker into `AddressSuggest` service.
- Lightweight tests for ranking logic.

Ranking signals
---------------

The ranker uses a few simple signals:

- String match:
  - `prefix`: suggestion `label` starts with the query string.
  - `word`: query appears as a separate word inside the label.
  - `substring`: query appears anywhere in the label.
- Country boost:
  - If `countryCode` parameter matches the suggestion country, a positive weight is added.
- Noise penalty:
  - If a label contains many non-alphanumeric characters, a small negative weight is applied.

Each suggestion gets:

- `score` – total float score based on the signals.
- `reason` – a map of `{signal => weight}` for debugging.

HTTP contract
-------------

The `AddressSuggestController` continues to return the same top-level structure:

- On success:

```json
{
  "status": "ok",
  "items": [
    {
      "label": "123 Main St, Houston, TX 77001, United States",
      "address": { "...": "..." },
      "providerKey": "provider-demo",
      "score": 5.5,
      "reason": {
        "prefix": 3.0,
        "country": 1.5,
        "substring": 1.0
      }
    }
  ]
}
```

- On empty query:

```json
{
  "status": "ok",
  "items": []
}
```

- On quota exceeded (from R12):

```json
{
  "status": "error",
  "error": "quota_exceeded",
  "operation": "suggest",
  "items": [],
  "quotaExceeded": true
}
```

Using `score` on the client
---------------------------

The recommended usage on the client side:

- Sort UI suggestions by `score` (already performed by the backend).
- Treat `score` as a relative ordering hint, not as an absolute confidence value.
- Optionally, show only top N items (backend already respects `limit`).

Tests
-----

R13 adds tests (or stubs for future tests) under `tests/Locator/Service` for the ranker:

- Ensure prefix matches outrank substring matches.
- Ensure correct country suggestions are boosted.
- Ensure `score` and `reason` are set on ranked suggestions.
