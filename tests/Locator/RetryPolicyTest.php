<?php

# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace App\Locating\Tests\Locator;

use App\Locating\Model\Locator\Policy\RetryPolicy as EntityRetryPolicy;
use App\Locating\Service\Provider\Location\Runtime\Resilience\RetryPolicy;
use PHPUnit\Framework\TestCase;

final class RetryPolicyTest extends TestCase
{
    public function testShouldRetryForRetriableStatusCodes(): void
    {
        $policy = new RetryPolicy();

        $this->assertTrue($policy->shouldRetry(0, 3, 500));
        $this->assertTrue($policy->shouldRetry(1, 3, 429));
        $this->assertFalse($policy->shouldRetry(3, 3, 500));
        $this->assertFalse($policy->shouldRetry(1, 3, 404));
    }

    public function testDelayRangeIsBoundedByCap(): void
    {
        $policy = new RetryPolicy();

        $delay = $policy->delayMs(2, 10, 200);

        $this->assertGreaterThanOrEqual(0, $delay);
        $this->assertLessThanOrEqual(40, $delay);
    }

    public function testEntityRetryPolicyDelegatesToServicePolicy(): void
    {
        $policy = new EntityRetryPolicy();

        $this->assertTrue($policy->shouldRetry(0, 2, 503));

        $delay = $policy->delayMs(3, 10, 60);
        $this->assertGreaterThanOrEqual(0, $delay);
        $this->assertLessThanOrEqual(60, $delay);
    }
}
