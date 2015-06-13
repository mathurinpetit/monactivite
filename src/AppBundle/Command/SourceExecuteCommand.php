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
            ->addOption('dry-run', 't', InputOption::VALUE_NONE, 'Try import but not store in database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mm = $this->getContainer()->get('app.manager.main');
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        
        $source = $em->getRepository('AppBundle:Source')->findOneBy(array('name' => $input->getArgument('name')));

        if(!$source) {

            throw new \Exception(sprintf("Source \"%s\" not found", $input->getArgument('name')));
        }
        
        $mm->executeSource($source, 
                           $output, 
                           $input->getOption('dry-run'));
    }
}
?>