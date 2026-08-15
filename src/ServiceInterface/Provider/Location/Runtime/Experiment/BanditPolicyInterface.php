<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Experiment;

interface BanditPolicyInterface
{
    public function select(array $arm): string;

    public function update(string $armId, float $reward): void;
}
