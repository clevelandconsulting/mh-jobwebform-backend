<?php

require_once(__DIR__ . '/../app/app.php');

if ($app->devMode) {
 //echo "<pre><code>";
 //print_r($app->router);
 //echo "</code></pre>";
}

$app->run();

?>