<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\Entity\Locator;

use App\Bridge\Legacy\Entity\Location\AddressInputLegacyInterface;

final class AddressInput implements AddressInputLegacyInterface
{
    public function __construct(
        private string $rawLine,
        private array $data = [],
    ) {
    }

    public static function fromArray(array $payload): self
    {
        $raw = (string) ($payload['raw'] ?? '');
        $data = (array) ($payload['data'] ?? []);

        return new self($raw, $data);
    }

    public function rawLine(): string
    {
        return $this->rawLine;
    }

    public function toArray(): array
    {
        return [
            'raw' => $this->rawLine,
            'data' => $this->data,
        ];
    }

    public function data(): array
    {
        return $this->data;
    }
}
