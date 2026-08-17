<?php

declare(strict_types=1);

namespace App\Locating\Tests\Infrastructure\Batch\Location;

use App\Locating\Infrastructure\Batch\Location\AddressBatchResultBackend;
use PHPUnit\Framework\TestCase;

final class AddressBatchResultBackendTest extends TestCase
{
    public function testCreateBuildsRecordArray(): void
    {
        $backend = new AddressBatchResultBackend();

        $record = $backend->create('accepted', ['street' => '1 Main'], [
            ['field' => 'street', 'code' => 'normalized', 'message' => 'ok'],
        ]);

        self::assertSame('accepted', $record->toArray()['status']);
    }
}
