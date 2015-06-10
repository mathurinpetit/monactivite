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
            throw new \Exception("Date is required");
        }

        if(!$activity->getTitle()) {
            throw new \Exception("Ttitle is required");
        }

        $activity->setSlug(md5($this->slugger->slugify(sprintf("%s_%s", $activity->getExecutedAt()->format('Y-m-d H:i-s'), $activity->getTitle()))));

        if($this->repository->findBySlug($activity->getSlug())) {
            throw new \Exception("Already exist");
        }
        
        return $activity;
    }

    public function fromArray($datas) {
        $activity = new Activity();

        if(!isset($datas['executed_at'])) {

        } elseif($datas['executed_at'] instanceof \DateTime) {
            $activity->setExecutedAt($datas['executed_at']);
        } elseif($datas['executed_at']) {
            $activity->setExecutedAt(new \DateTime($datas['executed_at']));
        }

        if(isset($datas['title'])) {
            $activity->setTitle($datas['title']);
        }
        if(isset($datas['author'])) {
            $activity->setAuthor($datas['author']);
        }
        if(isset($datas['destination'])) {
            $activity->setDestination($datas['destination']);
        }
        if(isset($datas['type'])) {
            $activity->setType($datas['type']);
        }
        if(isset($datas['content'])) {
            $activity->setContent($datas['content']);
        }
        if(isset($datas['source'])) {
            $activity->setSource($datas['source']);
        }

        return $this->fromEntity($activity);
    }

}