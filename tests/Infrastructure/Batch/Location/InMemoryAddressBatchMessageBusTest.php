<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\Batch\Location;

use App\Infrastructure\Batch\Location\InMemoryAddressBatchMessageBus;
use App\Message\Batch\Location\AddressBatchMessage;
use App\MessageHandlerInterface\Batch\Location\AddressBatchMessageHandlerInterface;
use PHPUnit\Framework\TestCase;

final class InMemoryAddressBatchMessageBusTest extends TestCase
{
    public function testDispatchInvokesAppHandler(): void
    {
        $seen = [];
        $handler = new class($seen) implements AddressBatchMessageHandlerInterface {
            public function __construct(private array &$seen)
            {
            }

            public function __invoke(\App\MessageInterface\Batch\Location\AddressBatchMessageInterface $message): void
            {
                $this->seen[] = [$message->jobId(), $message->payload()];
            }
        };

        $bus = new InMemoryAddressBatchMessageBus($handler);
        $bus->dispatch(new AddressBatchMessage('job-27', ['raw' => 'bar']));

        self::assertSame([['job-27', ['raw' => 'bar']]], $seen);
    }
}
