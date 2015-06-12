<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="timeline")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Activity');

        $activitiesByDates = array();

        for($i=0;$i<20;$i++) {
            $date = new \DateTime(date('Y-m-d'));
            $date->modify("- ".$i."days");

            $activitiesByDates[$date->format('Y-m-d')] = $repo->findByDate($date);
        }

        $tags = $em->getRepository('AppBundle:Tag')->findAll();

        return $this->render('default/index.html.twig', array('activitiesByDates' => $activitiesByDates, 'tags' => $tags));
    }
}
