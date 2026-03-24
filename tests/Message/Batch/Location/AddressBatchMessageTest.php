<?php

declare(strict_types=1);

namespace Tests\Message\Batch\Location;

use App\Message\Batch\Location\AddressBatchMessage;
use PHPUnit\Framework\TestCase;

final class AddressBatchMessageTest extends TestCase
{
    public function testExposesJobIdAndPayload(): void
    {
        $message = new AddressBatchMessage('job-42', ['raw' => '123 Main St']);

        self::assertSame('job-42', $message->jobId());
        self::assertSame(['raw' => '123 Main St'], $message->payload());
    }
}
