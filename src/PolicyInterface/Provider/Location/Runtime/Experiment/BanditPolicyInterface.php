<?php

declare(strict_types=1);

namespace App\Locating\PolicyInterface\Provider\Location\Runtime\Experiment;

interface BanditPolicyInterface
{
    /** @param array<string,float|int> $arm */
    public function select(array $arm): string;

    public function update(string $armId, float $reward): void;
}
