<?php
declare(strict_types=1);

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routes): void {
    $routes->add('locator_address_reverse', '/locator/address/reverse')
        ->controller('App\\Controller\\Locator\\AddressReverseController')
        ->methods(['GET']);
};
