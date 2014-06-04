<?php

ini_set('display_errors', '1');

date_default_timezone_set('America/Detroit');

require_once('../vendor/autoload.php');
require_once('mhApp.php');


//configure reply to and from addresses for emails sent out
$replyTo = mailerFactory::createAddress('info@clevelandconsulting.com', 'Information');
$from = mailerFactory::createAddress('kevin@clevelandconsulting.com','Mailer');
$to = mailerFactory::createAddress('kevinvile@gmail.com','The Exalted One');

//configure the smtp settings
$smtpConfig = mailerFactory::createSMTPConfig(
                                'mail.clevelandconsulting.com',  //HOST
                                true,                            //USE AUTHORIZATION
                                'kevin@clevelandconsulting.com', //USERNAME
                                'C00kie22',                      //PASSWORD
                                'tls',                           //SECURITY METHOD (tls or ssl or empty)
                                $from,
                                $replyTo,
                                $to,
                                50);                             //Word Wrapping Length


$app = new mhApp($smtpConfig);

$app->router->defineRoute(new route('upload','uploadController'));
$app->router->defineRoute(new route('sendjob', 'sendjobController'));

?>