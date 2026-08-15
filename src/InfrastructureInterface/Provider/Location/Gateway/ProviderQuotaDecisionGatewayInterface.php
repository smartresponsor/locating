<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\InfrastructureInterface\Provider\Location\Gateway;

interface ProviderQuotaDecisionGatewayInterface
{
    public function allow(string $tenantId, string $operation, int $units = 1, bool $record = false): bool;
}
