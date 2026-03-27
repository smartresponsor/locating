<?php

declare(strict_types=1);

namespace Tests\Security;

use PHPUnit\Framework\TestCase;
use Smartresponsor\Integration\Locator\Log\Redactor;

final class RedactorTest extends TestCase
{
    public function testMasksKnownSensitiveKeys(): void
    {
        $masked = Redactor::mask([
            'GOOGLE_API_KEY' => 'top-secret',
            'USPS_USERID' => 'user-id',
            'Authorization' => 'Bearer secret',
            'X-API-Key' => 'api-key',
            'safe' => 'value',
        ]);

        self::assertSame('***', $masked['GOOGLE_API_KEY']);
        self::assertSame('***', $masked['USPS_USERID']);
        self::assertSame('***', $masked['Authorization']);
        self::assertSame('***', $masked['X-API-Key']);
        self::assertSame('value', $masked['safe']);
    }
}
