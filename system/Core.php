<?php

define('ROOT', $_SERVER['DOCUMENT_ROOT']);
define('SYSF', ROOT."/system/");
define('CLSF', SYSF."Classes/");
define('TMPF', SYSF."templates/");

require_once SYSF."Config.php";
require_once CLSF."MySQL.php";
require_once CLSF."Site.php";

$Site = new Site(Array(
    'host' => $config['mysql']['host'],
    'username' => $config['mysql']['user'],
    'password' => $config['mysql']['password'],
    'db' => $config['mysql']['database'],
    'port' => $config['mysql']['port'],
    'siteUrl' => $config['site']['url']
));

require_once ROOT.'/vendor/autoload.php';

session_start();

$googleClient = new Google_Client();
$googleClient->setClientId("177519179969-q9dr12m5qqtpbr95sn34eq93vf7j2ccd.apps.googleusercontent.com");
$googleClient->setClientSecret("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");
$googleClient->setRedirectUri("http://localhost/login");
$googleClient->addScope("email");
$googleClient->addScope("profile");

$imOnline = array_key_exists('user_id', $_SESSION);