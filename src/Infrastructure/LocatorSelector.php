<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace Smartresponsor\Infrastructure;
use Smartresponsor\ServiceInterface\LocatorInterface; use Smartresponsor\Strategy\OpenStreetMapLocator; use Smartresponsor\Strategy\GoogleLocator; use Smartresponsor\Strategy\USPSLocator; use Smartresponsor\Integration\Http\NominatimClient; use Smartresponsor\Integration\Http\GoogleGeocodingClient; use Smartresponsor\Integration\Http\USPSClient; use Smartresponsor\Integration\Decorator\RateLimitedLocator; use Smartresponsor\Integration\Cache\SimpleArrayCache; use Smartresponsor\Integration\Decorator\CachedLocator;
final class LocatorSelector{ public function __construct(private readonly LocatorConfig $cfg){}
public function getActiveStrategy(): LocatorInterface{ $base = match(strtolower($this->cfg->strategy)){ 'google' => new GoogleLocator(new GoogleGeocodingClient($this->cfg->googleApiKey)), 'usps' => new USPSLocator(new USPSClient(userId:getenv('USPS_USERID')?:'')), default => new OpenStreetMapLocator(new NominatimClient($this->cfg->nominatimBaseUrl, $this->cfg->nominatimEmail)),}; $wrapped=new RateLimitedLocator($base,120); $wrapped=new CachedLocator($wrapped, new SimpleArrayCache(), 600); return $wrapped; } }
