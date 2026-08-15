<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Observability\Location;

use App\Locating\ReadModel\Observability\Location\ProviderGovernanceExecutionItem;
use App\Locating\ReadModel\Observability\Location\ProviderGovernanceExecutionReport;
use App\Locating\ReadModel\Observability\Location\ProviderGovernanceExecutionStepStatus;
use App\Locating\Service\Observability\Location\LocationProviderGovernanceAcknowledgementService;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceExecutionServiceInterface;
use PHPUnit\Framework\TestCase;

final class LocationProviderGovernanceAcknowledgementServiceTest extends TestCase
{
    public function testBuildsAcknowledgementOutcomeReport(): void
    {
        $execution = new class () implements LocationProviderGovernanceExecutionServiceInterface {
            public function report(): \App\Locating\ReadModelInterface\Observability\Location\ProviderGovernanceExecutionReportInterface
            {
                return new ProviderGovernanceExecutionReport('location', [
                    'alpha' => new ProviderGovernanceExecutionItem(
                        'alpha',
                        'deprioritize-provider',
                        'degraded',
                        'pending-acknowledgement',
                        [new ProviderGovernanceExecutionStepStatus('reduce-traffic-share', 'high', 'routing-policy', 'awaiting-operator-ack', true)],
                    ),
                ]);
            }
        };

        $report = (new LocationProviderGovernanceAcknowledgementService($execution))->acknowledge([
            'acknowledgements' => [
                'alpha' => [
                    'reduce-traffic-share' => ['outcome' => 'approved', 'note' => 'Proceed.'],
                ],
            ],
        ]);

        self::assertSame('acknowledged', $report->acknowledgements()['alpha:reduce-traffic-share']->acknowledgementState());
        self::assertTrue($report->acknowledgements()['alpha:reduce-traffic-share']->accepted());
    }
}
