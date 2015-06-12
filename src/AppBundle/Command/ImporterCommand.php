<?php 

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImporterCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('monactivite:importer')
            ->setDescription('Run importer')
            ->addArgument('name', InputArgument::REQUIRED, 'Name of the importer')
            ->addArgument('source', InputArgument::REQUIRED, 'Source')
            ->addArgument('source-name', InputArgument::REQUIRED, 'Source name')
            ->addOption('dry-run', 't', InputOption::VALUE_NONE, 'Try import but not store in database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $im = $this->getContainer()->get('app.importer.manager');

        $im->execute($input->getArgument('name'), 
                     $input->getArgument('source'), 
                     $input->getArgument('source-name'), 
                     $output, 
                     $input->getOption('dry-run'));
    }
}
?>