<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\Locating\Model\Location;

use App\Locating\ModelInterface\Location\AddressIssueInterface;

final class AddressValidationIssue implements AddressIssueInterface
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

    public function toArray(): array
    {
        return [
            'field' => $this->field,
            'code' => $this->code,
            'message' => $this->message,
        ];
    }
}
