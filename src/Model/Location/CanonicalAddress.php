<?php

declare(strict_types=1);

namespace App\Locating\Model\Location;

final class CanonicalAddress
{
    public function __construct(public string $street = '', public string $house = '', public string $city = '', public string $region = '', public string $postalCode = '', public string $countryCode = '', public string $formatted = '')
    {
    }
    public function toArray(): array
    {
        return ['street' => $this->street,'house' => $this->house,'city' => $this->city,'region' => $this->region,'postalCode' => $this->postalCode,'countryCode' => $this->countryCode,'formatted' => $this->formatted];
    }
}
