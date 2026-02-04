# DOM&DRIVE: Layer‑First Isolation for Small Teams and AI‑Accelerated Engineering

*by Oleksandr Tishchenko*

> **Thesis.** Software should visualize the **transactional life of an object** across layers,
> not just delineate bounded contexts. That’s the essence of **DOM&DRIVE** — a layer‑first isolation approach
> where the path layout is `src/<Layer>/<Domain>`, and the architecture mirrors the journey from **Entity**
> to **Template/Locale**.

## Why DOM&DRIVE now

1. **Monoliths are back — for good reasons.** As organizations consolidate services for cost and simplicity,
   the need to see the *whole transaction* across the entire codebase grows.
2. **Teams are smaller; AI is bigger.** AI copilots and micro‑teams operate by responsibility,
   not by domain ownership. They reason about *layers*.
3. **Cognitive load beats theory.** A system you can *see* is a system you can *evolve*.
   Layer‑first gives you instant visual symmetry across all components.

## DDD vs DOM&DRIVE (the short table)

| Aspect | Classic DDD | DOM&DRIVE |
|---|---|---|
| Path layout | `src/<Domain>/<Layer>` | `src/<Layer>/<Domain>` |
| Primary axis | Domain segregation | Transaction continuity |
| IDE navigation | Context‑first | Responsibility‑first |
| Best for | Large orgs with clear domain ownership | Small teams + AI assistants |
| Visual effect | Fragmented across domains | One continuous “vertical” per layer |

## Core principles

- **Layer‑first isolation.** The first directory after `src/` is a layer: `Entity`, `Repository`, `Service`,
  `Api`, `Template`, `Locale`, `Contract`, `Strategy`, `Integration`, `Tests`.
- **Park domains within layers.** Each domain (Address, Order, Vendor…) is a **park** under the layer.
- **One naming contract.** Single hyphen `-` between words, underscore `_` between increment and name
  (e.g., `001_locator-core-bootstrap.zip`). No double hyphens.
- **Self‑sufficient commits.** Each release phase ships with a `fast-import` so history can be reconstructed anywhere.
- **Transaction visibility.** From `Entity` to `Template/Locale` — always visible, always symmetrical.

## What it changes in practice

- **Refactoring speed.** You navigate to *all Services* or *all Entities* across domains in one place.
- **Cross‑domain reuse.** Shared value objects (e.g., `CountryIsoCode`) live where they’re used conceptually.
- **CI/CD clarity.** Layer pipelines match team responsibility lines (lint models, test services, verify API).

## Migration guide

1. **Map layers** in your monolith (`Entity`, `Repository`, `Service`, `Api`, `Template`, `Locale`, etc.).
2. **Move domains** under each layer (`src/Service/Order`, `src/Service/Address`, …).
3. **Set PSR‑4** to a single root (`"SmartResponsor\": "src/"`). Namespaces reflect the layer-first layout.
4. **Adopt commit/pack naming** with single hyphen and increment underscore.
5. **Bundle fast‑imports** into release archives.

## FAQ

**Q: Is this anti‑DDD?**  
*A:* No. It’s a visual/operational evolution. Your aggregates and boundaries still exist —
they’re just organized to reveal **transaction flow**.

**Q: Microservices vs monolith?**  
*A:* DOM&DRIVE favors **monolith first** for velocity and coherence. When a module outgrows the monolith,
its layer‑first layout maps naturally to a standalone package.

**Q: How does AI benefit?**  
*A:* LLMs reason better about consistent folder taxonomies and contracts. DOM&DRIVE maximizes that consistency.

## Example: Address + Locator

- `smartresponsor/address` — domain‑core (owners, relations, persistence)  
- `smartresponsor/locator` — utility‑engine (normalization, validation, geocoding)  
Both follow `src/<Layer>/<Domain>` and integrate cleanly: Address depends on Locator’s contracts — not vice versa.

## Closing

DOM&DRIVE is about **seeing the system as it lives**.  
When layers are first‑class and domains are parks inside them, the transaction of any object is always in sight.
That’s how small teams and AI‑accelerated engineering win.
