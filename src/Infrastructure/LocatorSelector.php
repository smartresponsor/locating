<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace App\Infrastructure;
use App\ServiceInterface\LocatorInterface; use App\Strategy\OpenStreetMapLocator; use App\Strategy\GoogleLocator; use App\Strategy\USPSLocator; use App\Integration\Http\NominatimClient; use App\Integration\Http\GoogleGeocodingClient; use App\Integration\Http\USPSClient; use App\Integration\Decorator\RateLimitedLocator; use App\Integration\Cache\SimpleArrayCache; use App\Integration\Decorator\CachedLocator;
final class LocatorSelector{ public function __construct(private readonly LocatorConfig $cfg){}
public function getActiveStrategy(): LocatorInterface{ $base = match(strtolower($this->cfg->strategy)){ 'google' => new GoogleLocator(new GoogleGeocodingClient($this->cfg->googleApiKey)), 'usps' => new USPSLocator(new USPSClient(userId:getenv('USPS_USERID')?:'')), default => new OpenStreetMapLocator(new NominatimClient($this->cfg->nominatimBaseUrl, $this->cfg->nominatimEmail)),}; $wrapped=new RateLimitedLocator($base,120); $wrapped=new CachedLocator($wrapped, new SimpleArrayCache(), 600); return $wrapped; } }
