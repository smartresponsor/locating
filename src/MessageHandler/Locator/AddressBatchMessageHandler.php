<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\MessageHandler\Locator;

use Smartresponsor\Entity\Locator\AddressInput;
use Smartresponsor\EntityInterface\Locator\AddressBatchJobInterface;
use Smartresponsor\InfrastructureInterface\Locator\AddressBatchJobRepositoryInterface;
use Smartresponsor\InfrastructureInterface\Locator\AddressBatchResultStorageInterface;
use Smartresponsor\Message\Locator\AddressBatchMessage;
use Smartresponsor\ServiceInterface\Locator\AddressPipelineInterface;

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
