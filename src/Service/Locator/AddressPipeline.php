<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\Service\Locator;

use Smartresponsor\Entity\Locator\AddressInput;
use Smartresponsor\Entity\Locator\AddressResult;
use Smartresponsor\Entity\Locator\AddressStatus;
use Smartresponsor\ServiceInterface\Locator\AddressGeocodeBridgeInterface;
use Smartresponsor\ServiceInterface\Locator\AddressNormalizerInterface;
use Smartresponsor\ServiceInterface\Locator\AddressParserInterface;
use Smartresponsor\ServiceInterface\Locator\AddressPipelineInterface;
use Smartresponsor\ServiceInterface\Locator\AddressValidatorInterface;

final class AddressPipeline implements AddressPipelineInterface
{
    public function __construct(
        private AddressParserInterface $parser,
        private AddressNormalizerInterface $normalizer,
        private AddressValidatorInterface $validator,
        private ?AddressGeocodeBridgeInterface $geocodeBridge = null
    ) {
    }

    public function process(AddressInput $input): AddressResult
    {
        $parsed = $this->parser->parse($input);
        $normalized = $this->normalizer->normalize($parsed);

        $validation = $this->validator->validate($normalized);
        $status = $validation['status'];
        $issues = $validation['issues'];

        $result = AddressResult::create(
            $status,
            $status === AddressStatus::REJECTED ? null : $normalized,
            $issues
        );

        if ($this->geocodeBridge === null) {
            return $result;
        }

        return $this->geocodeBridge->enrich($normalized, $result);
    }
}
