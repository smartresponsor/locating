<?php

// Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\Service\Locator;

use App\Bridge\Legacy\Service\Location\PolicyLegacyInterface;

final class Policy implements PolicyLegacyInterface
{
    public function __construct(private array $cfg)
    {
    }

    public function order(string $purpose, ?string $region): array
    {
        $region = $region ?: 'world';
        $defaults = $this->cfg['defaults'][$purpose] ?? [];
        $regional = $this->cfg['regions'][$region][$purpose] ?? [];

        return array_values(array_unique(array_merge($regional, $defaults)));
    }

    public static function fromEnv(): self
    {
        $json = getenv('LOCATOR_POLICY_JSON') ?: '';
        if ('' !== $json) {
            $cfg = json_decode($json, true) ?: [];
        } else {
            $cfg = [
                'defaults' => [
                    'forward' => ['nominatim', 'mock'],
                    'reverse' => ['nominatim', 'mock'],
                ],
                'regions' => [
                    'eu' => [
                        'forward' => ['nominatim'],
                        'reverse' => ['nominatim'],
                    ],
                    'global' => [
                        'forward' => ['photon', 'mock'],
                        'reverse' => ['mock'],
                    ],
                ],
            ];
        }

        return new self($cfg);
    }
}
