<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Batch\Location;

use App\Infrastructure\Batch\Location\SmartresponsorAddressBatchJobRecordFactoryBackend;
use PHPUnit\Framework\TestCase;

final class SmartresponsorAddressBatchJobRecordFactoryBackendTest extends TestCase
{
    public function testCreateBuildsRecord(): void
    {
        $backend = new SmartresponsorAddressBatchJobRecordFactoryBackend();

        $record = $backend->create('tenant-a', 3);

        self::assertSame('tenant-a', $record->tenantId());
        self::assertSame(3, $record->totalCount());
        self::assertNotSame('', $record->jobId());
    }
}
