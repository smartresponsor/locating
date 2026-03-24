<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Smartresponsor\MessageHandler;

use Smartresponsor\Entity\AddressInput;
use Smartresponsor\EntityInterface\AddressBatchJobInterface;
use Smartresponsor\InfrastructureInterface\AddressBatchJobRepositoryInterface;
use Smartresponsor\InfrastructureInterface\AddressBatchResultStorageInterface;
use Smartresponsor\Message\AddressBatchMessage;
use Smartresponsor\ServiceInterface\AddressPipelineInterface;

/**
 * Handles batch items by delegating to AddressPipelineInterface.
 */
final class AddressBatchMessageHandler
{
    public function __construct(
        private AddressPipelineInterface $pipeline,
        private AddressBatchJobRepositoryInterface $jobRepository,
        private AddressBatchResultStorageInterface $resultStorage
    ) {
    }

    public function __invoke(AddressBatchMessage $message): void
    {
        $job = $this->jobRepository->find($message->jobId());
        if ($job === null) {
            return;
        }

        if ($job instanceof AddressBatchJobInterface) {
            $job->markRun();
            $this->jobRepository->save($job);
        }

        $payload = $message->payload();
        $input = AddressInput::fromArray($payload);

        $result = $this->pipeline->process($input);

        $this->resultStorage->appendResult($message->jobId(), $result);

        if ($job instanceof AddressBatchJobInterface) {
            $job->incrementProcessed();
            $this->jobRepository->save($job);
        }
    }
}
