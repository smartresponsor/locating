<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Provider\Location\Runtime\Experiment;

use App\Locating\ServiceInterface\Provider\Location\Runtime\Experiment\BanditRouterInterface;

final class BanditRouter implements BanditRouterInterface
{
    /** @var array<string, array<string, array<string, array{n:int,avg:float}>>> region=>op=>provider=>stats */
    private array $st = [];
    /** @param list<string> $provider */
    public function select(string $region, string $op, array $provider): string
    {
        if ([] === $provider) {
            return '';
        }
        $t = &$this->st[$region][$op];
        $N = 0;
        foreach ($provider as $id) {
            $row = $t[$id] ?? ['n' => 0,'avg' => 0.0];
            $t[$id] = $row;
            $N += $row['n'];
        }
        $c = 2.0; // exploration factor
        $bestId = (string)$provider[0];
        $bestU = -1.0;
        foreach ($provider as $id) {
            $row = $t[$id];
            $n = max(0, (int)$row['n']);
            $avg = (float)$row['avg'];
            $u = ($n > 0 ? $avg + \sqrt(($c * \log(max(1, $N))) / $n) : 1.0); // optimistic init
            if ($u > $bestU) {
                $bestU = $u;
                $bestId = (string)$id;
            }
        }
        return $bestId;
    }
    public function update(string $region, string $op, string $providerId, float $reward): void
    {
        $current = $this->st[$region][$op][$providerId] ?? ['n' => 0, 'avg' => 0.0];
        $n = $current['n'] + 1;
        $current['avg'] = ($current['avg'] * $current['n'] + max(0.0, min(1.0, $reward))) / $n;
        $current['n'] = $n;
        $this->st[$region][$op][$providerId] = $current;
    }
}
