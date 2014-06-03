<?php

date_default_timezone_set('America/Detroit');

require_once('../vendor/autoload.php');
require_once('mhApp.php');

$app = new mhApp();

$app->router->defineRoute(new route('upload','uploadController'));
$app->router->defineRoute(new route('sendjob', 'sendjobController'));

?>