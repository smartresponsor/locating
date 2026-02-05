<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\Service\Locator;

use Smartresponsor\Entity\Locator\AddressInput;
use Smartresponsor\InfrastructureInterface\Locator\LocatorFixtureReaderInterface;
use Smartresponsor\ServiceInterface\Locator\LocatorDemoSeedInterface;
use Smartresponsor\ServiceInterface\Locator\AddressPipelineInterface;

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
