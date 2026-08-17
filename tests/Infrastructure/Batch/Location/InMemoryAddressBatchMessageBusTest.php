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
        $handler = new class () implements AddressBatchMessageHandlerInterface {
            /** @var list<array{0:string,1:array<string,mixed>}> */
            public array $seen = [];

            public function __invoke(\App\Locating\MessageInterface\Batch\Location\AddressBatchMessageInterface $message): void
            {
                $this->seen[] = [$message->jobId(), $message->payload()];
            }
        };

        $bus = new InMemoryAddressBatchMessageBus($handler);
        $bus->dispatch(new AddressBatchMessage('job-27', ['raw' => 'bar']));

        self::assertSame([['job-27', ['raw' => 'bar']]], $handler->seen);
    }
}
