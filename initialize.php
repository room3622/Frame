<?php


ob_start();

function Sys()
{
    $path = __DIR__ . "/";
    $sys = $path . "App/";
    return $sys;
}

function Composer()
{
    $Composer = Sys() . "Composer/vendor/autoload.php";
    if (file_exists($Composer)) {
        require_once $Composer;

    }
}

Composer();


spl_autoload_register("loader");

function loader($classname)
{

    $exe = ".php";
    $fullpath1 = Sys() . "Controllers/" . $classname . $exe;
    $fullpath2 = Sys() . "Core/" . $classname . $exe;

    if (file_exists($fullpath1)) {

        require $fullpath1;

    } elseif (file_exists($fullpath2)) {


        require $fullpath2;

    } else {

        return false;
    }


}



function Devent(){

    $Devent =  __DIR__ . "/.env";


    if(!file_exists($Devent)){


        //$homepage = file_get_contents(__DIR__ . '/.env.example');

        $myfile = fopen($Devent, "w") or die("Unable to open file!");
        $txt = file_get_contents(__DIR__. '/.env.example', true);
        fwrite($myfile, $txt);
        fclose($myfile);

    }



}

Devent();

use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');



$session = new Session();



//Sql Backups
$Sql = Sql::Update();



if ($session->is_logged_in()) {
    $session->user_id;
}

    if (isset($_GET['module'])) {
        $file_dir = Sys() . 'Modules/' . ucfirst($_GET['module']) . '.php';


        if (file_exists($file_dir)) {
            include $file_dir;
        } else {
            echo "<div align='center'>
    <h1>This Module <b style='color: red'>" . ucfirst($_GET['module']) . "</b> that don't exist</h1>
</div>";
            exit();
        }
    }






function redirect_to( $location = NULL ) {
    if ($location != NULL) {
        header("Location: {$location}");
        exit;
    }
}




ob_end_flush();