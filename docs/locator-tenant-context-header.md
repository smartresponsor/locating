# Locator tenant context (header and query)

R19 adds a concrete TenantContext implementation that derives the tenant id
from the current HTTP request.

Implementation: `App\Infrastructure\Locator\RequestTenantContext`

Resolution order:

1. `X-SR-Tenant` header
2. `tenant` query parameter
3. `LOCATOR_DEFAULT_TENANT` environment variable
4. hardcoded fallback `demo`

## Usage

For most production deployments you should:

- send `X-SR-Tenant: <tenant-id>` from your API gateway or upstream services;
- optionally configure `LOCATOR_DEFAULT_TENANT` for local development.

This context is wired into:

- `AddressQuotaGuard` (per-tenant quotas),
- any other services that depend on `TenantContextInterface`.

Service wiring (see `config/services.php`):

- `TenantContextInterface` is aliased to `RequestTenantContext`.
