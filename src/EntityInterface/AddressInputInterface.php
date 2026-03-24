<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Smartresponsor\EntityInterface;

interface AddressInputInterface
{
    public function rawLine(): string;

    public function toArray(): array;
}
