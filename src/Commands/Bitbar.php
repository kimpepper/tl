<?php

namespace Larowlan\Tl\Commands;

use Larowlan\Tl\Connector\Connector;
use Larowlan\Tl\Formatter;
use Larowlan\Tl\Repository\Repository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 */
class Bitbar extends Command {

  /**
   * @var \Larowlan\Tl\Connector\Connector
   */
  protected $connector;

  /**
   * @var \Larowlan\Tl\Repository\Repository
   */
  protected $repository;

  /**
   *
   */
  public function __construct(Connector $connector, Repository $repository) {
    $this->connector = $connector;
    $this->repository = $repository;
    parent::__construct();
  }

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this
      ->setName('bitbar')
      ->setDescription('Bitbar output')
      ->setHelp('Bitbar output<comment>Usage:</comment> <info>tl bitbar</info>')
      ->addUsage('tl bitbar');
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    if ($open = $this->repository->getActive()) {
      $text = $open->tid . ': ' . Formatter::formatDuration(time() - $open->start) . ' ';
    }
    else {
      $text = 'Inactive ';
    }
    $total = 0;
    foreach ($this->repository->review(Total::ALL) as $data) {
      $total += $data->duration;
    }
    $text .= '(' . $total . 'h)';
    $output->writeln($text);
  }

}
