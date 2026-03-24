<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace App\ServiceInterface;

use App\Entity\AddressData;
use App\Entity\AddressStatus;
use App\EntityInterface\AddressValidationIssueInterface;

interface AddressValidatorInterface
{
    /**
     * @return array{status: AddressStatus, issues: AddressValidationIssueInterface[]}
     */
    public function validate(AddressData $address): array;
}
