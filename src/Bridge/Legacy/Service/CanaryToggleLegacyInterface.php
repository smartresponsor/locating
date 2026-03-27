<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Service\Location;

interface CanaryToggleLegacyInterface
{
    public function isEnabled(string $flag, string $tenantId, ?string $userId = null): bool;

    public function enablePercent(string $flag, string $tenantId, int $percent): void;

    public function disable(string $flag, string $tenantId): void;
}
