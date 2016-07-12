<?php

namespace Acquia\Blt\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ComposerMungeCommand extends Command
{
  protected function configure()
  {
    $this
      ->setName('composer:munge')
      ->setDescription('Munge values in two composer.json files')
      ->addArgument(
        'file1',
        InputArgument::REQUIRED,
        'The first composer.json. Any conflicts will prioritize the value in this file.'
      )
      ->addArgument(
        'file2',
        InputArgument::REQUIRED,
        'The second composer.json.'
      )
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $file1 = $input->getArgument('file1');
    $file2 = $input->getArgument('file2');
    $munged_json = $this->munge($file1, $file2);

    $output->writeln($munged_json);
  }

  protected $repoRoot = '';

  /**
   * @param $file1
   * @param $file2
   */
  protected function munge($file1, $file2) {
    $file1_contents = json_decode(file_get_contents($file1), true);
    $file2_contents = json_decode(file_get_contents($file2), true);

    $merge_keys = [
      'require',
      'require-dev',
      'scripts'
    ];
    $output = $file1_contents;
    foreach ($merge_keys as $key) {
      $output[$key] = array_replace_recursive((array) $file1_contents[$key], (array) $file2_contents[$key]);
    }

    $output_json = json_encode($output, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);

    return $output_json;
  }
}
