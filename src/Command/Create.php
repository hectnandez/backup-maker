<?php
/**
 * Created by PhpStorm.
 * User: hectnandez
 * Date: 14/05/2018
 * Time: 10:33
 */

namespace BackupMaker\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use BackupMaker\Classes\Create as CreateClass;
use BackupMaker\Classes\ListAvailable AS ListAvailableClass;


class Create extends Command
{
    protected function configure()
    {
        $this->setName('backup-maker:create')
        ->setDescription('Create a FTP backup')
        ->setHelp('This command allows to create a complete backup FTP site with an option to exclude 
        some folders')
        ->addOption('alias', 'a', InputOption::VALUE_REQUIRED, 'The config site alias.', false)
        ->addOption('username', 'u',InputOption::VALUE_REQUIRED, 'The Ftp\'s User.', false)
        ->addOption('password', 'p',InputOption::VALUE_REQUIRED, 'The Ftp\'s password.', false);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $list = new ListAvailableClass();
        /**
         * Check the options
         */
        $alias = $input->getOption('alias');
        if(empty($alias)){
            $output->writeln('<comment>You have to specified the alias</comment>');
            die();
        }
        $username = $input->getOption('username');
        if(empty($username)){
            $output->writeln('<comment>You have to specified the Ftp\'s User</comment>');
            die();
        }
        $password = $input->getOption('password');
        if(empty($password)){
            $output->writeln('<comment>You have to specified the Ftp\'s Password</comment>');
            die();
        }
        try{
            /**
             * Show the list of the sites available
             */
            $siteConfig = $list->getSitesAvailables($alias);
            if($siteConfig == false){
                $output->writeln('<question>The alias that you entered is isn\'t found</question>');
                die();
            }
            /**
             * Excute the backup
             */
            $create = new CreateClass($output, $siteConfig, $username, $password);
            if($create->executeBackupFtp()){
                $output->writeln('<info>finalizado</info>');
            } else {
                $output->writeln('<error>Problemas para terminar el backup</error>');
            }
        } catch (\Exception $ex){
            $output->writeln('<errror>ERROR: '.$ex->getMessage().' | File: '.$ex->getFile().' Line: '.$ex->getLine().'</errror>');
            die();
        }
    }
}