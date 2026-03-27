<?php

declare(strict_types=1);

namespace App\Tests\Config;

use PHPUnit\Framework\TestCase;

final class LocationBatchServicesConfigTest extends TestCase
{
    public function testServicesConfigContainsAppBatchAliasesAndWiring(): void
    {
        $contents = (string) file_get_contents(__DIR__.'/../../config/services.php');

        self::assertStringContainsString('use App\\Service\\Batch\\Location\\LocationAddressBatchService;', $contents);
        self::assertStringContainsString('use App\\Service\\Batch\\Location\\LocationAddressBatchServiceMetricDecorator;', $contents);
        self::assertStringContainsString('use App\\ServiceInterface\\Batch\\Location\\LocationAddressBatchServiceInterface;', $contents);
        self::assertStringContainsString('$services->alias(AddressBatchJobFactoryInterface::class, AddressBatchJobFactory::class);', $contents);
        self::assertStringContainsString('$services->alias(LocationAddressBatchServiceInterface::class, LocationAddressBatchServiceMetricDecorator::class);', $contents);
        self::assertStringContainsString("$services->set(AddressBatchJobFactory::class);", $contents);
        self::assertStringContainsString("$services->set(LocationAddressBatchService::class)", $contents);
        self::assertStringContainsString("$services->set(LocationAddressBatchServiceMetricDecorator::class)", $contents);
    }
}
