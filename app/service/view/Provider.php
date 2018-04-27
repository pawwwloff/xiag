<?php 

namespace app\service\view;

use vendor\core\service\AbstractProvider;
use vendor\core\template\View;

class Provider extends AbstractProvider{
	
	/**
	 * 
	 * @var string
	 */
	public $serviceName = 'view';
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \vendor\core\service\AbstractProvider::init()
	 * @return mixed
	 */
	public function init(){
		
		$view = new View();
		$this->di->set($this->serviceName, $view);
	}
}