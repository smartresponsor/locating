<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Service\Provider\Location;

use App\Entity\Location\ProviderQuotaSignal;
use App\EntityInterface\Location\ProviderQuotaSignalInterface;
use App\InfrastructureInterface\Provider\Location\ProviderQuotaDecisionGatewayInterface;
use App\ServiceInterface\Provider\Location\ProviderQuotaSignalReaderInterface;

final class LegacyProviderQuotaSignalReader implements ProviderQuotaSignalReaderInterface
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
