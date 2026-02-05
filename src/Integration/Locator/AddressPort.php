<?php
declare(strict_types=1);
namespace Smartresponsor\Integration\Locator;
use Smartresponsor\Model\Locator\AddressData;
interface AddressPort{ public function persist(AddressData $address): void; }
