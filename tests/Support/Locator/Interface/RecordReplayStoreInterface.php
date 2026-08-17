<?php

declare(strict_types=1);

namespace App\Locating\Tests\Support\Locator\Interface;

interface RecordReplayStoreInterface
{
    /**
     * @param array<string,mixed> $request
     * @param array<string,mixed> $response
     */
    public function record(string $key, array $request, array $response): void;

    /** @return array<string,mixed>|null */
    public function replay(string $key): ?array;
}
