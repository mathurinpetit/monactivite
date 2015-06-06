<?php 

namespace AppBundle\Activity;

use AppBundle\Entity\Activity;

class ActivityManager
{
    protected $slugger;

    public function __construct($slugger) {
        $this->slugger = $slugger;
    }

    public function fromEntity(Activity $activity) {
        $activity->setSlug($this->slugger->slugify(sprintf("%s_%s", $activity->getExecutedAt()->format('Y-m-d H:i-s'), $activity->getTitle())));
        
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