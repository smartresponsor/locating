<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service;
final class DryRunSimulator implements DryRunSimulatorInterface {
    /** @var array<string,bool> */
    private array $flag = [];
    public function set(string $tenantId, bool $enabled): void { $this->flag[$tenantId] = $enabled; }
    public function enabled(string $tenantId): bool { return (bool)($this->flag[$tenantId] ?? false); }
    public function simulate(string $op, array $input): array {
        $seed = crc32(json_encode($input));
        $lat = 37.0 + (($seed % 1000)/1000.0);
        $lon = -122.0 - (($seed % 1000)/1000.0);
        $ok = ($seed % 7) != 0;
        return [
            'op'=>$op,
            'ok'=>$ok,
            'lat'=>$lat,
            'lon'=>$lon,
            'providerId'=>'dry-run',
            'confidence'=> $ok ? 0.85 : 0.35,
            'note'=>'Dry-run simulated payload (no external calls)'
        ];
    }
}
