<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

use App\Controller\AddressReverseController;
use App\Controller\AddressSuggestController;
use App\Infrastructure\InMemoryMetricRecorder;
use App\InfrastructureInterface\MetricRecorderInterface;
use App\InfrastructureInterface\MetricSnapshotProviderInterface;
use App\Service\AddressReverse;
use App\Service\AddressSuggest;
use App\Service\AddressQuotaGuard;
use App\ServiceInterface\AddressQuotaGuardInterface;
use App\ServiceInterface\AddressReverseInterface;
use App\ServiceInterface\AddressSuggestInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
            ->autowire()
            ->autoconfigure()
            ->private();

    $services->load('App\\', '../src/*')
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
