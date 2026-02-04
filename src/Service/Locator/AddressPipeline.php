<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\Service\Locator;

use App\Entity\Locator\AddressInput;
use App\Entity\Locator\AddressResult;
use App\Entity\Locator\AddressStatus;
use App\ServiceInterface\Locator\AddressGeocodeBridgeInterface;
use App\ServiceInterface\Locator\AddressNormalizerInterface;
use App\ServiceInterface\Locator\AddressParserInterface;
use App\ServiceInterface\Locator\AddressPipelineInterface;
use App\ServiceInterface\Locator\AddressValidatorInterface;

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
