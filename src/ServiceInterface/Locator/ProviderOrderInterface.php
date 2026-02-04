<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain\Locator;
interface ProviderOrderInterface {
    /**
     * Rank providers for tenant/region based on health/latency/cost signals and bandit feedback.
     * @param array $signal map providerId => ['latency_ms'=>float,'error_rate'=>float,'unit_cost'=>float,'health'=>float]
     * @return array ordered list of provider ids
     */
    public function rank(array $signal, BanditPolicyInterface $bandit): array;
}
