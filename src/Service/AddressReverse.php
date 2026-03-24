<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace App\Service;

use App\Entity\AddressData;
use App\Entity\AddressResult;
use App\Entity\AddressStatus;
use App\Entity\GeoPoint;
use App\InfrastructureInterface\MetricRecorderInterface;
use App\InfrastructureInterface\ReverseHttpClientInterface;
use App\ServiceInterface\AddressReverseInterface;

/**
 * Default implementation of AddressReverseInterface backed by a single
 * HTTP reverse geocoding client.
 *
 * For now we rely on Nominatim-compatible payloads but keep the mapping
 * defensive so that providers can be swapped later.
 */
final class AddressReverse implements AddressReverseInterface
{
    public function __construct(
        private ReverseHttpClientInterface $client,
        private ?MetricRecorderInterface $metricRecorder = null
    ) {
    }

    public function reverse(float $latitude, float $longitude, ?string $countryCode = null): AddressResult
    {
        $start = microtime(true);

        try {
            $payload = $this->client->reverse($latitude, $longitude, $countryCode);

            $address = [];
            if (isset($payload['address']) && is_array($payload['address'])) {
                $address = $payload['address'];
            }

            $streetParts = [];
            if (!empty($address['road'])) {
                $streetParts[] = (string)$address['road'];
            }
            if (!empty($address['house_number'])) {
                $streetParts[] = (string)$address['house_number'];
            }

            $street = trim(implode(' ', $streetParts));
            $city = (string)($address['city'] ?? $address['town'] ?? $address['village'] ?? '');
            $region = (string)($address['state'] ?? '');
            $postalCode = (string)($address['postcode'] ?? '');
            $country = (string)($address['country'] ?? '');
            $countryCodeValue = (string)($address['country_code'] ?? '');
            if ($countryCodeValue !== '') {
                $countryCodeValue = strtoupper($countryCodeValue);
            }

            $dataArray = [
                'street' => $street,
                'city' => $city,
                'region' => $region,
                'postalCode' => $postalCode,
                'country' => $country,
                'countryCode' => $countryCodeValue,
            ];

            $addressData = AddressData::fromArray($dataArray);

            // Simple confidence classification based on how many components we have.
            $filled = 0;
            foreach (['street', 'city', 'region', 'postalCode', 'countryCode'] as $key) {
                if ($dataArray[$key] !== '') {
                    $filled++;
                }
            }

            $status = AddressStatus::PARTIAL;
            if ($filled >= 4) {
                $status = AddressStatus::VERIFIED;
            } elseif ($filled <= 1) {
                $status = AddressStatus::REJECTED;
            }

            $geoPoint = new GeoPoint($latitude, $longitude);

            $providerKey = 'nominatim';
            if (isset($payload['licence']) && is_string($payload['licence'])) {
                $providerKey = 'nominatim';
            }

            $result = AddressResult::create(
                $status,
                $addressData,
                [],
                $geoPoint,
                $providerKey
            );

            $this->recordMetric('ok', $start);

            return $result;
        } catch (\Throwable $exception) {
            $this->recordMetric('error', $start);

            throw $exception;
        }
    }

    private function recordMetric(string $result, float $start): void
    {
        if ($this->metricRecorder === null) {
            return;
        }

        $durationMs = (microtime(true) - $start) * 1000.0;

        $this->metricRecorder->recordLatency('address_reverse', $durationMs);
        $this->metricRecorder->incrementCounter('address_reverse', $result);
    }
}
