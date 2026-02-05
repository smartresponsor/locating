<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\Infrastructure\Locator;

use Smartresponsor\EntityInterface\Locator\AddressBatchJobInterface;
use Smartresponsor\InfrastructureInterface\Locator\AddressBatchJobRepositoryInterface;

/**
 * Simple in-memory repository suitable for development and tests.
 * For production use a database-backed implementation.
 */
final class InMemoryAddressBatchJobRepository implements AddressBatchJobRepositoryInterface
{
    /**
     * @var array<string,AddressBatchJobInterface>
     */
    private array $jobById = [];

    public function save(AddressBatchJobInterface $job): void
    {
        $this->jobById[$job->jobId()] = $job;
    }

    public function find(string $jobId): ?AddressBatchJobInterface
    {
        return $this->jobById[$jobId] ?? null;
    }
}
