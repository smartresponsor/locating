<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

        namespace App\Tests\Locator\Status;

        use App\Controller\Locator\StatusController;
        use App\Infrastructure\Locator\InMemoryMetricRecorder;
        use PHPUnit\Framework\TestCase;
        use Symfony\Component\HttpFoundation\Request;

        final class StatusControllerTest extends TestCase
        {
            public function testStatusReturnsOkWithMetrics(): void
            {
                $recorder = new InMemoryMetricRecorder();
                $recorder->recordLatency('address_pipeline', 10.0);
                $recorder->incrementCounter('address_pipeline', 'ok');

                $controller = new StatusController($recorder);

                $response = $controller(new Request());

                self.assertSame(200, $response->getStatusCode());

                $data = json_decode($response->getContent() ?: '', true, 512, JSON_THROW_ON_ERROR);

                self.assertSame('locator', $data['service'] ?? null);
                self.assertSame('ok', $data['status'] ?? null);
                self.assertArrayHasKey('metrics', $data);
                self.assertArrayHasKey('address_pipeline', $data['metrics']);
            }
        }
