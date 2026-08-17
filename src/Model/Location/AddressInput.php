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
        $raw = is_string($payload['raw'] ?? null) ? $payload['raw'] : '';
        $data = [];
        if (is_array($payload['data'] ?? null)) {
            foreach ($payload['data'] as $key => $value) {
                if (is_string($key)) {
                    $data[$key] = $value;
                }
            }
        }

        return new self($raw, $data);
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
