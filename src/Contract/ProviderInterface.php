<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace Smartresponsor\Contract;
interface ProviderInterface{ public function name(): string; public function geocode(string $q): array; }
