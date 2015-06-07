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

    public function run($argument, OutputInterface $output, $dryrun = false) {
        $output->writeln(sprintf("<comment>Started import mails in %s</comment>", $argument));

        $mail = null;
        $start = false;
        $nb = 0;
        $handle = fopen($argument, "r");

        while (($line = fgets($handle)) !== false) {
            if(preg_match('/^(From .?$|From - )/', $line)) {
                if($mail && $start) {
                    if($this->importMail($mail, $output, $dryrun)) { $nb++; }
                }
                $mail = null;
                $start = true;
                continue;
            }

            $mail .= $line;
        }

        if($mail && $start) {
            if($this->importMail($mail, $output, $dryrun)) { $nb++; }
        }

        fclose($handle);

        $output->writeln(sprintf("<info>%s new activity imported</info>", $nb));
    }

    protected function importMail($mail, OutputInterface $output, $dryrun = false) {
        try {
            $parsedMail = @$this->mailParser->parse($mail);
        } catch(\Exception $e) {
            if($output->isVerbose()) {
                $output->writeln("<error>Error ".$e->getMessage()."</error>");
            }
            
            return false;
        }

        //$addressesFrom = $parsedMail->getAllEmailAddresses(array('from'));
        //$addressesTo = $parsedMail->getAllEmailAddresses(array('to'));

        try {
            $subject = $parsedMail->getMail()->getHeaderField("Subject");
        } catch(\Exception $e) {
            $subject = null;
        }

        $date = $parsedMail->getMail()->getHeaderField("Date");

        $html2text = new Html2Text($parsedMail->getPrimaryContent());
        $body = $html2text->get_text();

        try {
            $activity = $this->am->fromArray(array(
                'title' => $subject,
                'executed_at' => $date,
                'content' => $body,
            ));

            if(!$dryrun) {
                $this->em->persist($activity);
                $this->em->flush($activity);
            }
            if($output->isVerbose()) {
                $output->writeln(sprintf("<info>Imported</info>;%s;%s;%s", $date, $subject, str_replace("\n", "", $body)));
            }
        } catch (\Exception $e) {
            if($output->isVerbose()) {
                $output->writeln(sprintf("<error>%s</error>;%s;%s;%s", $e->getMessage(), $date, $subject, str_replace("\n", "", $body)));
            }

            return false;
        }

        return true;
    }

    public function getRootDir() {

        return dirname(__FILE__);
    }

    public function check($argument) {
        parent::check($argument);

        if(!file_exists($argument)) {
            throw new \Exception(sprintf("File %s doesn't exist", $argument));
        }

        $line = "";
        $handle = fopen($argument, "r");
        for($i=1; $i<20; $i++) { $line .= fgets($handle); }
        fclose($handle);

        if(strpos($line, "Message-ID") === false) {
           throw new \Exception(sprintf("This file is not a mail file : %s", $argument)); 
        }
    }

} 