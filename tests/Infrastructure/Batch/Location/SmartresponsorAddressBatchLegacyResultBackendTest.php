<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Batch\Location;

use App\Infrastructure\Batch\Location\SmartresponsorAddressBatchLegacyResultBackend;
use PHPUnit\Framework\TestCase;

final class SmartresponsorAddressBatchLegacyResultBackendTest extends TestCase
{
    public function testCreateBuildsRecordArray(): void
    {
        $backend = new SmartresponsorAddressBatchLegacyResultBackend();

        $record = $backend->create('accepted', ['street' => '1 Main'], [
            ['field' => 'street', 'code' => 'normalized', 'message' => 'ok'],
        ]);

        self::assertIsArray($record->toArray());
    }
}
