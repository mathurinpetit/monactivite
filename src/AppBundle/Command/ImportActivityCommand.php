<?php 

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportActivityCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('monactivite:activity:add')
            ->setDescription('Add an activity')
            ->addArgument('date', InputArgument::REQUIRED, 'Date realisation of your activity')
            ->addArgument('title', InputArgument::REQUIRED, 'Title of your activity')
            ->addArgument('content', InputArgument::OPTIONAL, 'Content of your activity')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $importActivity = $this->getContainer()->get('import_activity')
    }
}
?>