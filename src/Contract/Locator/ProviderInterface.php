<?php
declare(strict_types=1);
namespace Smartresponsor\Contract\Locator;
interface ProviderInterface{ public function name(): string; public function geocode(string $q): array; }
