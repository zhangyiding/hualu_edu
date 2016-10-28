<?php
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');
define('ECAR_TP_PATH' , __DIR__);
define('APP_PATH', ECAR_TP_PATH . '/Application/');
define('__PUBLIC__','__ROOT__/public/');
//define('HOME_PUBLIC','__ROOT__/public/home/');
header("Content-type: text/html; charset=utf-8");
//引入composer 包管理文件
//require_once './vendor/autoload.php';

//处理环境变量文件
//$dotenv = new Dotenv\Dotenv(__DIR__);
//$dotenv->load();
define('APP_DEBUG',true);
define('APP_STATUS','config_db');
header("Access-Control-Allow-Origin: *");
require_once '../framework/ThinkPHP/ThinkPHP.php';
?>
 
