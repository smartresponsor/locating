<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace App\Tests\Panther;

use PHPUnit\Framework\Attributes\Group;
use Symfony\Component\Panther\PantherTestCase;

#[Group('panther')]
final class PublicIndexPantherTest extends PantherTestCase
{
    public function testPantherCanReadTheRootJsonContract(): void
    {
        $client = static::createPantherClient([
            'external_base_uri' => $_SERVER['PANTHER_EXTERNAL_BASE_URI'] ?? 'http://127.0.0.1:8000',
        ]);

        $client->request('GET', '/');

        self::assertStringContainsString('"status":"ok"', $client->getPageSource());
        self::assertStringContainsString('"component":"locator-sketch30"', $client->getPageSource());
    }
}
