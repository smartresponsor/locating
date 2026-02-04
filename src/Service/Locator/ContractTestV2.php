<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Service\Locator;
final class ContractTestV2 implements ContractTestV2Interface {
    /** @var array<string, array<string, array<string, array{req:array, opt:array}>>> */
    private array $schema = [];
    public function __construct() {
        // Minimal schema for geocode v1/v2
        $this->schema['*']['geocode']['v1'] = ['req'=>['lat','lon','providerId'], 'opt'=>['confidence','raw']];
        $this->schema['*']['geocode']['v2'] = ['req'=>['lat','lon','providerId','normalized'], 'opt'=>['confidence','raw']];
    }
    public function validate(string $providerId, string $op, string $version, array $response): array {
        $prov = $this->schema[$providerId][$op][$version] ?? $this->schema['*'][$op][$version] ?? null;
        if ($prov === null) { return ['no-schema']; }
        $issue = [];
        foreach ($prov['req'] as $k){ if (!array_key_exists($k, $response)) { $issue[]='missing:'.$k; } }
        if (isset($response['lat']) && (!is_numeric($response['lat']) || $response['lat']>90 || $response['lat']<-90)) { $issue[]='bad:lat'; }
        if (isset($response['lon']) && (!is_numeric($response['lon']) || $response['lon']>180 || $response['lon']<-180)) { $issue[]='bad:lon'; }
        return $issue;
    }
}
