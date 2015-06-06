<?php 

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Import\ActivityImport;

class ActivityAddCommand extends ContainerAwareCommand
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
        $am = $this->getContainer()->get('app.activity.manager');
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        
        $activity = $am->fromArray(array(
            "executed_at" => $input->getArgument('date'),
            "title" => $input->getArgument('title'),
            "content" => $input->getArgument('content'),
        ));

        $em->persist($activity);
        $em->flush();
    }
}
?>