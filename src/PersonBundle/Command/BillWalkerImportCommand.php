<?php

namespace Cerad\Bundle\PersonBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
//  Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BillWalkerImportCommand extends ContainerAwareCommand
{
  protected function configure()
  {
    $this->setName       ('cerad_person__bill_walker__import');
    $this->setDescription('Import Bill Walker Officials');
  //$this->addArgument   ('type', InputArgument::REQUIRED, 'zayso or ng2014');
    $this->addArgument   ('file', InputArgument::REQUIRED, 'file');
  }
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $filePath = $input->getArgument('file');
    
    $reader = $this->getContainer()->get('cerad_person__bill_walker__reader');
    
    $items = $reader->read($filePath);
    
    $saver = $this->getContainer()->get('cerad_person__referee__saver_dbal');
    
    $results = $saver->save($items);
    
    echo sprintf("BW %s Total: %d, Inserted: %d, Updated: %d\n",$filePath,
      $results['total'],$results['inserted'],$results['updated']);
  }
}