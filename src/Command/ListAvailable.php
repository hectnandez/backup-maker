<?php
/**
 * Created by PhpStorm.
 * User: hectnandez
 * Date: 14/05/2018
 * Time: 11:01
 */

namespace BackupMaker\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use BackupMaker\Classes\ListAvailable AS ListClass;

class ListAvailable extends Command
{
    protected function configure()
    {
        $this->setName('backup-maker:list-sites')
            ->setDescription('List available FTP backup')
            ->setHelp('This command allows to list the available sites to create a complete backup FTP site with 
            an option to exclude some folders');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $list = new ListClass();
        $sitesAvailables = $list->getSitesAvailables();
        if(empty($sitesAvailables)){
            $output->writeln('No exist sites configured yet');
        } else {
            foreach ($sitesAvailables as $site) {
                $output->writeln('Alias: '.$site['alias']);
            }
        }
    }

}