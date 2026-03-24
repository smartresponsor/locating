<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace Smartresponsor\Integration\Secrets;
use Smartresponsor\Contract\Secrets\SecretsProviderInterface;
final class EnvSecretsProvider implements SecretsProviderInterface{
  public function get(string $key, ?string $default=null): ?string{
    $v=getenv($key); return $v===false? $default : $v;
  }
}