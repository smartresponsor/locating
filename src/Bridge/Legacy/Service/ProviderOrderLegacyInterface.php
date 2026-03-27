<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Service\Location;

interface ProviderOrderLegacyInterface
{
    public function rank(array $signal, object $bandit): array;
}
