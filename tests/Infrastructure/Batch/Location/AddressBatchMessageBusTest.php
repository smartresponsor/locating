<?php

declare(strict_types=1);

namespace App\Locating\Tests\Infrastructure\Batch\Location;

use App\Locating\Infrastructure\Batch\Location\AddressBatchMessageBus;
use App\Locating\Infrastructure\Batch\Location\AddressBatchMessageBusBackend;
use App\Locating\Message\Batch\Location\AddressBatchMessage as AppAddressBatchMessage;
use App\Locating\Model\Location\Batch\AddressBatchMessage;
use App\Locating\Model\Location\Batch\AddressBatchMessageBusInterface;
use PHPUnit\Framework\TestCase;

final class AddressBatchMessageBusTest extends TestCase
{
    public function testDispatchTransformsAppMessageIntoMessage(): void
    {
        $captured = null;
        $bus = new class ($captured) implements AddressBatchMessageBusInterface {
            public function __construct(private mixed &$captured)
            {
            }

            public function dispatch(AddressBatchMessage $message): void
            {
                $this->captured = $message;
            }
        };

        $adapter = new AddressBatchMessageBus(new AddressBatchMessageBusBackend($bus));
        $adapter->dispatch(new AppAddressBatchMessage('job-27', ['raw' => 'foo']));

        self::assertInstanceOf(AddressBatchMessage::class, $captured);
        self::assertSame('job-27', $captured->jobId());
        self::assertSame(['raw' => 'foo'], $captured->payload());
    }
}
