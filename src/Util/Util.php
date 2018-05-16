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
    public static function getDir($dir){
        $fs = new Filesystem();
        if(!$fs->exists($dir)){
            $fs->mkdir($dir);
        }
        return $dir;
    }
}