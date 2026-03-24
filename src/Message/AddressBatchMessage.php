<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Smartresponsor\Message;

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
