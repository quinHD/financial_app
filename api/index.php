<?php
    require 'Slim/Slim.php';

    \Slim\Slim::registerAutoloader();

    $app = new \Slim\Slim();

    require 'app/libs/connect.php';
    require 'app/routes/api.php';

    $app -> run ();
?>