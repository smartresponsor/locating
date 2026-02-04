<?php
declare(strict_types=1);
namespace App\Entity\Locator;
final class GeoPoint{ public function __construct(public float $latitude, public float $longitude){} }
