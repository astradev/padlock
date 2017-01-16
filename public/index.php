<?php
/*
   Padlock
*/

$f3 = require( '../lib/base.php' );

$f3->config('../config/config.ini');

$f3->route( 'GET /', 'Controller\Test->main' );

$f3->run();
