<?php 

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ExecuteCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('monactivite:execute')
            ->setDescription('Execute all : sources and filters')
            ->addOption('dry-run', 't', InputOption::VALUE_NONE, 'Try import but not store in database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mm = $this->getContainer()->get('app.manager.main');
        
        $mm->executeAll($output, 
                        $input->getOption('dry-run'));
    }
}
?>