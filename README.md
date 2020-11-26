# PHP backup maker
PHP console apllication to make backup's via FTP with a minimal configuration file required.

## Install aplication
1. Clone the reposotiry:
    ```
    git clone https://github.com/hectnandez/backup-maker.git
    ```
2. Go to the root directory of the proyect and install composer dependencies:
    ```
    composer install
    ```

## How to use?
Open your console, go to the root directory of the proyect and type:
```
php console backup-maker:create -a name-of-the-site -u ftp-username -p ftp-password
```
By default all sites configuration must be found in the folder 'config' in the root of the proyect, and, all backups 
made will go to a folder outside of the project called 'backup'. 

Also yo can change the rutes of the directories in the console file, found in the root of the project.
```
/**
 * Directories condigurations path's
 */
define('DIR_CONFIG', __DIR__.DIRECTORY_SEPARATOR.'config');
define('DIR_BACKUP', dirname(__DIR__).DIRECTORY_SEPARATOR.'backups');
```

###### Template of the sites.json configuration file
```
{
    "alias": "www.example.com",
    "host": "8.8.8.8",
    "port":	21,
    "destination":
    {
        "path": "name_of_the_proyect",
        "date_pattern": "YmdH" --> date format
    },
    "origin":
    {
        "path": "/", --> root path of the FTP or you custom path
        "not_folders": --> folder to advoid in the backup
        [
            "/src/config",
            "/cgi-bin",
            "/logs"
        ]
    }
}
```
