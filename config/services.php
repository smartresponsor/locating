<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

use Smartresponsor\Controller\Locator\AddressReverseController;
use Smartresponsor\Controller\Locator\AddressSuggestController;
use Smartresponsor\Infrastructure\Locator\InMemoryMetricRecorder;
use Smartresponsor\InfrastructureInterface\Locator\MetricRecorderInterface;
use Smartresponsor\InfrastructureInterface\Locator\MetricSnapshotProviderInterface;
use Smartresponsor\Service\Locator\AddressReverse;
use Smartresponsor\Service\Locator\AddressSuggest;
use Smartresponsor\Service\Locator\AddressQuotaGuard;
use Smartresponsor\ServiceInterface\Locator\AddressQuotaGuardInterface;
use Smartresponsor\ServiceInterface\Locator\AddressReverseInterface;
use Smartresponsor\ServiceInterface\Locator\AddressSuggestInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
            ->autowire()
            ->autoconfigure()
            ->private();

    $services->load('Smartresponsor\\', '../src/*')
        ->exclude([
            '../src/{DependencyInjection,Entity,Kernel.php,Tests}',
        ]);

    $services->alias(MetricRecorderInterface::class, InMemoryMetricRecorder::class);
    $services->alias(MetricSnapshotProviderInterface::class, InMemoryMetricRecorder::class);

    $services->alias(AddressSuggestInterface::class, AddressSuggest::class);
    $services->alias(AddressReverseInterface::class, AddressReverse::class);
    $services->alias(AddressQuotaGuardInterface::class, AddressQuotaGuard::class);

    $services->set(AddressSuggestController::class)->public();
    $services->set(AddressReverseController::class)->public();
};
