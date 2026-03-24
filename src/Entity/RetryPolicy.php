<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace App\Entity;

use App\Service\RetryPolicy as ServiceRetryPolicy;

/**
 * @deprecated Use App\Service\RetryPolicy directly.
 */
final class RetryPolicy extends ServiceRetryPolicy
{
}
