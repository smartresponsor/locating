<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace App\Service;

use App\Entity\AddressInput;
use App\InfrastructureInterface\LocatorFixtureReaderInterface;
use App\ServiceInterface\LocatorDemoSeedInterface;
use App\ServiceInterface\AddressPipelineInterface;

final class LocatorDemoSeed implements LocatorDemoSeedInterface
{
    private string $defaultFixturePath;

    public function __construct(
        private LocatorFixtureReaderInterface $fixtureReader,
        private AddressPipelineInterface $addressPipeline,
        string $defaultFixturePath
    ) {
        $this->defaultFixturePath = $defaultFixturePath;
    }

    public function seedFromFile(string $tenantId, string $filePath): int
    {
        $recordList = $this->fixtureReader->readFixture($filePath);

        $count = 0;

        foreach ($recordList as $record) {
            $raw = (string)($record['raw'] ?? '');
            $data = $record['data'] ?? [];
            if (!is_array($data)) {
                $data = [];
            }

            $input = AddressInput::fromArray([
                'raw' => $raw,
                'data' => $data,
                'meta' => [
                    'tenantId' => $tenantId,
                    'fixtureId' => $record['id'] ?? null,
                    'tagList' => $record['tags'] ?? [],
                ],
            ]);

            $this->addressPipeline->process($input);
            $count++;
        }

        return $count;
    }

    public function seedDemo(string $tenantId): int
    {
        return $this->seedFromFile($tenantId, $this->defaultFixturePath);
    }
}
