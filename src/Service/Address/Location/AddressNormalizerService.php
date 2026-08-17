<?php

declare(strict_types=1);

namespace App\Locating\Service\Address\Location;

use App\Locating\Model\Location\CanonicalAddress;
use App\Locating\ServiceInterface\Address\Location\AddressNormalizerServiceInterface;

final class AddressNormalizerService implements AddressNormalizerServiceInterface
{
    /**
     * @param array<string, mixed> $raw
     * @return array{address:CanonicalAddress, score:float}
     */
    public function canonicalize(array $raw, string $provider): array
    {
        $string = static fn (mixed $value): string => is_string($value) ? trim($value) : '';
        $a = new CanonicalAddress(
            street: $string($raw['street'] ?? null),
            house: $string($raw['house'] ?? null),
            city: $string($raw['city'] ?? null),
            region: $string($raw['region'] ?? null),
            postalCode: $string($raw['postalCode'] ?? null),
            countryCode: strtoupper($string($raw['countryCode'] ?? null)),
            formatted: $string($raw['formatted'] ?? null),
        );
        $score = 0.0;
        if ($a->street !== '') {
            $score += 0.25;
        }
        if ($a->house !== '') {
            $score += 0.15;
        }
        if ($a->city !== '') {
            $score += 0.2;
        }
        if ($a->region !== '') {
            $score += 0.1;
        }
        if ($a->postalCode !== '') {
            $score += 0.15;
        }
        if ($a->countryCode !== '') {
            $score += 0.15;
        }
        if ($provider === 'mock') {
            $score *= 0.7;
        }
        $score = min(1.0, max(0.0, $score));
        return ['address' => $a, 'score' => $score];
    }
}
