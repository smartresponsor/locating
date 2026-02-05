<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service\Locator;
interface RetentionInterface { public function policy(): array; }
class RetentionPolicy implements RetentionInterface {
    public function policy(): array {
        return [
            'address' => ['ttl_days'=>365, 'action'=>'anonymize'],
            'audit'   => ['ttl_days'=>1825, 'action'=>'retain'],
        ];
    }
    public function rule(string $kind): array {
        $p = $this->policy();
        return $p[$kind] ?? ['ttl_days'=>365, 'action'=>'anonymize'];
    }
}
