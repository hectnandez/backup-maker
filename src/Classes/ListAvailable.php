<?php
/**
 * Created by PhpStorm.
 * User: hectnandez
 * Date: 14/05/2018
 * Time: 11:01
 */

namespace BackupMaker\Classes;


use BackupMaker\Util\Util;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class ListAvailable
{
    private $fs;
    private $sitesAvailable;

    /**
     * ListAvailable constructor.
     */
    public function __construct()
    {
        $this->fs = new Filesystem();
        $this->sitesAvailable = $this->loadSites();
        return $this;
    }

    /**
     * @param null|string $alias
     * @return array|bool|mixed
     */
    public function getSitesAvailables($alias = null){
        if(empty($alias)){
            return $this->sitesAvailable;
        }
        if(array_key_exists($alias, $this->sitesAvailable)){
            return $this->sitesAvailable[$alias];
        }
        return false;
    }

    /**
     * @return array
     */
    private function loadSites(){
        $sitesAvailables = array();
        $dir = Util::getDir(DIR_CONFIG);
        foreach (Finder::create()->files()->in($dir)->name('*.json') as $fileConfig){
            try{
                /**
                 * @var $fileConfig SplFileInfo
                 */
                $data = json_decode(file_get_contents($fileConfig->getPathname()), true);
                $sitesAvailables[$data['alias']] = $data;
            } catch(\Exception $ex){
                var_dump($fileConfig);
            }
        }
        return $sitesAvailables;
    }
}