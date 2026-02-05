<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Entity\Locator;
final class TraceContext {
    public function __construct(public string $traceId, public string $spanId){}
    public static function new(): self {
        return new self(bin2hex(random_bytes(8)), bin2hex(random_bytes(8)));
    }
    public function child(): self {
        return new self($this->traceId, bin2hex(random_bytes(8)));
    }
}
