<?php

declare(strict_types=1);

namespace App\Locating\Tests\Infrastructure\Batch\Location;

use App\Locating\Message\Batch\Location\AddressBatchMessage as AppAddressBatchMessage;
use App\Locating\MessageInterface\Batch\Location\AddressBatchMessageInterface;
use App\Locating\Model\Location\Batch\AddressBatchMessage;
use App\Locating\Service\Batch\Location\AddressBatchMessageBus;
use App\Locating\Service\Batch\Location\AddressBatchMessageBusBackend;
use App\Locating\ServiceInterface\Batch\Location\AddressBatchMessageBusInterface;
use PHPUnit\Framework\TestCase;

final class AddressBatchMessageBusTest extends TestCase
{
    public function testDispatchTransformsAppMessageIntoMessage(): void
    {
        $bus = new class () implements AddressBatchMessageBusInterface {
            public ?AddressBatchMessageInterface $captured = null;

            public function dispatch(AddressBatchMessageInterface $message): void
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
