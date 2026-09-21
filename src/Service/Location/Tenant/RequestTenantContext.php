<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\Locating\Service\Location\Tenant;

use App\Locating\ServiceInterface\Location\Tenant\TenantContextInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Tenant context based on the current HTTP request.
 *
 * Resolution order:
 *   1. X-SR-Tenant header
 *   2. tenant query parameter
 *   3. LOCATOR_DEFAULT_TENANT environment variable
 *   4. 'demo' fallback
 */
final class RequestTenantContext implements TenantContextInterface
{
    private const HEADER_NAME = 'X-SR-Tenant';
    private const QUERY_NAME = 'tenant';
    private const DEFAULT_TENANT = 'demo';

    public function __construct(private RequestStack $requestStack)
    {
    }

    public function id(): string
    {
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            $envRaw = $_ENV['LOCATOR_DEFAULT_TENANT'] ?? $_SERVER['LOCATOR_DEFAULT_TENANT'] ?? null;
            $envValue = is_string($envRaw) ? $envRaw : '';
            $fallback = '' !== $envValue ? $envValue : self::DEFAULT_TENANT;

            return $fallback;
        }

        $headerValue = (string) $request->headers->get(self::HEADER_NAME, '');
        if ('' !== $headerValue) {
            return $this->normalize($headerValue);
        }

        $queryValue = (string) $request->query->get(self::QUERY_NAME, '');
        if ('' !== $queryValue) {
            return $this->normalize($queryValue);
        }

        $envRaw = $_ENV['LOCATOR_DEFAULT_TENANT'] ?? $_SERVER['LOCATOR_DEFAULT_TENANT'] ?? null;
        $envValue = is_string($envRaw) ? $envRaw : '';
        if ('' !== $envValue) {
            return $this->normalize($envValue);
        }

        return self::DEFAULT_TENANT;
    }

    private function normalize(string $value): string
    {
        $value = trim($value);
        if ('' === $value) {
            return self::DEFAULT_TENANT;
        }

        return $value;
    }
}
