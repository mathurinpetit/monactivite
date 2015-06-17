<?php 

namespace AppBundle\Manager;

use AppBundle\Entity\Filter;
use Symfony\Component\Console\Output\OutputInterface;

    
class FilterManager
{
    protected $em;
    protected $repository;
    protected $slugger;

    public function __construct($em, $slugger) {
        $this->em = $em;
        $this->repository = $em->getRepository('AppBundle:Filter');
        $this->slugger = $slugger;
    }

    public function executeOne(Filter $filter, OutputInterface $output, $dryRun = false) {
        $activities = $this->em->getRepository('AppBundle:Activity')->findByFilter($filter);

        foreach($activities as $activity) {
            $activity->addTag($filter->getTag());
            $this->em->persist($activity);
        }

        if(!$dryRun) {
            $this->em->flush();
        }

        $output->writeln(sprintf("tag <comment>\"%s\"</comment> has been <info>added</info> in <comment>%s</comment> activity</info>", $filter->getTag()->getName(), count($activities)));
    }

    public function executeAll(OutputInterface $output, $dryRun = false) {
        $filters = $this->repository->findAll();

        foreach($filters as $filter) {
            $this->executeOne($filter, $output, $dryRun);
        }
    }
}