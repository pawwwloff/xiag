<?php 

namespace app\service\routing;

use vendor\core\service\AbstractProvider;
use vendor\core\Router;

class Provider extends AbstractProvider{
	
	/**
	 * 
	 * @var string
	 */
	public $serviceName = 'router';
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \vendor\core\service\AbstractProvider::init()
	 * @return mixed
	 */
	public function init(){
		$router = new Router();
		
		$routes = require $_SERVER['DOCUMENT_ROOT'].'/../app/Routing.php';
		foreach ($routes as $key=>$value){
			$router->add($key, $value);
		}
		$this->di->set($this->serviceName, $router);
	}
}