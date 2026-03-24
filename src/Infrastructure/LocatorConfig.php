<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace Smartresponsor\Infrastructure;
final class LocatorConfig{ public function __construct(public readonly string $strategy='osm', public readonly ?string $googleApiKey=null, public readonly string $nominatimBaseUrl='https://nominatim.openstreetmap.org', public readonly ?string $nominatimEmail=null){}}
