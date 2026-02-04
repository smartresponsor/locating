<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\Message\Locator;

final class AddressBatchMessage
{
    /**
     * @param array<string,mixed> $payload
     */
    public function __construct(
        private string $jobId,
        private array $payload
    ) {
    }

    public function jobId(): string
    {
        return $this->jobId;
    }

    /**
     * @return array<string,mixed>
     */
    public function payload(): array
    {
        return $this->payload;
    }
}
