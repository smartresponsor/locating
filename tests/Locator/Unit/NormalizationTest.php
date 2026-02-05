<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use Smartresponsor\Strategy\Locator\OpenStreetMapLocator;
use Smartresponsor\Integration\Locator\Http\NominatimClient;
final class NormalizationTest extends TestCase{
    public function testClassLoads(): void{
        $this->assertTrue(class_exists(OpenStreetMapLocator::class));
        $this->assertTrue(class_exists(NominatimClient::class));
    }
}
