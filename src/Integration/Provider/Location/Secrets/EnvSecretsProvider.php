<?php

declare(strict_types=1);

namespace App\Locating\Integration\Provider\Location\Secrets;

final class EnvSecretsProvider implements SecretsProviderInterface
{
    public function get(string $key, ?string $default = null): ?string
    {
        $v = getenv($key);

        return false === $v ? $default : $v;
    }
}
