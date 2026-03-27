<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Service\Location;

use App\Bridge\Legacy\Entity\Location\AddressInputLegacyInterface;

interface ProviderRouterLegacyInterface
{
    public function route(AddressInputLegacyInterface $input, string $region, string $tenantId, array $provider): array;
}
