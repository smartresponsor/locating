<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Privacy\Location;

use App\Locating\Service\Privacy\Location\RetentionPolicy;
use App\Locating\Service\Privacy\Location\RetentionSweeper;
use PHPUnit\Framework\TestCase;

final class RetentionPolicyTest extends TestCase
{
    public function testDefaultAndOverrideTtlAreStable(): void
    {
        $policy = new RetentionPolicy();

        self::assertSame(365, $policy->ttl('address'));
        self::assertSame(1825, $policy->ttl('audit'));
        self::assertSame(365, $policy->ttl('unknown'));

        $policy->set('address', 30);

        self::assertSame(30, $policy->ttl('address'));
        self::assertSame(1825, $policy->ttl('audit'));
    }

    public function testNegativeOverrideIsClampedAndUsedBySweeper(): void
    {
        $policy = new RetentionPolicy();
        $policy->set('address', -10);

        $sql = (new RetentionSweeper())->plan(['address' => 'address_log'], $policy);

        self::assertSame(
            ["DELETE FROM address_log WHERE created_at < NOW() - INTERVAL '0 day';"],
            $sql,
        );
    }
}
