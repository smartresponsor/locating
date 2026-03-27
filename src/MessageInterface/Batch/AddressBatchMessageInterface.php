<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\MessageInterface\Batch\Location;

interface AddressBatchMessageInterface
{
    public function jobId(): string;

    /**
     * @return array<string,mixed>
     */
    public function payload(): array;
}
