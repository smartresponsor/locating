<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\Service\Locator;

use Smartresponsor\Entity\Locator\AddressInput;
use Smartresponsor\InfrastructureInterface\Locator\LocatorFixtureReaderInterface;
use Smartresponsor\ServiceInterface\Locator\LocatorDemoLoopInterface;
use Smartresponsor\ServiceInterface\Locator\AddressPipelineInterface;

final class LocatorDemoLoop implements LocatorDemoLoopInterface
{
    public function __construct(
        private LocatorFixtureReaderInterface $fixtureReader,
        private AddressPipelineInterface $addressPipeline,
        private string $defaultFixturePath
    ) {
    }

    public function runLoop(string $tenantId, int $roundMax, int $sleepSecond): void
    {
        if ($roundMax < 1) {
            $roundMax = 1;
        }

        if ($sleepSecond < 0) {
            $sleepSecond = 0;
        }

        $recordList = $this->fixtureReader->readFixture($this->defaultFixturePath);

        if ($recordList === []) {
            return;
        }

        for ($roundIndex = 0; $roundIndex < $roundMax; $roundIndex++) {
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
                        'round' => $roundIndex,
                    ],
                ]);

                try {
                    $this->addressPipeline->process($input);
                } catch (\Throwable) {
                    // Error is still visible in metrics through decorators; loop must continue.
                }
            }

            if ($sleepSecond > 0 && $roundIndex < $roundMax - 1) {
                sleep($sleepSecond);
            }
        }
    }
}
