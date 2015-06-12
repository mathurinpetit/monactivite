<?php 

namespace AppBundle\Activity;

use AppBundle\Entity\Activity;
use AppBundle\Entity\Filter;
use AppBundle\Entity\Source;

class ActivityManager
{
    protected $em;
    protected $repository;
    protected $slugger;
    protected $im;

    public function __construct($em, $slugger, $im) {
        $this->em = $em;
        $this->repository = $em->getRepository('AppBundle:Activity');
        $this->slugger = $slugger;
        $this->im = $im;
    }

    public function addFromEntity(Activity $activity) {
        if(!$activity->getExecutedAt()) {
            throw new \Exception("Date is required");
        }

        if(!$activity->getTitle()) {
            throw new \Exception("Title is required");
        }

        $activity->setSlug(md5($this->slugger->slugify(sprintf("%s_%s", $activity->getExecutedAt()->format('Y-m-d H:i-s'), $activity->getTitle()))));

        if($this->repository->findBySlug($activity->getSlug())) {
            throw new \Exception("Already exist");
        }
        
        return $activity;
    }

    public function executeSource(Source $source) {
        /*$importer = $this->getContainer()->get('app.importer.'.$input->getArgument('name'));
        
        $importer->check($input->getArgument('source'));
        $importer->run($input->getArgument('source'), $input->getArgument('source-name'), $output, $input->getOption('dry-run'));*/
    }

    public function executeFilter(Filter $filter) {
        $activities = $this->repository->findByFilter($filter);

        foreach($activities as $activity) {
            $activity->addTag($filter->getTag());
            $this->em->persist($activity);
        }

        return count($activities);
    }
}