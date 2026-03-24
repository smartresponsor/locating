<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service\Locator;

use App\Bridge\Legacy\Provider\Location\ProviderContractGoldenLegacyInterface;

final class ProviderContractGolden implements ProviderContractGoldenLegacyInterface
{
    public function __construct(private string $root = __DIR__.'/../../../tests/Golden')
    {
    }

    public function record(string $providerId, string $op, array $request, array $response): void
    {
        $p = $this->path($providerId, $op, $request);
        @mkdir(\dirname($p), 0777, true);
        file_put_contents($p, json_encode(['request' => $request, 'response' => $response], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    public function verify(string $providerId, string $op, array $request, array $response, bool $update = false): bool
    {
        $p = $this->path($providerId, $op, $request);
        if (!is_file($p)) {
            $this->record($providerId, $op, $request, $response);

            return true;
        }
        $gold = json_decode((string) file_get_contents($p), true);
        $ok = $this->equal($gold['response'] ?? null, $response);
        if (!$ok && $update) {
            $this->record($providerId, $op, $request, $response);

            return true;
        }

        return $ok;
    }

    public function path(string $providerId, string $op, array $request): string
    {
        $hash = substr(hash('sha256', json_encode($request)), 0, 16);

        return rtrim($this->root, '/').'/'.$providerId.'/'.$op.'/'.$hash.'.golden.json';
    }

    private function equal($a, $b): bool
    {
        return json_encode($a, JSON_UNESCAPED_SLASHES) === json_encode($b, JSON_UNESCAPED_SLASHES);
    }
}
