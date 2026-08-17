<?php

declare(strict_types=1);

namespace App\Locating\Tests\Infrastructure\Batch\Location;

use App\Locating\Infrastructure\Batch\Location\AddressBatchMessageBus;
use App\Locating\Infrastructure\Batch\Location\AddressBatchMessageBusBackend;
use App\Locating\Message\Batch\Location\AddressBatchMessage as AppAddressBatchMessage;
use App\Locating\Model\Location\Batch\AddressBatchMessage;
use App\Locating\ServiceInterface\Location\Batch\AddressBatchMessageBusInterface;
use PHPUnit\Framework\TestCase;

final class AddressBatchMessageBusTest extends TestCase
{
    public function testDispatchTransformsAppMessageIntoMessage(): void
    {
        $bus = new class () implements AddressBatchMessageBusInterface {
            public ?AddressBatchMessage $captured = null;

            public function dispatch(AddressBatchMessage $message): void
            {
                $this->captured = $message;
            }
        };

        $adapter = new AddressBatchMessageBus(new AddressBatchMessageBusBackend($bus));
        $adapter->dispatch(new AppAddressBatchMessage('job-27', ['raw' => 'foo']));

        self::assertInstanceOf(AddressBatchMessage::class, $bus->captured);
        self::assertSame('job-27', $bus->captured->jobId());
        self::assertSame(['raw' => 'foo'], $bus->captured->payload());
    }
}
