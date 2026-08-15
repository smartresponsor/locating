<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Model\Location;

use App\Locating\ModelInterface\Location\AddressIssueInterface;

final class AddressIssue implements AddressIssueInterface
{
    public function __construct(
        private readonly string $field,
        private readonly string $code,
        private readonly string $message,
    ) {
    }

    public static function missing(string $field): self
    {
        return new self($field, 'missing', sprintf('Field "%s" is required.', $field));
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
