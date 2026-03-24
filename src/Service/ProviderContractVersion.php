<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Service;
final class ProviderContractVersion implements ProviderContractVersionInterface {
    /** @var array<string, array<string, array<int,string>>> provider=>op=>[version...] sorted asc */
    private array $map = [];
    public function register(string $providerId, string $op, string $version): void {
        $p = (string)$providerId; $o = (string)$op; $v = (string)$version;
        $this->map[$p][$o] = $this->map[$p][$o] ?? [];
        if (!in_array($v, $this->map[$p][$o], true)) {
            $this->map[$p][$o][] = $v;
            sort($this->map[$p][$o], \SORT_NATURAL);
        }
    }
    public function select(string $providerId, string $op, ?string $want): string {
        $list = $this->map[$providerId][$op] ?? [];
        if (empty($list)) { throw new \RuntimeException('No version for provider/op'); }
        if ($want === null || $want === '') { return end($list) ?: $list[count($list)-1]; }
        if (!in_array($want, $list, true)) { throw new \InvalidArgumentException('Unsupported version: '.$want); }
        return $want;
    }
    public function support(string $providerId, string $op, string $version): bool {
        return in_array($version, $this->map[$providerId][$op] ?? [], true);
    }
}
