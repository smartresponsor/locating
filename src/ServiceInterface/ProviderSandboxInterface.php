<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace App\ServiceInterface;

/**
 */

interface ProviderSandboxInterface
{
    public function register(string $providerId, ProviderAdapterInterface $adapter): void;
    public function route(string $providerId, array $request): array;
}