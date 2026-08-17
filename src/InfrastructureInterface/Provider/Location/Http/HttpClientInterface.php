<?php

declare(strict_types=1);

namespace App\Locating\InfrastructureInterface\Provider\Location\Http;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface HttpClientInterface
{
    /**
     * @param array<string,string> $headers
     * @return array<string,mixed>
     */
    public function get(string $url, array $headers = [], int $timeoutMs = 800): array;

    /**
     * @param array<string,mixed> $payload
     * @param array<string,string> $headers
     * @return array<string,mixed>
     */
    public function postJson(string $url, array $payload, array $headers = [], int $timeoutMs = 800): array;
}
