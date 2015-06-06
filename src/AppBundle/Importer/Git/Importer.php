<?php

namespace AppBundle\Importer\Git;

class Importer
{
    protected $am;
    protected $em;

    public function __construct($am, $em)
    {
        $this->am = $am;
        $this->em = $em;
    }

    public function run($argument) {
        echo $this->getCsv($argument);
    }

    protected function getCsv($file) {

        return shell_exec(sprintf("%s/bin/git2csv.sh %s", dirname(__FILE__), $file));
    }
} 