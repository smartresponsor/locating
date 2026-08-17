<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Command\Geo;

use App\Locating\ServiceInterface\Http\Location\LocationAddressReverseServiceInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'location:reverse', description: 'Reverse-geocode a point into the canonical location payload.')]
final class LocationReverseCommand extends Command
{
    public function __construct(private readonly LocationAddressReverseServiceInterface $addressReverseService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('lat', InputArgument::REQUIRED, 'Latitude')
            ->addArgument('lon', InputArgument::REQUIRED, 'Longitude');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $latitudeValue = $input->getArgument('lat');
        $longitudeValue = $input->getArgument('lon');
        if (!is_numeric($latitudeValue) || !is_numeric($longitudeValue)) {
            throw new \InvalidArgumentException('Latitude and longitude must be numeric.');
        }
        $latitude = (float) $latitudeValue;
        $longitude = (float) $longitudeValue;
        $reverseView = $this->addressReverseService->reverse($latitude, $longitude);

        $output->writeln(json_encode($reverseView->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));

        return Command::SUCCESS;
    }
}
