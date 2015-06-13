<?php 

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Entity\Filter;

class FilterAddCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('monactivite:filter:add')
            ->setDescription('Add a filter')
            ->addArgument('tag-name', InputArgument::REQUIRED, "Tag's name")
            ->addArgument('query', InputArgument::REQUIRED, 'Query')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $tag = $em->getRepository('AppBundle:Tag')->findOneBy(array("name" => $input->getArgument('tag-name')));

        if(!$tag) {

            throw new \Exception(sprintf("Tag \"%s\" not found", $input->getArgument('tag-name')));
        }
        
        $filter = new Filter();
        $filter->setTag($tag);
        $filter->setQuery($input->getArgument('query'));

        $em->persist($filter);
        $em->flush();
    }
}
?>