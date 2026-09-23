<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\RecorderInterface;

interface LocationMetricRecorderInterface
{
    public function recordLatency(string $operation, float $milliseconds): void;

    public function incrementCounter(string $operation, string $result): void;
}
