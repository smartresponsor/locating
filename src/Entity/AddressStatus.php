<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Smartresponsor\Entity;

enum AddressStatus: string
{
    case VERIFIED = 'verified';
    case PARTIAL = 'partial';
    case REJECTED = 'rejected';
    case AMBIGUOUS = 'ambiguous';
}
