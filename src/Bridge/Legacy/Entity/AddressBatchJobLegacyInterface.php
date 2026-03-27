<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Entity\Location;

use Smartresponsor\Entity\Locator\AddressBatchJobStatus;

interface AddressBatchJobLegacyInterface
{
    public function jobId(): string;

    public function tenantId(): string;

    public function jobStatus(): AddressBatchJobStatus;

    public function totalCount(): int;

    public function processedCount(): int;

    public function createdAt(): \DateTimeImmutable;

    public function updatedAt(): \DateTimeImmutable;
}
