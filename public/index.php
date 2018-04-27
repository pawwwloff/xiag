<?php 
define('ROOT_DIR', __DIR__.'/../');
define('APP_DIR', __DIR__.'/../app/');
require_once (__DIR__."/../vendor/autoload.php");
require_once (__DIR__."/../vendor/core/dev_function.php");

use app\App;
use vendor\core\DI;

try{
	//Dependency injection
	$di = new DI();
	
	$services = require __DIR__ . '/../config/Service.php';
	
	foreach ($services as $service){
		$provider = new $service($di);
		$provider->init();
	}
	
	$app = new App($di);
	$app->run();
}catch (\ErrorException $e){
	echo $e->getMessage();
}
