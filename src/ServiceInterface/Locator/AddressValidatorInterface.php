<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\ServiceInterface\Locator;

use Smartresponsor\Entity\Locator\AddressData;
use Smartresponsor\Entity\Locator\AddressStatus;
use Smartresponsor\EntityInterface\Locator\AddressValidationIssueInterface;

interface AddressValidatorInterface
{
    /**
     * @return array{status: AddressStatus, issues: AddressValidationIssueInterface[]}
     */
    public function validate(AddressData $address): array;
}
