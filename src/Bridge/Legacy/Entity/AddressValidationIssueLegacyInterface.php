<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Entity\Location;

interface AddressValidationIssueLegacyInterface
{
    public function field(): string;

    public function code(): string;

    public function message(): string;
}
