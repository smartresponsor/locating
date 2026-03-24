<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Command\Geo;

use App\ServiceInterface\Http\Location\AddressReverseServiceInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'location:reverse', description: 'Reverse-geocode a point into the canonical location payload.')]
final class LocationReverseCommand extends Command
{
    public function __construct(private readonly AddressReverseServiceInterface $addressReverseService)
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
        $latitude = (float) $input->getArgument('lat');
        $longitude = (float) $input->getArgument('lon');
        $reverseView = $this->addressReverseService->reverse($latitude, $longitude);

        $output->writeln(json_encode($reverseView->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));

        return Command::SUCCESS;
    }
}
