<?php
declare(strict_types=1);
namespace Smartresponsor\Contract\Locator;
interface ReverseProviderInterface{ public function name(): string; public function reverse(float $lat, float $lon): array; }
