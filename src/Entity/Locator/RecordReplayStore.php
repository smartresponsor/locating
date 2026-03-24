<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Entity\Locator;

use App\Bridge\Legacy\Helper\Location\RecordReplayStoreLegacyInterface;

final class RecordReplayStore implements RecordReplayStoreLegacyInterface
{
    /** @var array<string, array{request:array,response:array,ts:int}> */
    private array $m = [];

    public function record(string $key, array $request, array $response): void
    {
        $this->m[$key] = ['request' => $request, 'response' => $response, 'ts' => time()];
    }

    public function replay(string $key): ?array
    {
        return $this->m[$key]['response'] ?? null;
    }
}
