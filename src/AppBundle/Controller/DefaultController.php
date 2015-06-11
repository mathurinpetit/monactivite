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

        for($i=0;$i<7;$i++) {
            $date = new \DateTime("2015-06-11");
            $date->modify("- ".$i."days");

            $activitiesByDates[$date->format('Y-m-d')] = $repo->findByDate($date);
        }

        return $this->render('default/index.html.twig', array('activitiesByDates' => $activitiesByDates));
    }
}
