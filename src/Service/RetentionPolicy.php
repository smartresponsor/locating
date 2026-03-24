<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp

declare(strict_types=1);

namespace Smartresponsor\Service;

use Smartresponsor\ServiceInterface\RetentionInterface;

final class RetentionPolicy implements RetentionInterface
{
    public function policy(): array
    {
        return [
            'address' => ['ttl_days' => 365, 'action' => 'anonymize'],
            'audit' => ['ttl_days' => 1825, 'action' => 'retain'],
        ];
    }

    public function rule(string $kind): array
    {
        $policy = $this->policy();

        return $policy[$kind] ?? ['ttl_days' => 365, 'action' => 'anonymize'];
    }
}
