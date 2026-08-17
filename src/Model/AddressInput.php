<?php

declare(strict_types=1);

namespace App\Locating\Model;

final readonly class AddressInput
{
    /** @param array<string, mixed> $data */
    public function __construct(
        private string $raw,
        private array $data = [],
    ) {
    }

    /** @param array<string, mixed> $payload */
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

    /** @return array<string, mixed> */
    public function data(): array
    {
        return $this->data;
    }
}
