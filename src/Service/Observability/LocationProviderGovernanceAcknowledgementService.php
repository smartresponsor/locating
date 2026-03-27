<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Service\Observability\Location;

use App\Entity\Location\ProviderGovernanceAcknowledgement;
use App\Entity\Location\ProviderGovernanceAcknowledgementReport;
use App\EntityInterface\Location\ProviderGovernanceAcknowledgementReportInterface;
use App\ServiceInterface\Observability\Location\LocationProviderGovernanceAcknowledgementServiceInterface;
use App\ServiceInterface\Observability\Location\LocationProviderGovernanceExecutionServiceInterface;

final class LocationProviderGovernanceAcknowledgementService implements LocationProviderGovernanceAcknowledgementServiceInterface
{
    public function __construct(private readonly LocationProviderGovernanceExecutionServiceInterface $execution)
    {
    }

    public function acknowledge(array $payload): ProviderGovernanceAcknowledgementReportInterface
    {
        $requested = $payload['acknowledgements'] ?? [];
        $items = [];

        foreach ($this->execution->report()->providers() as $sourceKey => $provider) {
            foreach ($provider->steps() as $step) {
                $request = $requested[$sourceKey][$step->code()] ?? [];
                $requestedOutcome = is_array($request) ? (string) ($request['outcome'] ?? 'pending') : 'pending';
                $note = is_array($request) ? (string) ($request['note'] ?? '') : '';
                [$normalizedOutcome, $ackState, $accepted] = $this->normalizeOutcome($requestedOutcome, $step->acknowledgementRequired());
                $items[$sourceKey.':'.$step->code()] = new ProviderGovernanceAcknowledgement(
                    $sourceKey,
                    $step->code(),
                    $requestedOutcome,
                    $normalizedOutcome,
                    $ackState,
                    $accepted,
                    $note,
                );
            }
        }

        return new ProviderGovernanceAcknowledgementReport('location', $items);
    }

    /** @return array{0:string,1:string,2:bool} */
    private function normalizeOutcome(string $requestedOutcome, bool $ackRequired): array
    {
        $normalized = strtolower(trim($requestedOutcome));

        return match ($normalized) {
            'acknowledged', 'accept', 'accepted', 'approve', 'approved' => ['acknowledged', 'acknowledged', true],
            'rejected', 'reject', 'declined', 'defer', 'deferred' => ['rejected', 'rejected', false],
            default => [$ackRequired ? 'pending' : 'not-required', $ackRequired ? 'pending-acknowledgement' : 'not-required', false],
        };
    }
}
