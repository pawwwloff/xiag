<?php

namespace app;

class App{
	/**
	 * 
	 * @var DI
	 */
	private $di;
	private $router;
	private $db;
	
	/**
	 * application constructor
	 * @param $di
	 */
	public function __construct($di){
		$this->di = $di;
		$this->router = $this->di->get('router');
		$this->db = $this->di->get('db');
	}
	
	/**
	 * run application
	 */
	public function run(){
		try {
			$this->router->dispatch($this->di);
		}catch (\Exception $e){
			$e->getMessage();
			exit;
		}
	}
}