<?php

declare(strict_types=1);

namespace App\Locating\Tests\MessageHandler\Locator;

use App\Locating\InfrastructureInterface\Batch\Location\AddressBatchJobProgressWriterInterface;
use App\Locating\InfrastructureInterface\Batch\Location\AddressBatchResultWriterInterface;
use App\Locating\Message\Batch\Location\AddressBatchMessage;
use App\Locating\MessageHandler\Batch\Location\AddressBatchMessageHandler;
use App\Locating\Model\Location\AddressPipelineResult;
use App\Locating\ServiceInterface\Address\Location\AddressPipelineInterface;
use PHPUnit\Framework\TestCase;

final class AddressBatchMessageHandlerTest extends TestCase
{
    public function testHandlesMessageWithAppOwnedHandler(): void
    {
        $captured = [];

        $pipeline = new class ($captured) implements AddressPipelineInterface {
            /** @param array<string,mixed> $captured */
            public function __construct(private array &$captured)
            {
            }

            /** @return array<string,mixed> */
            public function captured(): array
            {
                return $this->captured;
            }

            public function process(\App\Locating\ModelInterface\Location\AddressInputInterface $input): \App\Locating\ModelInterface\Location\AddressPipelineResultInterface
            {
                $this->captured['raw'] = $input->raw();

                return new AddressPipelineResult(AddressPipelineResult::STATUS_VERIFIED, null, []);
            }
        };

        $jobProgressWriter = new class () implements AddressBatchJobProgressWriterInterface {
            /** @var list<array{string,string}> */
            public array $calls = [];

            public function markRun(string $jobId): bool
            {
                $this->calls[] = ['markRun', $jobId];

                return true;
            }

            public function incrementProcessed(string $jobId): bool
            {
                $this->calls[] = ['incrementProcessed', $jobId];

                return true;
            }
        };

        $resultWriter = new class ($captured) implements AddressBatchResultWriterInterface {
            /** @param array<string,mixed> $captured */
            public function __construct(private array &$captured)
            {
            }

            /** @return array<string,mixed> */
            public function captured(): array
            {
                return $this->captured;
            }

            public function appendPipelineResult(string $jobId, \App\Locating\ModelInterface\Location\AddressPipelineResultInterface $result): void
            {
                $this->captured['jobId'] = $jobId;
                $this->captured['status'] = $result->status();
            }
        };

        $handler = new AddressBatchMessageHandler($pipeline, $jobProgressWriter, $resultWriter);
        $handler(new AddressBatchMessage('job-99', ['raw' => '500 Example Rd']));

        self::assertSame('500 Example Rd', $captured['raw']);
        self::assertSame('job-99', $captured['jobId']);
        self::assertSame('verified', $captured['status']);
        self::assertSame([
            ['markRun', 'job-99'],
            ['incrementProcessed', 'job-99'],
        ], $jobProgressWriter->calls);
    }
}
