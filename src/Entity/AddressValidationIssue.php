<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace App\Entity;

use App\EntityInterface\AddressValidationIssueInterface;

final class AddressValidationIssue implements AddressValidationIssueInterface
{
    public function __construct(
        private string $field,
        private string $code,
        private string $message
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
