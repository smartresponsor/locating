<?php

declare(strict_types=1);

namespace App\Locating;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

/**
 * Boots Locating as a standalone Symfony application for verification and debugging.
 */
final class Kernel extends BaseKernel
{
    use MicroKernelTrait;
}
