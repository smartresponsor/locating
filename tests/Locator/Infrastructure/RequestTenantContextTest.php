<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\Tests\Locator\Infrastructure;

use Smartresponsor\Infrastructure\Locator\RequestTenantContext;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class RequestTenantContextTest extends TestCase
{
    public function testHeaderWinsOverQueryEnvAndDefault(): void
    {
        $stack = new RequestStack();
        $request = new Request(['tenant' => 'query-tenant'], [], [], [], [], [], null);
        $request->headers->set('X-SR-Tenant', 'header-tenant');
        $stack->push($request);

        $context = new RequestTenantContext($stack);

        self::assertSame('header-tenant', $context->id());
    }

    public function testQueryWinsOverEnvAndDefault(): void
    {
        $stack = new RequestStack();
        $request = new Request(['tenant' => 'query-tenant'], [], [], [], [], [], null);
        $stack->push($request);

        $context = new RequestTenantContext($stack);

        self::assertSame('query-tenant', $context->id());
    }

    public function testEnvWinsWhenNoRequest(): void
    {
        $_ENV['LOCATOR_DEFAULT_TENANT'] = 'env-tenant';

        $stack = new RequestStack();
        $context = new RequestTenantContext($stack);

        self::assertSame('env-tenant', $context->id());

        unset($_ENV['LOCATOR_DEFAULT_TENANT']);
    }

    public function testFallbackDemoWhenNothingIsProvided(): void
    {
        $stack = new RequestStack();
        $context = new RequestTenantContext($stack);

        self::assertSame('demo', $context->id());
    }
}
