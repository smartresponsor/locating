<?php

declare(strict_types=1);

namespace App\Locating\Integration\Provider\Location\Metrics\Health;

use App\Locating\Integration\Provider\Location\Http\NominatimClient;

final class NominatimHealthCheck implements HealthCheckInterface
{
    public function __construct(private NominatimClient $c)
    {
    }

    public function nameEntity(): string
    {
        return 'nominatim';
    }

    public function check(): array
    {
        $ok = $this->c->ping();

        return ['status' => $ok ? 'UP' : 'DOWN'];
    }
}
