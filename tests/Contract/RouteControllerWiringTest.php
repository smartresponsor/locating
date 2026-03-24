<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace App\Tests\Contract;

use PHPUnit\Framework\TestCase;

final class RouteControllerWiringTest extends TestCase
{
    public function testEveryConfiguredControllerClassExists(): void
    {
        $controllers = array_merge(
            $this->controllersFromYamlRoutes(__DIR__ . '/../../../config/routes'),
            $this->controllersFromPhpRoutes(__DIR__ . '/../../../config/routes')
        );

        self::assertNotEmpty($controllers, 'No controllers found in route configuration.');

        foreach (array_values(array_unique($controllers)) as $controller) {
            self::assertStringStartsWith(
                'App\\Controller\\',
                $controller,
                sprintf('Controller must use App namespace: %s', $controller)
            );
            self::assertTrue(class_exists($controller), sprintf('Controller class not found: %s', $controller));
            self::assertTrue(method_exists($controller, '__invoke'), sprintf('Controller must be invokable: %s', $controller));
        }
    }

    /**
     * @return list<string>
     */
    private function controllersFromYamlRoutes(string $routesDir): array
    {
        $controllers = [];

        foreach (glob($routesDir . '/*.yaml') ?: [] as $yamlFile) {
            $content = (string) file_get_contents($yamlFile);
            if ($content === '') {
                continue;
            }

            if (preg_match_all('/^\s*controller:\s*([^\n]+)$/m', $content, $matches) > 0) {
                foreach ($matches[1] as $candidate) {
                    $controller = trim($candidate, " \t\n\r\0\x0B\"'");
                    if ($controller !== '') {
                        $controllers[] = $controller;
                    }
                }
            }
        }

        return $controllers;
    }

    /**
     * @return list<string>
     */
    private function controllersFromPhpRoutes(string $routesDir): array
    {
        $controllers = [];

        foreach (glob($routesDir . '/*.php') ?: [] as $phpRouteFile) {
            $content = (string) file_get_contents($phpRouteFile);
            if ($content === '') {
                continue;
            }

            if (preg_match_all('/->controller\(\s*[\"\']([^\"\']+)[\"\']\s*\)/', $content, $matches) > 0) {
                foreach ($matches[1] as $controller) {
                    $controllers[] = $controller;
                }
            }
        }

        return $controllers;
    }
}
