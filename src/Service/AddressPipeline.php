<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace App\Service;

use App\Entity\AddressInput;
use App\Entity\AddressResult;
use App\Entity\AddressStatus;
use App\ServiceInterface\AddressGeocodeBridgeInterface;
use App\ServiceInterface\AddressNormalizerInterface;
use App\ServiceInterface\AddressParserInterface;
use App\ServiceInterface\AddressPipelineInterface;
use App\ServiceInterface\AddressValidatorInterface;

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
