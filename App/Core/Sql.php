<?php

Class Sql{



    public static function Start(){


        define("DB_PASSWORD",   $_ENV['DB_PASSWORD']);
        define("DB_HOST",       $_ENV['DB_HOST']);
        define("BACKUP_DIR",    $_ENV['BACKUP_DIR']); // Comment this line to use same script's directory ('.')
        define("TABLES", '*'); // Full backup
        define("CHARSET", 'utf8');
        define("GZIP_BACKUP_FILE", false); // Set to false if you want plain SQL backup files (not gzipped)
        define("DISABLE_FOREIGN_KEY_CHECKS", true); // Set to true if you are having foreign key constraint fails
        define("BATCH_SIZE", 10000); // Batch size when selecting rows from database in order to not exhaust system memory

        error_reporting(E_ALL);
        // Set script max execution time
        set_time_limit(900); // 15 minutes

        if (php_sapi_name() != "cli") {
            // echo '<div style="font-family: monospace;">';
        }






        $backupDatabase = new Backup_Database(DB_HOST, $_ENV['DB_USERNAME'], DB_PASSWORD, $_ENV['DB_DATABASE'], CHARSET);
        $result = $backupDatabase->backupTables(TABLES, BACKUP_DIR) ? 'OK' : 'KO';
        //$backupDatabase->obfPrint('Backup result: ' . $result, 1);

        // Use $output variable for further processing, for example to send it by email
        //$output = $backupDatabase->getOutput();

        if (php_sapi_name() != "cli") {
            //  echo '</div>';
        }

        self::DellOldBackups();


    }



    public static function DellOldBackups(){


        $dir = "../SQL/backup/"; /** define the directory **/


        /*** cycle through all files in the directory ***/
        foreach (glob($dir."*") as $file):


            if (filemtime($file) < time() - 120) { // 2 days
                unlink($file);
            }

        endforeach;

    }



    public static function Update(){

        /**
         *  get data from database if posable if returns 1 backups and dells will work
         *
         */

        $data = 1;

        if($data == 1){

            self::Start();

        }

    }




}