<?php 

namespace app\service\database;

use vendor\core\service\AbstractProvider;
use vendor\core\database\Connection;

class Provider extends AbstractProvider{
	
	/**
	 * 
	 * @var string
	 */
	public $serviceName = 'db';
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \vendor\core\service\AbstractProvider::init()
	 * @return mixed
	 */
	public function init(){
		
		$db = Connection::instance();
		$this->di->set($this->serviceName, $db);
	}
}