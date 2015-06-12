<?php 

namespace AppBundle\Importer;

use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Entity\Source;

class ImporterManager
{
    protected $importers = null;

    public function __construct($importers) {
        $this->importers = array();
        foreach($importers as $importer) {
            $this->importers[$importer->getName()] = $importer;
        }
    }

    public function execute($importerName, $source, $sourceName, OutputInterface $output, $dryRun = false) {
        $importer = $this->get($importerName);

        $importer->check($source);
        $importer->run($source, $sourceName, $output, $dryRun);
    }

    public function get($name) {
        if(!isset($this->importers[$name])) {

            throw new \Exception(sprintf("Importer %s doesn't exist", $name));
        }

        return $this->importers[$name];
    }
}