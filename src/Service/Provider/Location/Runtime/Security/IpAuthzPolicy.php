<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Provider\Location\Runtime\Security;

use App\Locating\ServiceInterface\Provider\Location\Runtime\Security\IpAuthzPolicyInterface;

final class IpAuthzPolicy implements IpAuthzPolicyInterface
{
    /** @var array<string, array<int, array{action:string,cidr:string}>> */
    private array $rule = [];

    public function allow(string $tenantId, string $ip): bool
    {
        $rule = $this->rule[$tenantId] ?? [];
        ksort($rule, SORT_NUMERIC); // lower priority value first
        foreach ($rule as $prio => $r) {
            if ($this->match($ip, $r['cidr'])) {
                return 'allow' === $r['action'];
            }
        }

        return true; // default allow
    }

    public function add(string $tenantId, string $action, string $cidr, int $priority): void
    {
        $t = $this->rule[$tenantId] ?? [];
        $t[$priority] = ['action' => 'deny' === $action ? 'deny' : 'allow', 'cidr' => $cidr];
        $this->rule[$tenantId] = $t;
    }

    private function match(string $ip, string $cidr): bool
    {
        // IPv4/IPv6 CIDR match
        if (!str_contains($cidr, '/')) {
            return $ip === $cidr;
        }

        [$net, $maskValue] = explode('/', $cidr, 2);
        $netw = @inet_pton($net);
        $addr = @inet_pton($ip);
        if (false === $netw || false === $addr) {
            return false;
        }

        $mask = filter_var($maskValue, FILTER_VALIDATE_INT);
        $len = strlen($netw);
        if (false === $mask || $mask < 0 || $mask > $len * 8 || $len !== strlen($addr)) {
            return false;
        }

        $bytes = intdiv($mask, 8);
        $bits = $mask % 8;
        if ($bytes > 0 && substr($netw, 0, $bytes) !== substr($addr, 0, $bytes)) {
            return false;
        }
        if ($bits > 0) {
            $n = ord($netw[$bytes]) >> (8 - $bits);
            $a = ord($addr[$bytes]) >> (8 - $bits);
            if ($n !== $a) {
                return false;
            }
        }

        return true;
    }
}
