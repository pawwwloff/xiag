<?php 

namespace app\service\request;

use vendor\core\service\AbstractProvider;
use vendor\core\http\Request;

class Provider extends AbstractProvider{
	
	/**
	 * 
	 * @var string
	 */
	public $serviceName = 'request';
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \vendor\core\service\AbstractProvider::init()
	 * @return mixed
	 */
	public function init(){
		
		$request = new Request();
		$this->di->set($this->serviceName, $request);
	}
}