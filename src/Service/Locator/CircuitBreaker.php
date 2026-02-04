<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Service\Locator;
final class CircuitBreaker implements CircuitBreakerInterface {
    /** @var array<string, array{state:string,fail:int,ok:int,opened:int}> */
    private array $st = [];
    public function __construct(private int $trip=5, private int $halfAfterS=10, private int $resetOk=3) {}
    public function allow(string $key): bool {
        $row = $this->st[$key] ?? ['state'=>'closed','fail'=>0,'ok'=>0,'opened'=>0];
        if ($row['state'] === 'open') {
            if (time() - $row['opened'] >= $this->halfAfterS) {
                $row['state'] = 'half'; $row['fail']=0; $row['ok']=0; $this->st[$key]=$row; return true;
            }
            return false;
        }
        return true;
    }
    public function onResult(string $key, bool $ok): string {
        $row = $this->st[$key] ?? ['state'=>'closed','fail'=>0,'ok'=>0,'opened'=>0];
        if ($ok) {
            $row['ok'] += 1;
            if ($row['state'] === 'half' && $row['ok'] >= $this->resetOk) { $row=['state'=>'closed','fail'=>0,'ok'=>0,'opened'=>0]; }
        } else {
            $row['fail'] += 1;
            if ($row['state']!=='open' && $row['fail'] >= $this->trip) { $row['state']='open'; $row['opened']=time(); }
        }
        $this->st[$key]=$row; return $row['state'];
    }
}
