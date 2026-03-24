<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\Entity\Locator;

use App\Bridge\Legacy\Entity\Location\AddressValidationIssueLegacyInterface;

final class AddressValidationIssue implements AddressValidationIssueLegacyInterface
{
    public function __construct(
        private string $field,
        private string $code,
        private string $message,
    ) {
    }

    public static function missing(string $field): self
    {
        return new self($field, 'missing', sprintf('Field "%s" is required.', $field));
    }

    public static function invalid(string $field, string $reason): self
    {
        return new self($field, 'invalid', $reason);
    }

    public function field(): string
    {
        return $this->field;
    }

    public function code(): string
    {
        return $this->code;
    }

    public function message(): string
    {
        return $this->message;
    }
}
