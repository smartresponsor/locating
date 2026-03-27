<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Service\Location;

interface BanditPolicyLegacyInterface
{
    public function select(array $arm): string;

    public function update(string $armId, float $reward): void;
}
