<?php

namespace AppBundle\Importer\Git;

use Symfony\Component\Console\Output\OutputInterface;

class Importer
{
    protected $am;
    protected $em;

    public function __construct($am, $em)
    {
        $this->am = $am;
        $this->em = $em;
    }

    public function run($argument, OutputInterface $output) {
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
                $this->em->persist($activity);
                $this->em->flush($activity);

                $output->writeln(sprintf("<info>Imported</info>;%s", str_replace("\n", "", $line)));
            } catch (\Exception $e) {
                $output->writeln(sprintf("<error>%s</error>;<comment>%s</comment>", $e->getMessage(), str_replace("\n", "", $line)));
            }
        }

        unlink($storeFile);
    }

    protected function storeCsv($file) {
        $storeFile = sprintf("%s/var/commits_%s_%s.csv", dirname(__FILE__), date("YmdHis"), uniqid());
        
        shell_exec(sprintf("%s/bin/git2csv.sh %s > %s", dirname(__FILE__), $file, $storeFile));
    
        return $storeFile;
    }
} 