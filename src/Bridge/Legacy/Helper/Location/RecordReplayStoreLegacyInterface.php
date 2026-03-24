<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Helper\Location;

interface RecordReplayStoreLegacyInterface
{
    public function record(string $key, array $request, array $response): void;

    public function replay(string $key): ?array;
}
