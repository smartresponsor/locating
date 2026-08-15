<?php

declare(strict_types=1);

namespace App\Locating\Tests\Locator\Address;

use App\Locating\Model\Location\AddressData;
use App\Locating\Model\Location\AddressResult;
use App\Locating\Model\Location\AddressStatus;
use App\Locating\Model\Location\AddressValidationIssue;
use App\Locating\Model\Location\GeoPoint;
use PHPUnit\Framework\TestCase;

final class AddressContractTest extends TestCase
{
    public function testContractSamplesAreConsistent(): void
    {
        $fixturePath = dirname(__DIR__, 2) . '/fixtures/address/address-contract-samples.ndjson';
        $this->assertFileExists($fixturePath, 'Fixture file must exist');

        $handle = fopen($fixturePath, 'rb');
        $this->assertIsResource($handle);

        while (($line = fgets($handle)) !== false) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            $record = json_decode($line, true, 512, JSON_THROW_ON_ERROR);

            $statusValue = $record['status'] ?? null;
            $this->assertIsString($statusValue, 'Status must be a string');

            $status = match ($statusValue) {
                'verified' => AddressStatus::VERIFIED,
                'partial' => AddressStatus::PARTIAL,
                'rejected' => AddressStatus::REJECTED,
                'ambiguous' => AddressStatus::AMBIGUOUS,
                default => throw new \RuntimeException('Unsupported status: ' . $statusValue),
            };

            $addressDataArray = $record['address'] ?? null;
            $this->assertIsArray($addressDataArray, 'Address must be an array');

            $addressData = new AddressData(
                $addressDataArray['street'] ?? '',
                $addressDataArray['city'] ?? '',
                $addressDataArray['region'] ?? '',
                $addressDataArray['postalCode'] ?? '',
                $addressDataArray['countryCode'] ?? ''
            );

            $issues = [];
            foreach ($record['issues'] ?? [] as $issueArray) {
                $issues[] = new AddressValidationIssue(
                    $issueArray['field'] ?? '',
                    $issueArray['code'] ?? '',
                    $issueArray['message'] ?? ''
                );
            }

            $geoPoint = null;
            if (array_key_exists('geoPoint', $record) && $record['geoPoint'] !== null) {
                $geo = $record['geoPoint'];
                $geoPoint = new GeoPoint(
                    (float) ($geo['latitude'] ?? 0.0),
                    (float) ($geo['longitude'] ?? 0.0)
                );
            }

            $providerKey = $record['providerKey'] ?? null;
            if ($providerKey !== null) {
                $this->assertIsString($providerKey);
            }

            $result = AddressResult::create(
                $status,
                $addressData,
                $issues,
                $geoPoint,
                $providerKey
            );

            $this->assertSame($statusValue, $result->status(), 'Status must match');
            $this->assertNotNull($result->address(), 'Address must not be null');
            $this->assertSame($addressDataArray, $result->address()->toArray(), 'Address array must be equal');

            $this->assertCount(count($issues), $result->issues(), 'Issues count must match');

            foreach ($result->issues() as $index => $issue) {
                $expected = $issues[$index];
                $this->assertSame($expected->field(), $issue['field'], 'Issue field must match');
                $this->assertSame($expected->code(), $issue['code'], 'Issue code must match');
                $this->assertSame($expected->message(), $issue['message'], 'Issue message must match');
            }

            if ($geoPoint === null) {
                $this->assertNull($result->geoPoint(), 'GeoPoint must be null when not provided');
            } else {
                $this->assertNotNull($result->geoPoint(), 'GeoPoint must not be null');
                $this->assertSame($geoPoint->latitude, $result->geoPoint()['latitude']);
                $this->assertSame($geoPoint->longitude, $result->geoPoint()['longitude']);
            }

            $array = $result->toArray();
            $this->assertArrayHasKey('status', $array);
            $this->assertArrayHasKey('address', $array);
            $this->assertArrayHasKey('issues', $array);
            $this->assertArrayHasKey('geoPoint', $array);
            $this->assertArrayHasKey('providerKey', $array);

            $this->assertSame($statusValue, $array['status']);
            $this->assertSame($addressDataArray, $array['address']);

            $this->assertSame(
                $record['issues'],
                array_map(
                    static function (array $issue): array {
                        return [
                            'field' => $issue['field'],
                            'code' => $issue['code'],
                            'message' => $issue['message'],
                        ];
                    },
                    $array['issues']
                )
            );

            if ($record['geoPoint'] === null) {
                $this->assertNull($array['geoPoint']);
            } else {
                $this->assertIsArray($array['geoPoint']);
                $this->assertSame($record['geoPoint']['latitude'], $array['geoPoint']['latitude']);
                $this->assertSame($record['geoPoint']['longitude'], $array['geoPoint']['longitude']);
            }

            $this->assertSame($providerKey, $array['providerKey']);
        }

        fclose($handle);
    }
}
