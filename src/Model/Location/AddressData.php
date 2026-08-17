<?php

declare(strict_types=1);

namespace App\Locating\Model\Location;

final class AddressData
{
    public function __construct(public string $street, public string $city, public string $region, public string $postalCode, public string $countryCode)
    {
    }
    /** @return array{street:string,city:string,region:string,postalCode:string,countryCode:string} */
    public function toArray(): array
    {
        return ['street' => $this->street,'city' => $this->city,'region' => $this->region,'postalCode' => $this->postalCode,'countryCode' => $this->countryCode];
    }

    /** @param array<string, mixed> $a */
    public static function fromArray(array $a): self
    {
        $string = static fn (mixed $value): string => is_string($value) ? $value : '';

        return new self(
            $string($a['street'] ?? null),
            $string($a['city'] ?? null),
            $string($a['region'] ?? null),
            $string($a['postalCode'] ?? null),
            $string($a['countryCode'] ?? null),
        );
    }
    public function oneLine(): string
    {
        return trim($this->street.', '.$this->city.', '.$this->region.' '.$this->postalCode.', '.$this->countryCode);
    }
}
