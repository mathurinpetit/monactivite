<?php 

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Entity\Tag;

class TagAddCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('monactivite:tag:add')
            ->setDescription('Add a tag')
            ->addArgument('name', InputArgument::REQUIRED, "Name")
            ->addArgument('color', InputArgument::OPTIONAL, 'Color')
            ->addArgument('icon', InputArgument::OPTIONAL, 'Icon')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $tag = new Tag();
        $tag->setName($input->getArgument('name'));
        $tag->setColor($input->getArgument('color'));
        $tag->setIcon($input->getArgument('icon'));

        $em->persist($tag);
        $em->flush();
    }
}
?>