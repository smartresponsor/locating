<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\ServiceInterface\Locator;

use App\Entity\Locator\AddressData;
use App\Entity\Locator\AddressStatus;
use App\EntityInterface\Locator\AddressValidationIssueInterface;

interface AddressValidatorInterface
{
    /**
     * @return array{status: AddressStatus, issues: AddressValidationIssueInterface[]}
     */
    public function validate(AddressData $address): array;
}
