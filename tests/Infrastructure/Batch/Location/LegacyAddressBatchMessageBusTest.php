<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\Batch\Location;

use App\Bridge\Legacy\Batch\Location\AddressBatchLegacyMessage;
use App\Bridge\Legacy\Batch\Location\AddressBatchLegacyMessageBusInterface;
use App\Infrastructure\Batch\Location\LegacyAddressBatchMessageBus;
use App\Infrastructure\Batch\Location\SmartresponsorAddressBatchLegacyMessageBusBackend;
use App\Message\Batch\Location\AddressBatchMessage as AppAddressBatchMessage;
use PHPUnit\Framework\TestCase;

final class LegacyAddressBatchMessageBusTest extends TestCase
{
    public function testDispatchTransformsAppMessageIntoLegacyMessage(): void
    {
        $captured = null;
        $bus = new class($captured) implements AddressBatchLegacyMessageBusInterface {
            public function __construct(private mixed &$captured)
            {
            }

            public function dispatch(AddressBatchLegacyMessage $message): void
            {
                $this->captured = $message;
            }
        };

        $adapter = new LegacyAddressBatchMessageBus(new SmartresponsorAddressBatchLegacyMessageBusBackend($bus));
        $adapter->dispatch(new AppAddressBatchMessage('job-27', ['raw' => 'foo']));

        self::assertInstanceOf(AddressBatchLegacyMessage::class, $captured);
        self::assertSame('job-27', $captured->jobId());
        self::assertSame(['raw' => 'foo'], $captured->payload());
    }
}
