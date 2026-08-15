<?php

declare(strict_types=1);

namespace App\Locating\InfrastructureInterface\Provider\Location\Http;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface HttpClientInterface
{
    public function get(string $url, array $headers = [], int $timeoutMs = 800): array;
    public function postJson(string $url, array $payload, array $headers = [], int $timeoutMs = 800): array;
}
