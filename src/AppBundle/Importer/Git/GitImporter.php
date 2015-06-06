<?php

namespace AppBundle\Importer\Git;

use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Importer\Importer;

class GitImporter extends Importer
{

    public function run($argument, OutputInterface $output, $dryrun = false) {
        $storeFile = $this->storeCsv($argument);

        foreach(file($storeFile) as $line) {
            $datas = str_getcsv($line, ";", '"');

            $date = isset($datas[3]) ? $datas[3] : null;
            $title = isset($datas[4]) ? $datas[4] : null;
            $content = isset($datas[5]) ? $datas[5] : null;

            try {
                $activity = $this->am->fromArray(array(
                    'title' => $title,
                    'executed_at' => $date,
                    'content' => $content,
                ));

                if(!$dryrun) {
                    $this->em->persist($activity);
                    $this->em->flush($activity);
                }

                $output->writeln(sprintf("<info>Imported</info>;%s", str_replace("\n", "", $line)));
            } catch (\Exception $e) {
                $output->writeln(sprintf("<error>%s</error>;%s", $e->getMessage(), str_replace("\n", "", $line)));
            }
        }

        unlink($storeFile);
    }

    public function getRootDir() {

        return dirname(__FILE__);
    }

    public function check($argument) {
        parent::check($argument);

        if(!file_exists($argument)) {
            throw new \Exception(sprintf("Folder %s doesn't exist", $argument));
        }

        if(!file_exists($argument."/.git")) {
            throw new \Exception(sprintf("This folder isn't a git repository", $argument));
        }
    }

    protected function storeCsv($file) {
        $storeFile = sprintf("%s/var/commits_%s_%s.csv", dirname(__FILE__), date("YmdHis"), uniqid());
        
        shell_exec(sprintf("%s/bin/git2csv.sh %s > %s", dirname(__FILE__), $file, $storeFile));
    
        return $storeFile;
    }

} 