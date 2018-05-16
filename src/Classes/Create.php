<?php
/**
 * Created by PhpStorm.
 * User: hectnandez
 * Date: 14/05/2018
 * Time: 10:57
 */

namespace BackupMaker\Classes;


use BackupMaker\Util\Util;

class Create
{
    private $config;
    private $ftpConnection;
    private $ftpUsername;
    private $ftpPassword;

    public function __construct($config, $ftpUsername, $ftpPassword)
    {
        $this->config = $config;
        $this->ftpUsername = $ftpUsername;
        $this->ftpPassword = $ftpPassword;
        return $this;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function executeBackupFtp(){
        $this->openFtpConnection();
        $destinationDir = DIR_BACKUP.DIRECTORY_SEPARATOR.$this->config['destination']['path'];
        $destinationDir .= DIRECTORY_SEPARATOR.date($this->config['destination']['date_pattern']);
        $destinationDir = Util::getDir($destinationDir);
        $listBackup = $this->ftpListRecursive(
            $this->ftpConnection,
            $this->config['origin']['path'],
            $this->config['origin']['not_folders']
        );
        foreach ($listBackup as $dir => $files){
            if($dir === '/'){
                $remoteDir = $dir;
               $localDir = $destinationDir;
            } else {
                $remoteDir = $dir.'/';
                $localDir = Util::getDir($destinationDir.$dir);
            }
            foreach ($files as $file){
                $remoteDirPath = $remoteDir.$file;
                $localDirPath = $localDir.DIRECTORY_SEPARATOR.$file;
                ftp_get($this->ftpConnection, $localDirPath, $remoteDirPath, FTP_BINARY);
            }
        }
        $this->closeFtpConnection();
        return true;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    private function openFtpConnection(){
        $this->ftpConnection = ftp_connect($this->config['host'], $this->config['port']);
        if($this->ftpConnection === false){
            throw new \Exception('Uneable to connect to Ftp Server');
        }
        if(!ftp_login($this->ftpConnection, $this->ftpUsername, $this->ftpPassword)){
            throw new \Exception('Uneable to login in the server');
        }
        ftp_pasv($this->ftpConnection, true);
        return true;
    }

    /**
     * @param $ftpConnection
     * @param $path
     * @param null $notFolders
     * @return array
     */
    private function ftpListRecursive($ftpConnection, $path, $notFolders = null) {
        $allFiles = array();
        $contents = ftp_mlsd($ftpConnection, $path);
        foreach($contents as $currentFile) {
            if($currentFile['name'] == '.' || $currentFile['name'] == '..'){
                continue;
            }
            if($currentFile['type'] == 'dir'){
                if($path === '/'){
                    $newPath = $path.$currentFile['name'];
                } else{
                    $newPath = $path.'/'.$currentFile['name'];
                }
                if(is_array($notFolders) && in_array($newPath, $notFolders)){
                    continue;
                }
                $allFiles = array_merge($allFiles, self::ftpListRecursive($ftpConnection, $newPath, $notFolders));
            } elseif($currentFile['type'] === 'file') {
                $allFiles[$path][] = $currentFile['name'];
            }
        }
        return $allFiles;
    }

    /**
     * @return bool
     */
    private function closeFtpConnection(){
        ftp_close($this->ftpConnection);
        return true;
    }

}