<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\Console;

use Smartresponsor\Model\Locator\GeoPoint;
use Smartresponsor\Service\Locator\LocationLocatorService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'locator:reverse', description: 'Reverse geocode a point')]
final class LocatorReverseCommand extends Command
{
    public function __construct(private LocationLocatorService $svc)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('lat', InputArgument::REQUIRED);
        $this->addArgument('lon', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $lat = (float) $input->getArgument('lat');
        $lon = (float) $input->getArgument('lon');
        $address = $this->svc->reverse(new GeoPoint($lat, $lon))->toArray();
        $output->writeln(json_encode($address, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return Command::SUCCESS;
    }
}
