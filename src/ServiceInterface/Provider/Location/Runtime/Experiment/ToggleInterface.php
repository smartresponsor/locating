<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Experiment;

interface ToggleInterface
{
    public function isEnabled(string $key, string $tenantId = '', int $seed = 0): bool;

    public function set(string $key, bool $enabled, int $percent = 100, string $tenantId = ''): void;
}
