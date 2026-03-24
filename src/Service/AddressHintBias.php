<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service;
final class AddressHintBias implements AddressHintBiasInterface {
    /** @var array<string, array<string,float>> */
    private array $w = [];
    public function set(string $tag, string $region, float $weight): void {
        $this->w[$tag] = $this->w[$tag] ?? [];
        $this->w[$tag][$region] = max(0.0, min(2.0, $weight));
    }
    public function weight(array $hint, string $region): float {
        $mul = 1.0;
        foreach ((array)$hint as $tag) {
            $mul *= (float)($this->w[(string)$tag][$region] ?? 1.0);
        }
        return max(0.25, min(4.0, $mul));
    }
}
