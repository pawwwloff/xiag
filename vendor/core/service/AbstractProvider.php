<?php 

namespace vendor\core\service;

use vendor\core\DI;
abstract class AbstractProvider
{
	protected $di;
	
	public function __construct(DI $di){
		$this->di = $di;
	}
		
	abstract function init();
}