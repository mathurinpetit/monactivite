<?php

namespace AppBundle\Importer\Feed;

use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Importer\Importer;

class FeedImporter extends Importer
{
    protected $feedParser;

    public function __construct($am, $em, $feedParser)
    {
        parent::__construct($am, $em);

        $this->feedParser = $feedParser;
    }

    public function run($argument, OutputInterface $output, $dryrun = false) {
        $resource = $this->feedParser->download('http://24eme.fr/test.xml');

        $parser = $this->feedParser->getParser(
            $resource->getUrl(),
            $resource->getContent(),
            $resource->getEncoding()
        );

        $feed = $parser->execute();

        foreach($feed->getItems() as $item) {
            try {
                $activity = $this->am->fromArray(array(
                    'title' => $item->getTitle(),
                    'executed_at' => $item->getDate(),
                    'content' => $item->getContent(),
                ));

                if(!$dryrun) {
                    $this->em->persist($activity);
                    $this->em->flush($activity);
                }

                $output->writeln(sprintf("<info>Imported</info>;%s", str_replace("\n", "", $item)));
            } catch (\Exception $e) {
                $output->writeln(sprintf("<error>%s</error>;%s", $e->getMessage(), str_replace("\n", "", $item)));
            }
        }
    }

    public function getRootDir() {

        return dirname(__FILE__);
    }

    public function check($argument) {
        parent::check($argument);

    }

} 