<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require __DIR__.'/vendor/autoload.php';
define('ROOT',__DIR__);
define('HMVC',__DIR__.'/hmvc');
use gaucho\env;
$env=new env();
$env->load(__DIR__.'/.env');
