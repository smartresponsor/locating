<?php

declare(strict_types=1);

namespace App\Locating\Integration\Provider\Location\Http;

use App\Locating\Integration\Http\SimpleHttp;

final class MapboxClient
{
    public function __construct(private string $key, private string $base = 'https://api.mapbox.com', private int $timeout = 10)
    {
    }
    public function geocode(string $q): array
    {
        $u = $this->base.'/geocoding/v5/mapbox.places/'.rawurlencode($q).'.json?'.http_build_query(['access_token' => $this->key,'limit' => '1','types' => 'address,place,postcode,address']);
        return SimpleHttp::get($u, $this->timeout);
    }
    public function reverse(float $lat, float $lon): array
    {
        $u = $this->base.'/geocoding/v5/mapbox.places/'.rawurlencode((string)$lon.','.(string)$lat).'.json?'.http_build_query(['access_token' => $this->key,'limit' => '1','types' => 'address,place,postcode,address']);
        return SimpleHttp::get($u, $this->timeout);
    }
}
