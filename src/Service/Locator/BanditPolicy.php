<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service\Locator;

use App\Bridge\Legacy\Service\Location\BanditPolicyLegacyInterface;

final class BanditPolicy implements BanditPolicyLegacyInterface
{
    private float $eps;
    /** @var array<string, array{n:int, avg:float}> */
    private array $stat = [];

    public function __construct(float $eps = 0.1)
    {
        $this->eps = max(0.0, min(1.0, $eps));
    }

    public function select(array $arm): string
    {
        if (empty($arm)) {
            return '';
        }
        if (mt_rand() / mt_getrandmax() < $this->eps) {
            return array_keys($arm)[array_rand($arm)];
        }
        $bestId = '';
        $best = -INF;
        foreach ($arm as $id => $w) {
            $s = $this->stat[$id]['avg'] ?? 0.0;
            if ($s > $best) {
                $best = $s;
                $bestId = $id;
            }
        }

        return $bestId ?: array_keys($arm)[0];
    }

    public function update(string $armId, float $reward): void
    {
        $st = $this->stat[$armId] ?? ['n' => 0, 'avg' => 0.0];
        ++$st['n'];
        $st['avg'] += ($reward - $st['avg']) / $st['n'];
        $this->stat[$armId] = $st;
    }
}
