<?php 

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Entity\Source;

class SourceAddCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('monactivite:source:add')
            ->setDescription('Add a source')
            ->addArgument('importer', InputArgument::REQUIRED, 'Importer to use')
            ->addArgument('source', InputArgument::REQUIRED, 'Source access')
            ->addArgument('name', InputArgument::OPTIONAL, 'Source name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        
        $source = new Source();

        $source->setImporter($input->getArgument('importer'));
        $source->setSource($input->getArgument('source'));
        $source->setName($input->getArgument('name'));

        $em->persist($source);
        $em->flush();
    }
}
?>