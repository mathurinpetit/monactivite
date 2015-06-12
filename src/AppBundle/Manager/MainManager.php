<?php 

namespace AppBundle\Manager;

use AppBundle\Entity\Source;
use Symfony\Component\Console\Output\OutputInterface;

class MainManager
{
    protected $am;
    protected $im;
    protected $fm;
    protected $sm;

    public function __construct($am, $im, $fm, $sm) {
        $this->am = $am;
        $this->im = $im;
        $this->fm = $fm;
        $this->sm = $sm;
    }

    public function executeSource(Source $source, OutputInterface $output, $dryRun = false) {

        return $this->sm->executeSource($source, $output, $dryRun);
    }

    public function executeAllSources(OutputInterface $output, $dryRun = false) {

        return $this->sm->executeAllSources($output, $dryRun);
    }

    public function executeFilter(Filter $filter) {
        
        return $this->fm->executeFilter($filter);
    }

}
