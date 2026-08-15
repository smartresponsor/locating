<?php

declare(strict_types=1);

namespace App\Locating\Tests\Infrastructure\Batch\Location;

use App\Locating\Infrastructure\Batch\Location\MessageBusAddressBatchMessageDispatcher;
use App\Locating\InfrastructureInterface\Batch\Location\AddressBatchMessageBusInterface;
use App\Locating\Message\Batch\Location\AddressBatchMessage;
use App\Locating\MessageInterface\Batch\Location\AddressBatchMessageInterface;
use PHPUnit\Framework\TestCase;

final class MessageBusAddressBatchMessageDispatcherTest extends TestCase
{
    public function testDispatchDelegatesToAppMessageBus(): void
    {
        $captured = null;
        $bus = new class ($captured) implements AddressBatchMessageBusInterface {
            public function __construct(private mixed &$captured)
            {
            }

            public function dispatch(AddressBatchMessageInterface $message): void
            {
                $this->captured = $message;
            }
        };

        $dispatcher = new MessageBusAddressBatchMessageDispatcher($bus);
        $message = new AddressBatchMessage('job-27', ['raw' => 'baz']);
        $dispatcher->dispatch($message);

        self::assertSame($message, $captured);
    }
}
