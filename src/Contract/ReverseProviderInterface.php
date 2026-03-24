<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace Smartresponsor\Contract;
interface ReverseProviderInterface{ public function name(): string; public function reverse(float $lat, float $lon): array; }
