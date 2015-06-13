<?php 

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ExecuteOneCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('monactivite:execute:one')
            ->addArgument('source-name', InputArgument::REQUIRED, 'Source name')
            ->setDescription('Execute a source and filters')
            ->addOption('dry-run', 't', InputOption::VALUE_NONE, 'Try import but not store in database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mm = $this->getContainer()->get('app.manager.main');
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $source = $em->getRepository('AppBundle:Source')->findOneBy(array('name' => $input->getArgument('source-name')));
        
        $mm->executeOne($source, 
                        $output, 
                        $input->getOption('dry-run'));
    }
}
?>