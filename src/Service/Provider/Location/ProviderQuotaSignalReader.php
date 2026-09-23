<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Provider\Location;

use App\Locating\ReadModel\Observability\Location\ProviderQuotaSignal;
use App\Locating\ReadModelInterface\Observability\Location\ProviderQuotaSignalInterface;
use App\Locating\ServiceInterface\Provider\Location\ProviderQuotaDecisionGatewayInterface;
use App\Locating\ServiceInterface\Provider\Location\ProviderQuotaSignalReaderInterface;

final class ProviderQuotaSignalReader implements ProviderQuotaSignalReaderInterface
{
    public function __construct(
        private readonly ProviderQuotaDecisionGatewayInterface $quotaGateway,
        private readonly string $tenantId = 'default',
    ) {
    }

    public function read(string $sourceKey, string $operation, array $context = []): ProviderQuotaSignalInterface
    {
        $unitsValue = $context['units'] ?? null;
        $units = is_numeric($unitsValue) ? max(1, (int) $unitsValue) : 1;
        $allowed = $this->quotaGateway->allow($this->tenantId, $operation, $units, false);

        return new ProviderQuotaSignal($sourceKey, $operation, $allowed);
    }
}
