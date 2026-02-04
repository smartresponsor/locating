<?php
declare(strict_types=1);
namespace SmartResponsor\Console;
use SmartResponsor\Service\Locator\LocatorService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
#[AsCommand(name: 'locator:normalize', description: 'Normalize a raw address')]
final class LocatorNormalizeCommand extends Command{
  public function __construct(private LocatorService $svc){ parent::__construct(); }
  protected function configure(): void{ $this->addArgument('raw', InputArgument::REQUIRED); }
  protected function execute(InputInterface $input, OutputInterface $output): int{
    $raw=(string)$input->getArgument('raw'); $r=$this->svc->normalize($raw)->toArray();
    $output->writeln(json_encode($r, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
    return Command::SUCCESS;
  }
}
