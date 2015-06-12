<?php 

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SourceExecuteCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('monactivite:source:execute')
            ->setDescription('Execute a source')
            ->addArgument('name', InputArgument::REQUIRED, 'Source name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        
    }
}
?>