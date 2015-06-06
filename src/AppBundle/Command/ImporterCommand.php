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
            ->addArgument('argument', InputArgument::REQUIRED, 'Argument to run importer')
            ->addOption('dry-run', 't', InputOption::VALUE_NONE, 'Try import but not store in database');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $importer = $this->getContainer()->get('app.importer.'.$input->getArgument('name'));
        
        $importer->check($input->getArgument('argument'));
        $importer->run($input->getArgument('argument'), $output, $input->getOption('dry-run'));
    }
}
?>