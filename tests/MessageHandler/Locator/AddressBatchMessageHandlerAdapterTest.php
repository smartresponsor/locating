<?php

declare(strict_types=1);

namespace Tests\MessageHandler\Locator;

use App\Bridge\Legacy\Batch\Location\AddressBatchLegacyMessage;
use App\Bridge\Legacy\Batch\Location\AddressBatchLegacyMessageHandler;
use App\MessageHandlerInterface\Batch\Location\AddressBatchMessageHandlerInterface;
use App\MessageInterface\Batch\Location\AddressBatchMessageInterface;
use PHPUnit\Framework\TestCase;

final class AddressBatchMessageHandlerAdapterTest extends TestCase
{
    public function testAdaptsLegacyMessageToAppHandler(): void
    {
        $captured = [];

        $appHandler = new class($captured) implements AddressBatchMessageHandlerInterface {
            /** @param array<string,mixed> $captured */
            public function __construct(private array &$captured)
            {
            }

            public function __invoke(AddressBatchMessageInterface $message): void
            {
                $this->captured['jobId'] = $message->jobId();
                $this->captured['payload'] = $message->payload();
            }
        };

        $handler = new AddressBatchLegacyMessageHandler($appHandler);
        $handler(new AddressBatchLegacyMessage('job-99', ['raw' => '500 Example Rd']));

        self::assertSame('job-99', $captured['jobId']);
        self::assertSame(['raw' => '500 Example Rd'], $captured['payload']);
    }
}
