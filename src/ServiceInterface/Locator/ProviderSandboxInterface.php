<?php
declare(strict_types=1);

namespace Smartresponsor\ServiceInterface\Locator;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface ProviderSandboxInterface
{
    public function register(string $providerId, ProviderAdapterInterface $adapter): void;
    public function route(string $providerId, array $request): array;
}