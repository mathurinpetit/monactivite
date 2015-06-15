<?php 

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FilterExecuteCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('monactivite:filter:execute')
            ->setDescription('Apply a filter on activities')
            ->addArgument('filter-id', InputArgument::REQUIRED, 'Filter id')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mm = $this->getContainer()->get('app.manager.main');
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        
        $filter = $em->getRepository('AppBundle:Filter')->find($input->getArgument('filter-id'));

        if(!$filter) {

            throw new \Exception(sprintf("Filter %s not found", $input->getArgument('name')));
        }

        $mm->executeFilter($filter, $output);        
    }
}
?>