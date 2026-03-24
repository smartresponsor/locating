<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace App\ControllerInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

interface AddressSuggestControllerInterface
{
    public function __invoke(Request $request): JsonResponse;
}
