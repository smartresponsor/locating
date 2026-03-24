<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\InfrastructureInterface;

/**
 */

interface FixtureHttpClientInterface
{
    public function get(string $u,array $o=[]): array;
    public function getRaw(string $u,array $o=[]): string;
}