<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


        namespace App\Tests\Status;

        use App\Controller\StatusController;
        use App\Infrastructure\InMemoryMetricRecorder;
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
