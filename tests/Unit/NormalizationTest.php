<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use Smartresponsor\Strategy\OpenStreetMapLocator;
use Smartresponsor\Integration\Http\NominatimClient;
final class NormalizationTest extends TestCase{
    public function testClassLoads(): void{
        $this->assertTrue(class_exists(OpenStreetMapLocator::class));
        $this->assertTrue(class_exists(NominatimClient::class));
    }
}
