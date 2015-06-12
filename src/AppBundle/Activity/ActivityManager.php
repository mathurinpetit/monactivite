<?php 

namespace AppBundle\Activity;

use AppBundle\Entity\Activity;
use AppBundle\Entity\Filter;

class ActivityManager
{
    protected $em;
    protected $repository;
    protected $slugger;

    public function __construct($em, $slugger) {
        $this->em = $em;
        $this->slugger = $slugger;
        $this->repository = $em->getRepository('AppBundle:Activity');
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

    public function executeFilter(Filter $filter) {
        $activities = $this->repository->findByFilter($filter);

        foreach($activities as $activity) {
            $activity->addTag($filter->getTag());
            $this->em->persist($activity);
        }

        return count($activities);
    }
}