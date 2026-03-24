<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\MessageHandler\Batch\Location;

use App\Entity\Location\AddressInput;
use App\InfrastructureInterface\Batch\Location\AddressBatchJobProgressWriterInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchResultWriterInterface;
use App\MessageHandlerInterface\Batch\Location\AddressBatchMessageHandlerInterface;
use App\MessageInterface\Batch\Location\AddressBatchMessageInterface;
use App\ServiceInterface\Address\Location\AddressPipelineInterface;

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
