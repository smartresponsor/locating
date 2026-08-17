<?php

declare(strict_types=1);

namespace App\Locating\Infrastructure\Provider\Location\Http;

interface HttpClientInterface
{
    /**
     * @param array<string,mixed> $o
     * @return array<string,mixed>
     */
    public function get(string $u, array $o = []): array;

    /** @param array<string,mixed> $o */
    public function getRaw(string $u, array $o = []): string;
}
