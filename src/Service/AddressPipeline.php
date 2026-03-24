<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Smartresponsor\Service;

use Smartresponsor\Entity\AddressInput;
use Smartresponsor\Entity\AddressResult;
use Smartresponsor\Entity\AddressStatus;
use Smartresponsor\ServiceInterface\AddressGeocodeBridgeInterface;
use Smartresponsor\ServiceInterface\AddressNormalizerInterface;
use Smartresponsor\ServiceInterface\AddressParserInterface;
use Smartresponsor\ServiceInterface\AddressPipelineInterface;
use Smartresponsor\ServiceInterface\AddressValidatorInterface;

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
