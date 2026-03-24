<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Service;
use Fiber;
final class RaceRunner implements RaceRunnerInterface {
    public function run(array $candidate, int $softTimeoutMs=800): array {
        $fiberMap = [];
        $result = null;
        foreach ($candidate as $id => $fn) {
            if (!\is_callable($fn)) { continue; }
            $fiber = new Fiber(function() use($fn) {
                return $fn();
            });
            $fiberMap[(string)$id] = $fiber;
        }
        $start = (int)\floor(microtime(true)*1000);
        // Start all fibers
        foreach ($fiberMap as $fiber) { if (!$fiber->isStarted()) { $fiber->start(); } }
        while (true) {
            foreach ($fiberMap as $id => $fiber) {
                if ($fiber->isTerminated()) {
                    try {
                        $val = $fiber->getReturn();
                        $result = ['id'=>$id,'value'=>$val];
                        break 2;
                    } catch (\Throwable $e) {
                        // ignore failed candidate
                    }
                } else {
                    // allow fiber to progress cooperatively
                    try { $fiber->resume(); } catch (\Throwable $e) { /* ignore */ }
                }
            }
            $now = (int)\floor(microtime(true)*1000);
            if ($now - $start >= $softTimeoutMs) { break; }
        }
        if ($result === null) { throw new \RuntimeException('RaceRunner timeout or all candidates failed'); }
        // Cancel others (by ignoring; fibers will end on GC)
        return $result;
    }
}
