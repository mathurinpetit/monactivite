<?php

namespace AppBundle\Importer\Mail;

use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Importer\Importer;
use Html2Text\Html2Text;

class MailImporter extends Importer
{
    protected $mailParser;

    public function __construct($am, $em, $mailParser)
    {
        parent::__construct($am, $em);

        $this->mailParser = $mailParser;
    }

    public function run($source, $sourceName = null, OutputInterface $output, $dryrun = false) {
        $output->writeln(sprintf("<comment>Started import mails in %s</comment>", $source));

        $mail = null;
        $start = false;
        $nb = 0;
        $handle = fopen($source, "r");

        while (($line = fgets($handle)) !== false) {
            if(preg_match('/^(From .?$|From - )/', $line)) {
                if($mail && $start) {
                    if($this->importMail($mail, $source, $sourceName, $output, $dryrun)) { $nb++; }
                }
                $mail = null;
                $start = true;
                continue;
            }

            $mail .= $line;
        }

        if($mail && $start) {
            if($this->importMail($mail, $source, $sourceName, $output, $dryrun)) { $nb++; }
        }

        fclose($handle);

        $output->writeln(sprintf("<info>%s new activity imported</info>", $nb));
    }

    protected function importMail($mail, $source, $sourceName, OutputInterface $output, $dryrun = false) {
        try {
            $parsedMail = @$this->mailParser->parse($mail);
        } catch(\Exception $e) {
            if($output->isVerbose()) {
                $output->writeln("<error>Error ".$e->getMessage()."</error>");
            }
            
            return false;
        }

        try {
            $subject = $parsedMail->getMail()->getHeaderField("Subject");
        } catch(\Exception $e) {
            $subject = null;
        }

        $date = $parsedMail->getMail()->getHeaderField("Date");
        $author = $parsedMail->getMail()->getHeaderField("From");

        $destination = null;
        foreach($parsedMail->getAllEmailAddresses(array('to')) as $address) {
            $destination = $address;
        }

        $html2text = new Html2Text($parsedMail->getPrimaryContent());
        $body = $html2text->get_text();

        try {
            $activity = $this->am->fromArray(array(
                'title' => $subject,
                'author' => $author,
                'executed_at' => $date,
                'content' => $body,
                'destination' => $destination,
                'source' => sprintf("%s <%s>", $sourceName, $source),
            ));

            if(!$dryrun) {
                $this->em->persist($activity);
                $this->em->flush($activity);
            }
            if($output->isVerbose()) {
                $output->writeln(sprintf("<info>Imported</info>;%s;%s", $date, $subject));
            }
        } catch (\Exception $e) {
            if($output->isVerbose()) {
                $output->writeln(sprintf("<error>%s</error>;%s;%s", $e->getMessage(), $date, $subject));
            }

            return false;
        }

        return true;
    }

    public function getRootDir() {

        return dirname(__FILE__);
    }

    public function check($source) {
        parent::check($source);

        if(!file_exists($source)) {
            throw new \Exception(sprintf("File %s doesn't exist", $source));
        }

        $line = "";
        $handle = fopen($source, "r");
        for($i=1; $i<20; $i++) { $line .= fgets($handle); }
        fclose($handle);

        if(strpos($line, "Message-ID") === false) {
           throw new \Exception(sprintf("This file is not a mail file : %s", $source)); 
        }
    }

} 