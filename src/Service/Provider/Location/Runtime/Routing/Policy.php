<?php

// Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace App\Locating\Service\Provider\Location\Runtime\Routing;

use App\Locating\ServiceInterface\Provider\Location\Runtime\Routing\PolicyInterface;

final class Policy implements PolicyInterface
{
    /**
     * @param array<string, mixed> $cfg
     */
    public function __construct(private array $cfg)
    {
    }

    /** @return list<string> */
    public function order(string $purpose, ?string $region): array
    {
        $region = $region ?: 'world';
        $defaultsRoot = is_array($this->cfg['defaults'] ?? null) ? $this->cfg['defaults'] : [];
        $regionsRoot = is_array($this->cfg['regions'] ?? null) ? $this->cfg['regions'] : [];
        $regionConfig = is_array($regionsRoot[$region] ?? null) ? $regionsRoot[$region] : [];
        $defaults = $this->stringList($defaultsRoot[$purpose] ?? null);
        $regional = $this->stringList($regionConfig[$purpose] ?? null);

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

        if (!is_array($cfg)) {
            return new self([]);
        }
        $normalized = [];
        foreach ($cfg as $key => $value) {
            if (is_string($key)) {
                $normalized[$key] = $value;
            }
        }

        return new self($normalized);
    }

    /** @return list<string> */
    private function stringList(mixed $value): array
    {
        if (!is_array($value)) {
            return [];
        }

        $items = [];
        foreach ($value as $item) {
            if (is_string($item) && '' !== $item) {
                $items[] = $item;
            }
        }

        return $items;
    }
}
