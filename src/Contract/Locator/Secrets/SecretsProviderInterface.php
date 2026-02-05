<?php
declare(strict_types=1);
namespace Smartresponsor\Contract\Locator\Secrets;
interface SecretsProviderInterface{ public function get(string $key, ?string $default=null): ?string; }