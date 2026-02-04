<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\EntityInterface\Locator;

interface AddressValidationIssueInterface
{
    public function field(): string;

    public function code(): string;

    public function message(): string;
}
