<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Smartresponsor\Service;

/**
 * Exception thrown when a tenant exceeds a configured quota for a batch operation.
 */
final class QuotaExceededException extends \RuntimeException
{
}
