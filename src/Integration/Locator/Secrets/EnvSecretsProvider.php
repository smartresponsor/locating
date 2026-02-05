<?php
declare(strict_types=1);
namespace Smartresponsor\Integration\Locator\Secrets;
use Smartresponsor\Contract\Locator\Secrets\SecretsProviderInterface;
final class EnvSecretsProvider implements SecretsProviderInterface{
  public function get(string $key, ?string $default=null): ?string{
    $v=getenv($key); return $v===false? $default : $v;
  }
}