<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\ControllerInterface\Http\Location;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

interface GovernanceRemediationPlanControllerInterface
{
    public function __invoke(Request $request): JsonResponse;
}
