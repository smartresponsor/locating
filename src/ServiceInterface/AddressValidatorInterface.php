<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Smartresponsor\ServiceInterface;

use Smartresponsor\Entity\AddressData;
use Smartresponsor\Entity\AddressStatus;
use Smartresponsor\EntityInterface\AddressValidationIssueInterface;

interface AddressValidatorInterface
{
    /**
     * @return array{status: AddressStatus, issues: AddressValidationIssueInterface[]}
     */
    public function validate(AddressData $address): array;
}
