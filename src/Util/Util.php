<?php
/**
 * Created by PhpStorm.
 * User: hectnandez
 * Date: 14/05/2018
 * Time: 11:14
 */

namespace BackupMaker\Util;


use Symfony\Component\Filesystem\Filesystem;

class Util
{
    /**
     * @param string $basename
     * @return bool
     */
    public static function isSpecialFile($basename){
        $specialsFolders = array(
            'LICENCE'
        );
        if(in_array($basename, $specialsFolders)){
            return true;
        }
        return false;
    }

    /**
     * @param array $listBackup
     * @return int
     */
    public static function countFilesToDownload($listBackup){
        $progressCount = 0;
        foreach ($listBackup as $files){
            $progressCount += count($files);
        }
        return $progressCount;
    }

    /**
     * @param string $dir
     * @return bool|string
     */
    public static function getDir($dir){
        $fs = new Filesystem();
        if(!$fs->exists($dir)){
            $fs->mkdir($dir);
        }
        return realpath($dir);
    }
}