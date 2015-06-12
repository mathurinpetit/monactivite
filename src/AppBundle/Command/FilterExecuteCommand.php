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
            ->addArgument('filter_id', InputArgument::REQUIRED, 'Filter id')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mm = $this->getContainer()->get('app.main.manager');
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        
        $filter = $em->getRepository('AppBundle:Filter')->find($input->getArgument('filter_id'));

        $nbUpdated = $mm->executeFilter($filter);

        $em->flush();

        $output->writeln(sprintf("tag <comment>\"%s\"</comment> has been <info>added</info> in <comment>%s</comment> activity</info>", $filter->getTag()->getName(), $nbUpdated));
    }
}
?>