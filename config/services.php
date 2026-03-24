<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

use Smartresponsor\Controller\AddressReverseController;
use Smartresponsor\Controller\AddressSuggestController;
use Smartresponsor\Infrastructure\InMemoryMetricRecorder;
use Smartresponsor\InfrastructureInterface\MetricRecorderInterface;
use Smartresponsor\InfrastructureInterface\MetricSnapshotProviderInterface;
use Smartresponsor\Service\AddressReverse;
use Smartresponsor\Service\AddressSuggest;
use Smartresponsor\Service\AddressQuotaGuard;
use Smartresponsor\ServiceInterface\AddressQuotaGuardInterface;
use Smartresponsor\ServiceInterface\AddressReverseInterface;
use Smartresponsor\ServiceInterface\AddressSuggestInterface;
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
