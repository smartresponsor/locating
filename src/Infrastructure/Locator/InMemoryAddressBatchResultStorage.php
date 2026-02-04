<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\Infrastructure\Locator;

use App\EntityInterface\Locator\AddressResultInterface;
use App\InfrastructureInterface\Locator\AddressBatchResultStorageInterface;

/**
 * In-memory result storage for batch jobs.
 */
final class InMemoryAddressBatchResultStorage implements AddressBatchResultStorageInterface
{
    /**
     * @var array<string,AddressResultInterface[]>
     */
    private array $resultByJobId = [];

    public function appendResult(string $jobId, AddressResultInterface $result): void
    {
        $this->resultByJobId[$jobId][] = $result;
    }

    public function resultList(string $jobId): array
    {
        return $this->resultByJobId[$jobId] ?? [];
    }
}
