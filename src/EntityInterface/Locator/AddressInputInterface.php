<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\EntityInterface\Locator;

interface AddressInputInterface
{
    public function rawLine(): string;

    public function toArray(): array;
}
