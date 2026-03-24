<?php

declare(strict_types=1);

namespace App\Tests\Config;

use PHPUnit\Framework\TestCase;

final class LocationBatchServicesConfigTest extends TestCase
{
    public function testServicesConfigContainsAppBatchAliasesAndWiring(): void
    {
        $contents = (string) file_get_contents(__DIR__.'/../../config/services.php');

        self::assertStringContainsString('use App\\Service\\Batch\\Location\\AddressBatchService;', $contents);
        self::assertStringContainsString('use App\\Service\\Batch\\Location\\AddressBatchServiceMetricDecorator;', $contents);
        self::assertStringContainsString('use App\\ServiceInterface\\Batch\\Location\\AddressBatchServiceInterface;', $contents);
        self::assertStringContainsString('$services->alias(AddressBatchJobFactoryInterface::class, AddressBatchJobFactory::class);', $contents);
        self::assertStringContainsString('$services->alias(AddressBatchServiceInterface::class, AddressBatchServiceMetricDecorator::class);', $contents);
        self::assertStringContainsString("$services->set(AddressBatchJobFactory::class);", $contents);
        self::assertStringContainsString("$services->set(AddressBatchService::class)", $contents);
        self::assertStringContainsString("$services->set(AddressBatchServiceMetricDecorator::class)", $contents);
    }
}
