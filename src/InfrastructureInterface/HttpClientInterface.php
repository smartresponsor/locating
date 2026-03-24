<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\InfrastructureInterface;

/**
 */

interface HttpClientInterface
{
    public function get(string $url, array $headers = [], int $timeoutMs = 800): array;
    public function postJson(string $url, array $payload, array $headers = [], int $timeoutMs = 800): array;
}