<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Observability\Location;

use App\Locating\ReadModel\Observability\Location\ProviderGovernanceAcknowledgement;
use App\Locating\ReadModel\Observability\Location\ProviderGovernanceAcknowledgementReport;
use App\Locating\ReadModelInterface\Observability\Location\ProviderGovernanceAcknowledgementReportInterface;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceAcknowledgementServiceInterface;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceExecutionServiceInterface;

final class LocationProviderGovernanceAcknowledgementService implements LocationProviderGovernanceAcknowledgementServiceInterface
{
    public function __construct(private readonly LocationProviderGovernanceExecutionServiceInterface $execution)
    {
    }

    /** @param array<string, mixed> $payload */
    public function acknowledge(array $payload): ProviderGovernanceAcknowledgementReportInterface
    {
        $requested = $this->normalizeAcknowledgements($payload['acknowledgements'] ?? null);
        $items = [];

        foreach ($this->execution->report()->providers() as $sourceKey => $provider) {
            foreach ($provider->steps() as $step) {
                $request = $requested[$sourceKey][$step->code()] ?? [];
                $requestedOutcome = is_string($request['outcome'] ?? null) ? $request['outcome'] : 'pending';
                $note = is_string($request['note'] ?? null) ? $request['note'] : '';
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

    /**
     * @return array<string, array<string, array<string, mixed>>>
     */
    private function normalizeAcknowledgements(mixed $value): array
    {
        if (!is_array($value)) {
            return [];
        }

        $normalized = [];
        foreach ($value as $sourceKey => $steps) {
            if (!is_string($sourceKey) || !is_array($steps)) {
                continue;
            }
            foreach ($steps as $stepCode => $request) {
                if (!is_string($stepCode) || !is_array($request)) {
                    continue;
                }
                $normalizedRequest = [];
                foreach ($request as $key => $entry) {
                    if (is_string($key)) {
                        $normalizedRequest[$key] = $entry;
                    }
                }
                $normalized[$sourceKey][$stepCode] = $normalizedRequest;
            }
        }

        return $normalized;
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
