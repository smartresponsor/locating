<?php
declare(strict_types=1);
namespace SmartResponsor\Integration\Locator;
use SmartResponsor\Model\Locator\AddressData;
interface AddressPort{ public function persist(AddressData $address): void; }
