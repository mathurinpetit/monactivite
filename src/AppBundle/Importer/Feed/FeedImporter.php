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
        $output->writeln(sprintf("<comment>Started import feed %s</comment>", $argument));

        $resource = $this->feedParser->download($argument);

        $parser = $this->feedParser->getParser(
            $resource->getUrl(),
            $resource->getContent(),
            $resource->getEncoding()
        );

        $feed = $parser->execute();
        
        $nb = 0;
        
        foreach($feed->getItems() as $item) {
            $author = $item->getAuthor() ? $item->getAuthor() : null;
            
            try {
                $activity = $this->am->fromArray(array(
                    'title' => $item->getTitle(),
                    'executed_at' => $item->getDate(),
                    'content' => $item->getContent(),
                    'author' => $author,
                    'destination' => null,
                ));


                if(!$dryrun) {
                    $this->em->persist($activity);
                    $this->em->flush($activity);
                }

                $nb++;

                if($output->isVerbose()) {
                    $output->writeln(sprintf("<info>Imported</info>;%s;%s", $item->getDate()->format('c'), $item->getTitle()));
                }

            } catch (\Exception $e) {
                if($output->isVerbose()) {
                    $output->writeln(sprintf("<error>%s</error>;%s;%s", $e->getMessage(), $item->getDate()->format('c'), $item->getTitle()));
                }
            }

        }
        
        $output->writeln(sprintf("<info>%s new activity imported</info>", $nb));
    }

    public function getRootDir() {

        return dirname(__FILE__);
    }

    public function check($argument) {
        parent::check($argument);

        try {
            $resource = $this->feedParser->download($argument);
            $parser = $this->feedParser->getParser($resource->getUrl(), $resource->getContent(), $resource->getEncoding());
        } catch(\Exception $e) {

            throw new \Exception(sprintf("Feed Url %s isn't valid : %s", $argument, $e->getMessage()));
        }
    }

} 