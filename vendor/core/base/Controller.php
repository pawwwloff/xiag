<?php 

namespace vendor\core\base;

use vendor\core\DI;
use vendor\core\User;

abstract class Controller{
	protected $data = [];
	protected $param = [];
	protected $di;
	protected $request;
	protected $cookie;
	protected $view;
	protected $user;
	
	public function __construct(DI $di, $param){
		$this->param = $param;
		$this->di = $di;
		$this->request = $this->di->get('request');
		$this->cookie = $this->request->cookie;
		$this->view = $this->di->get('view');
		$this->user = new User($this->di);
	}
}