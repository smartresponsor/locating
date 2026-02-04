<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\Service\Locator;

/**
 * Exception thrown when a tenant exceeds a configured quota for a batch operation.
 */
final class QuotaExceededException extends \RuntimeException
{
}
