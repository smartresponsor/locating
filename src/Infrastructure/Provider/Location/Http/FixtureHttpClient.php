<?php

declare(strict_types=1);

namespace App\Locating\Infrastructure\Provider\Location\Http;

final class FixtureHttpClient implements HttpClientInterface
{
    public function __construct(private string $dir)
    {
    }

    /**
     * @param array<string,mixed> $o
     * @return array<string,mixed>
     */
    public function get(string $u, array $o = []): array
    {
        $f = $this->dir.'/'.md5($u).'.json';
        if (!file_exists($f)) {
            return [];
        }
        $contents = file_get_contents($f);
        if (!is_string($contents)) {
            return [];
        }
        $decoded = json_decode($contents, true);
        if (!is_array($decoded)) {
            return [];
        }
        $result = [];
        foreach ($decoded as $key => $value) {
            if (is_string($key)) {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /** @param array<string,mixed> $o */
    public function getRaw(string $u, array $o = []): string
    {
        $f = $this->dir.'/'.md5($u).'.xml';
        if (!file_exists($f)) {
            return '';
        }
        $contents = file_get_contents($f);

        return is_string($contents) ? $contents : '';
    }
}
