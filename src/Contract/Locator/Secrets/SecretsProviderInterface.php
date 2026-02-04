<?php
declare(strict_types=1);
namespace SmartResponsor\Contract\Locator\Secrets;
interface SecretsProviderInterface{ public function get(string $key, ?string $default=null): ?string; }