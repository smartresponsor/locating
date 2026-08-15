<?php

declare(strict_types=1);

namespace App\Locating\Tests\Infrastructure\Batch\Location;

use App\Locating\Infrastructure\Batch\Location\InMemoryAddressBatchMessageBus;
use App\Locating\Message\Batch\Location\AddressBatchMessage;
use App\Locating\MessageHandlerInterface\Batch\Location\AddressBatchMessageHandlerInterface;
use PHPUnit\Framework\TestCase;

final class InMemoryAddressBatchMessageBusTest extends TestCase
{
    public function testDispatchInvokesAppHandler(): void
    {
        $seen = [];
        $handler = new class ($seen) implements AddressBatchMessageHandlerInterface {
            public function __construct(private array &$seen)
            {
            }

            public function __invoke(\App\Locating\MessageInterface\Batch\Location\AddressBatchMessageInterface $message): void
            {
                $this->seen[] = [$message->jobId(), $message->payload()];
            }
        };

        $bus = new InMemoryAddressBatchMessageBus($handler);
        $bus->dispatch(new AddressBatchMessage('job-27', ['raw' => 'bar']));

        self::assertSame([['job-27', ['raw' => 'bar']]], $seen);
    }
}
