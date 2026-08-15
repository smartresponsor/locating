<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Model\Location;

use App\Locating\ModelInterface\Location\AddressInputInterface;

final class AddressInput implements AddressInputInterface
{
    /**
     * @param array<string, mixed> $data
     */
    public function __construct(
        private readonly string $raw,
        private readonly array $data = [],
    ) {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        return new self(
            (string) ($payload['raw'] ?? ''),
            (array) ($payload['data'] ?? []),
        );
    }

    public function raw(): string
    {
        return $this->raw;
    }

    public function data(): array
    {
        return $this->data;
    }

    public function toArray(): array
    {
        return [
            'raw' => $this->raw,
            'data' => $this->data,
        ];
    }
}
