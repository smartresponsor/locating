<?php

declare(strict_types=1);

namespace App\Locating\Infrastructure\Provider\Location\Http;

interface HttpClientInterface
{
    public function get(string $u, array $o = []): array;
    public function getRaw(string $u, array $o = []): string;
}
