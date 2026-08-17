<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Provider\Location\Runtime\Contract;

use App\Locating\ServiceInterface\Provider\Location\Runtime\Contract\ProviderContractGoldenInterface;

final class ProviderContractGolden implements ProviderContractGoldenInterface
{
    public function __construct(private string $root = __DIR__.'/../../../tests/Golden')
    {
    }

    /**
     * @param array<string, mixed> $request
     * @param array<string, mixed> $response
     */
    public function record(string $providerId, string $op, array $request, array $response): void
    {
        $p = $this->path($providerId, $op, $request);
        @mkdir(\dirname($p), 0777, true);
        file_put_contents($p, json_encode(['request' => $request, 'response' => $response], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    /**
     * @param array<string, mixed> $request
     * @param array<string, mixed> $response
     */
    public function verify(string $providerId, string $op, array $request, array $response, bool $update = false): bool
    {
        $p = $this->path($providerId, $op, $request);
        if (!is_file($p)) {
            $this->record($providerId, $op, $request, $response);

            return true;
        }
        $gold = json_decode((string) file_get_contents($p), true);
        $goldResponse = is_array($gold) ? ($gold['response'] ?? null) : null;
        $ok = $this->equal($goldResponse, $response);
        if (!$ok && $update) {
            $this->record($providerId, $op, $request, $response);

            return true;
        }

        return $ok;
    }

    /** @param array<string, mixed> $request */
    public function path(string $providerId, string $op, array $request): string
    {
        $hash = substr(hash('sha256', json_encode($request, JSON_THROW_ON_ERROR)), 0, 16);

        return rtrim($this->root, '/').'/'.$providerId.'/'.$op.'/'.$hash.'.golden.json';
    }

    private function equal(mixed $a, mixed $b): bool
    {
        return json_encode($a, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR) === json_encode($b, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
    }
}
