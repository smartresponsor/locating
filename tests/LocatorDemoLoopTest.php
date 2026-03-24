<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;

use App\InfrastructureInterface\LocatorFixtureReaderInterface;
use App\Service\LocatorDemoLoop;
use App\ServiceInterface\AddressPipelineInterface;
use PHPUnit\Framework\TestCase;

final class LocatorDemoLoopTest extends TestCase
{
    public function testRunLoopUsesFixtureReaderAndPipeline(): void
    {
        $fixtureReader = new class implements LocatorFixtureReaderInterface {
            public array $recordList = [
                ['raw' => '10 Downing St, London', 'tenantId' => 'tenant-demo'],
                ['raw' => '1600 Amphitheatre Parkway, Mountain View, CA 94043, US', 'tenantId' => 'tenant-demo'],
            ];

            public function readFixture(string $filePath): array
            {
                return $this->recordList;
            }
        };

        $counter = new class implements AddressPipelineInterface {
            public int $callCount = 0;

            public function process(\App\Entity\AddressInput $input): \App\EntityInterface\AddressResultInterface
            {
                $this->callCount++;

                return \App\Entity\AddressResult::create(
                    \App\Entity\AddressStatus::VERIFIED,
                    null
                );
            }
        };

        $loop = new LocatorDemoLoop($fixtureReader, $counter, '/dev/null');

        $loop->runLoop('tenant-demo', 2, 0);

        self::assertSame(
            4,
            $counter->callCount,
            'Demo loop should call pipeline once for each record and round.'
        );
    }
}
