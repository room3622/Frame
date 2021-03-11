<?php


class Render
{


    public static function Json($data = null)
    {
        header('Content-Type: application/json, text/javascript');
        echo json_encode($data);
        exit();
    }


    public static function viewsNoexit($file = null, $data = null)
    {


        $file_dir = Sys() . 'Viwes/' . $file . '.php';
        if (file_exists($file_dir)) {

            include($file_dir);

        } else {
            echo "<div align='center'>
    <h1>This File <b style='color: red'>" . ucfirst($file) . "</b> that don't exist</h1><br/>
    <h2>On <b style='color: red' >Viwes</b></h2>
</div>";
            exit();
        }


    }



    public static function views($file = null, $data = null, $scrips= null)
    {

        $data = (object) $data;

        $file_dir = Sys() . 'Viwes/' . $file . '.php';
        $file_dir2 = Sys() . 'Viwes/' . $scrips . '.php';

        if (file_exists($file_dir)) {

            include($file_dir);

            if($scrips){

                include($file_dir2);

            }


            exit();

        } else {
            echo "<div align='center'>
    <h1>This File <b style='color: red'>" . ucfirst($file) . "</b> that don't exist</h1><br/>
    <h2>On <b style='color: red' >Viwes</b></h2>
</div>";
            exit();
        }


    }


}