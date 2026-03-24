<?php

declare(strict_types=1);

namespace Tests\Service\Observability\Location;

use App\Entity\Location\ProviderGovernanceExecutionItem;
use App\Entity\Location\ProviderGovernanceExecutionReport;
use App\Entity\Location\ProviderGovernanceExecutionStepStatus;
use App\Service\Observability\Location\ProviderGovernanceAcknowledgementService;
use App\ServiceInterface\Observability\Location\ProviderGovernanceExecutionServiceInterface;
use PHPUnit\Framework\TestCase;

final class ProviderGovernanceAcknowledgementServiceTest extends TestCase
{
    public function testBuildsAcknowledgementOutcomeReport(): void
    {
        $execution = new class implements ProviderGovernanceExecutionServiceInterface {
            public function report(): \App\EntityInterface\Location\ProviderGovernanceExecutionReportInterface
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

        $report = (new ProviderGovernanceAcknowledgementService($execution))->acknowledge([
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
