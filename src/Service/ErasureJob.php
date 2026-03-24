<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Service;
final class ErasureJob {
    public function __construct(
        private RetentionPolicy $policy,
        private PiiAnonymizer $anonymizer
    ) {}
    public function plan(string $kind, array $row, bool $legalHold=false): array {
        $rule = $this->policy->rule($kind);
        $action = $legalHold ? 'retain' : $rule['action'];
        $masked = $row;
        if ($action === 'anonymize') {
            $masked = $this->anonymizer->mask($row, [
                'name'=>'partial','email'=>'hash','phone'=>'partial','street'=>'hash'
            ]);
        } elseif ($action === 'delete') {
            $masked = [];
        }
        return ['action'=>$action, 'ttl_days'=>$rule['ttl_days'], 'result'=>$masked];
    }
}
