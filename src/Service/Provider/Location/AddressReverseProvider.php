<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\Locating\Service\Provider\Location;

use App\Locating\ModelInterface\Location\AddressReverseResultInterface;
use App\Locating\RecorderInterface\LocationMetricRecorderInterface;
use App\Locating\ServiceInterface\Address\Location\LocationResultFactoryInterface;
use App\Locating\ServiceInterface\Provider\Location\AddressReverseGatewayInterface;
use App\Locating\ServiceInterface\Provider\Location\AddressReverseProviderInterface;
use App\Locating\ServiceInterface\Provider\Location\AddressReverseSourceInterface;

final class AddressReverseProvider implements AddressReverseProviderInterface, AddressReverseSourceInterface
{
    public function sourceKey(): string
    {
        return 'reverse';
    }

    public function __construct(
        private readonly AddressReverseGatewayInterface $gateway,
        private readonly LocationResultFactoryInterface $resultFactory,
        private readonly ?LocationMetricRecorderInterface $metricRecorder = null,
    ) {
    }

    public function reverse(float $latitude, float $longitude, ?string $countryCode = null): AddressReverseResultInterface
    {
        $start = microtime(true);

        try {
            $payload = $this->gateway->reverse($latitude, $longitude, $countryCode);
            $address = isset($payload['address']) && is_array($payload['address']) ? $payload['address'] : [];
            $string = static fn (mixed $value): string => is_string($value) ? $value : '';

            $streetParts = [];
            $road = $string($address['road'] ?? null);
            if ('' !== $road) {
                $streetParts[] = $road;
            }
            $houseNumber = $string($address['house_number'] ?? null);
            if ('' !== $houseNumber) {
                $streetParts[] = $houseNumber;
            }
            $city = $string($address['city'] ?? null);
            if ('' === $city) {
                $city = $string($address['town'] ?? null);
            }
            if ('' === $city) {
                $city = $string($address['village'] ?? null);
            }

            $dataArray = [
                'street' => trim(implode(' ', $streetParts)),
                'city' => $city,
                'region' => $string($address['state'] ?? null),
                'postalCode' => $string($address['postcode'] ?? null),
                'countryCode' => strtoupper($string($address['country_code'] ?? null)),
            ];

            $filled = count(array_filter($dataArray, static fn ($value): bool => '' !== $value));
            $status = 'partial';
            if ($filled >= 4) {
                $status = 'verified';
            } elseif ($filled <= 1) {
                $status = 'rejected';
            }

            $this->recordMetric('ok', $start);

            return $this->resultFactory->createReverseResultFromArray([
                'status' => $status,
                'address' => $dataArray,
                'issues' => [],
                'geoPoint' => ['latitude' => $latitude, 'longitude' => $longitude],
                'providerKey' => 'nominatim',
            ]);
        } catch (\Throwable $exception) {
            $this->recordMetric('error', $start);
            throw $exception;
        }
    }

    private function recordMetric(string $result, float $start): void
    {
        if (null === $this->metricRecorder) {
            return;
        }

        $durationMs = (microtime(true) - $start) * 1000.0;
        $this->metricRecorder->recordLatency('address_reverse', $durationMs);
        $this->metricRecorder->incrementCounter('address_reverse', $result);
    }
}
