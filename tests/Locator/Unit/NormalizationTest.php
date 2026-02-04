<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use SmartResponsor\Strategy\Locator\OpenStreetMapLocator;
use SmartResponsor\Integration\Locator\Http\NominatimClient;
final class NormalizationTest extends TestCase{
    public function testClassLoads(): void{
        $this->assertTrue(class_exists(OpenStreetMapLocator::class));
        $this->assertTrue(class_exists(NominatimClient::class));
    }
}
