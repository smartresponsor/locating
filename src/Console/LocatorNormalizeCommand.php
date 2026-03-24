<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\Console;

use Smartresponsor\Service\LocationLocatorService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'locator:normalize', description: 'Normalize a raw address')]
final class LocatorNormalizeCommand extends Command
{
    public function __construct(private LocationLocatorService $svc)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('raw', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $raw = (string) $input->getArgument('raw');
        $result = $this->svc->normalize($raw)->toArray();
        $output->writeln(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return Command::SUCCESS;
    }
}
