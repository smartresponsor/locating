<?php

declare(strict_types=1);

namespace App\Locating\Integration\Observability\Trace;

final class OtelZipkinExporter
{
    public function __construct(private string $endpoint)
    {
    }
    /** @param list<Span> $spans */
    public function export(string $service, array $spans): void
    {
        $payload = [];
        foreach ($spans as $s) {
            $tsUs = (int)round($s->start * 1_000_000);
            $durUs = (int)round(($s->end - $s->start) * 1_000_000);
            $tags = [];
            foreach ($s->attrs as $key => $value) {
                $tags[$key] = is_scalar($value) || null === $value
                    ? (string) $value
                    : json_encode($value, JSON_THROW_ON_ERROR);
            }
            $payload[] = [
              'traceId' => $s->traceId,
              'id' => $s->spanId,
              'parentId' => $s->parentId,
              'nameEntity' => $s->nameEntity,
              'timestamp' => $tsUs,
              'duration' => $durUs > 0 ? $durUs : 1,
              'localEndpoint' => ['serviceName' => $service],
              'tags' => $tags,
            ];
        }
        $ch = curl_init($this->endpoint);
        $encoded = json_encode($payload, JSON_THROW_ON_ERROR);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 3,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => $encoded,
        ]);
        curl_exec($ch);
        curl_close($ch);
    }
}
