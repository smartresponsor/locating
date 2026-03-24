<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Service\Location;

interface PolicyLegacyInterface
{
    public function order(string $purpose, ?string $region): array;
}
