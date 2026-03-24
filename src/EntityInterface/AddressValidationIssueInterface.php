<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Smartresponsor\EntityInterface;

interface AddressValidationIssueInterface
{
    public function field(): string;

    public function code(): string;

    public function message(): string;
}
