<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service\Locator;
final class Toggle implements ToggleInterface {
    /** @var array<string, array{enabled:bool,percent:int,tenant?:string}> */
    private array $map = [];
    public function isEnabled(string $key, string $tenantId='', int $seed=0): bool {
        $row = $this->map[$key] ?? ['enabled'=>false,'percent'=>0];
        if (!$row['enabled']) { return false; }
        if (isset($row['tenant']) && $tenantId !== '' && $row['tenant'] !== $tenantId) { return false; }
        $pct = max(0, min(100, (int)$row['percent']));
        if ($pct >= 100) { return true; }
        $h = crc32($key . '|' . $tenantId . '|' . $seed);
        return ($h % 100) < $pct;
    }
    public function set(string $key, bool $enabled, int $percent=100, string $tenantId=''): void {
        $row = ['enabled'=>$enabled, 'percent'=>$percent];
        if ($tenantId !== '') { $row['tenant'] = $tenantId; }
        $this->map[$key] = $row;
    }
}
