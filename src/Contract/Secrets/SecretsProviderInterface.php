<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace Smartresponsor\Contract\Secrets;
interface SecretsProviderInterface{ public function get(string $key, ?string $default=null): ?string; }