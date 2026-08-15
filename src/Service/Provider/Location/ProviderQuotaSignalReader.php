<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Provider\Location;

use App\Locating\InfrastructureInterface\Provider\Location\Gateway\ProviderQuotaDecisionGatewayInterface;
use App\Locating\Model\Location\ProviderQuotaSignal;
use App\Locating\ModelInterface\Location\ProviderQuotaSignalInterface;
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
        $units = isset($context['units']) ? max(1, (int) $context['units']) : 1;
        $allowed = $this->quotaGateway->allow($this->tenantId, $operation, $units, false);

        return new ProviderQuotaSignal($sourceKey, $operation, $allowed);
    }
}
