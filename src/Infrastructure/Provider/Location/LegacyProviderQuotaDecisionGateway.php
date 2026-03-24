<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Infrastructure\Provider\Location;

use App\InfrastructureInterface\Provider\Location\ProviderQuotaDecisionBackendInterface;
use App\InfrastructureInterface\Provider\Location\ProviderQuotaDecisionGatewayInterface;

final class LegacyProviderQuotaDecisionGateway implements ProviderQuotaDecisionGatewayInterface
{
    public function __construct(private readonly ProviderQuotaDecisionBackendInterface $backend)
    {
    }

    public function allow(string $tenantId, string $operation, int $units = 1, bool $record = false): bool
    {
        return $this->backend->allow($tenantId, $operation, $units, $record);
    }
}
