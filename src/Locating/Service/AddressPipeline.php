<?php

declare(strict_types=1);

namespace App\Locating\Service;

use App\Locating\Model\AddressInput;
use App\Locating\Model\AddressPipelineResult;
use App\Locating\Model\AddressView;

final readonly class AddressPipeline
{
    public function process(AddressInput $input): AddressPipelineResult
    {
        $address = $this->normalize($this->parse($input));
        $issues = [];
        foreach (['street', 'city', 'countryCode'] as $field) {
            if ('' === trim((string) ($address->toArray()[$field] ?? ''))) {
                $issues[] = ['field' => $field, 'code' => 'missing', 'message' => sprintf('Field "%s" is required.', $field)];
            }
        }

        $status = [] === $issues
            ? AddressPipelineResult::STATUS_VERIFIED
            : ('' !== trim($address->street()) && '' !== trim($address->city())
                ? AddressPipelineResult::STATUS_PARTIAL
                : AddressPipelineResult::STATUS_REJECTED);

        return new AddressPipelineResult(
            $status,
            AddressPipelineResult::STATUS_REJECTED === $status ? null : $address,
            $issues,
        );
    }

    private function parse(AddressInput $input): AddressView
    {
        if ([] !== $input->data()) {
            return AddressView::fromArray($input->data());
        }

        $parts = array_map('trim', explode(',', trim($input->raw())));

        return new AddressView(
            $parts[0] ?? '',
            $parts[1] ?? '',
            $parts[2] ?? '',
            $parts[3] ?? '',
            strtoupper($parts[4] ?? ''),
        );
    }

    private function normalize(AddressView $address): AddressView
    {
        $data = $address->toArray();
        foreach ($data as $key => $value) {
            $data[$key] = trim((string) preg_replace('/\s+/', ' ', $value));
        }
        $data['countryCode'] = strtoupper($data['countryCode']);

        return AddressView::fromArray($data);
    }
}