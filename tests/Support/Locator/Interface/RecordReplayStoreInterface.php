<?php

declare(strict_types=1);

namespace App\Locating\Tests\Support\Locator\Interface;

interface RecordReplayStoreInterface
{
    public function record(string $key, array $request, array $response): void;

    public function replay(string $key): ?array;
}
