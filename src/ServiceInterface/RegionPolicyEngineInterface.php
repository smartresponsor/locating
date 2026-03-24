<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain;
interface RegionPolicyEngineInterface {
    /** Decide region route given hint (country, lat/lon) and policy table. */
    public function decide(array $hint): string;
    /** Configure policy rule priority (explicit before default). */
    public function set(array $rule): void; // rule: ['when'=>['country'=>'US','bbox'=>[lat1,lon1,lat2,lon2]], 'region'=>'us', 'mode'=>'allow']
}
