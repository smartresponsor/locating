<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\Locating\MessageHandler\Batch\Location;

use App\Locating\MessageHandlerInterface\Batch\Location\AddressBatchMessageHandlerInterface;
use App\Locating\MessageInterface\Batch\Location\AddressBatchMessageInterface;
use App\Locating\Model\Location\AddressInput;
use App\Locating\ServiceInterface\Address\Location\AddressPipelineInterface;
use App\Locating\ServiceInterface\Batch\Location\AddressBatchJobProgressWriterInterface;
use App\Locating\ServiceInterface\Batch\Location\AddressBatchResultWriterInterface;

final class AddressBatchMessageHandler implements AddressBatchMessageHandlerInterface
{
    public function __construct(
        private readonly AddressPipelineInterface $pipeline,
        private readonly AddressBatchJobProgressWriterInterface $jobProgressWriter,
        private readonly AddressBatchResultWriterInterface $resultWriter,
    ) {
    }

    public function __invoke(AddressBatchMessageInterface $message): void
    {
        if (!$this->jobProgressWriter->markRun($message->jobId())) {
            return;
        }

        $input = AddressInput::fromArray($message->payload());
        $result = $this->pipeline->process($input);

        $this->resultWriter->appendPipelineResult($message->jobId(), $result);
        $this->jobProgressWriter->incrementProcessed($message->jobId());
    }
}
