<?php
declare(strict_types=1);

namespace App\InfrastructureInterface\Locator;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface CurlHttpClientInterface
{
    public function get(string $u,array $o=[]): array;
    public function getRaw(string $u,array $o=[]): string;
}