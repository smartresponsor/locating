<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\Entity\Locator;

enum AddressStatus: string
{
    case VERIFIED = 'verified';
    case PARTIAL = 'partial';
    case REJECTED = 'rejected';
    case AMBIGUOUS = 'ambiguous';
}
