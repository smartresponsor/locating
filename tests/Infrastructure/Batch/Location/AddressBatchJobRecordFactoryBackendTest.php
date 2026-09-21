<?php

declare(strict_types=1);

namespace App\Locating\Tests\Infrastructure\Batch\Location;

use App\Locating\Service\Batch\Location\AddressBatchJobRecordFactoryBackend;
use PHPUnit\Framework\TestCase;

final class AddressBatchJobRecordFactoryBackendTest extends TestCase
{
    public function testCreateBuildsRecord(): void
    {
        $backend = new AddressBatchJobRecordFactoryBackend();

        $record = $backend->create('tenant-a', 3);

        self::assertSame('tenant-a', $record->tenantId());
        self::assertSame(3, $record->totalCount());
        self::assertNotSame('', $record->jobId());
    }
}
