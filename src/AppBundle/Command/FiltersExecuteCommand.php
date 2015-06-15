<?php 

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FiltersExecuteCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('monactivite:filters:execute')
            ->setDescription('Apply all filters on activities')
            ->addOption('dry-run', 't', InputOption::VALUE_NONE, 'Try import but not store in database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mm = $this->getContainer()->get('app.manager.main');
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        
        $mm->executeAllFilters($output, $input->getOption('dry-run'));
    }
}
?>