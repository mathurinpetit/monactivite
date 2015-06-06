<?php 

namespace AppBundle\Activity;

use AppBundle\Entity\Activity;

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

    public function fromEntity(Activity $activity) {
        if(!$activity->getExecutedAt()) {
            throw new \Exception("Title is required");
        }

        if(!$activity->getTitle()) {
            throw new \Exception("Date is required");
        }

        $activity->setSlug(md5($this->slugger->slugify(sprintf("%s_%s", $activity->getExecutedAt()->format('Y-m-d H:i-s'), $activity->getTitle()))));

        if($this->repository->findBySlug($activity->getSlug())) {
            throw new \Exception("Already exist");
        }
        
        return $activity;
    }

    public function fromArray($datas) {

        $activity = new Activity();
        $activity->setExecutedAt(new \DateTime($datas['executed_at']));
        $activity->setTitle($datas['title']);
        $activity->setContent($datas['content']);

        return $this->fromEntity($activity);
    }

}