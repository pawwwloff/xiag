<?php 

namespace vendor\core;
use app\models\Url;
class Router{
	
	private $routes = array();
	private $route = array();
	
	public function add($reg, $route = array()){
		
		$reg = str_replace('{', '(?P<', $reg);
		$reg = str_replace('}', '>[a-z-0-9]+)', $reg);
		if(isset($route['group'])){
			$route['url'] = $reg;
			$reg = $route['group']."/".$reg;
		}
		$this->routes["^$reg$"] = $route;
	}
	
	private function match(){
		$url = $_SERVER['REQUEST_URI'];  
		if($position = strpos($url, '?')){
			$url = substr($url, 0, $position);
		}
		$url = ltrim(rtrim($url,'/'),"/");
		foreach ($this->routes as $patt => $route){
			if(preg_match("#$patt#i", $url, $matches)){
				
				foreach ($matches as $k => $v){
					if(is_string($k))
						$route[$k] = $v;
				}
				if(isset($route['url']) && !isset($route['controller'])){
					$data = explode('/', $route['url']);
					$route['controller'] = $data[0]?:'';
					if(!isset($route['action']) && isset($data[1])){
						$pos = strripos($data[1], '(?P');
						if ($pos !== false){
							$data[1] = '';
						}
						$route['action'] = $data[1]?:'index';
					}
				}
				if(!isset($route['action']))
					$route['action'] = 'index';
				$this->route = $route;
				
				return true;
			}
		}
		return false;
	}
	
	public function getRoutes(){
		return $this->routes;
	}
	
	public function dispatch($di){
		$method = $di->get('request')->server['REQUEST_METHOD'];
		$this->add('', array('controller'=>'Main', 'action'=>'index'));
		$this->add('(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?');
		if($this->match()){
			if(isset($this->route['method']) && strtoupper($this->route['method'])!=$method){
				http_response_code(404);
				echo 'Method not exist';
				exit();
			}
			$controller = $this->route['controller'].'Controller';
			unset($this->route['controller']);
			$controller = $this->controllerCase($controller);
			$controllerPath = isset($this->route['group'])? '\\app\\controllers\\'.$this->route['group']."\\" : '\\app\\controllers\\';
			$controller = $controllerPath.$controller;
			if(class_exists($controller)){
				$action = $this->route['action'].'Action';
				unset($this->route['action']);
				$obj = new $controller($di, $this->route);
				$action = $this->actionCase($action);
				if(method_exists($obj, $action)){
						$obj->$action();
				}else{
					http_response_code(404);
					echo 'Action not exist';
				}
			}else{
				$url = new Url($di);
				$url = $url->redirect();
				if(!$url){
					http_response_code(404);
					echo 'Controller not exist';
				}
			}
		}else{
			http_response_code(404);
			echo '404';
		}
	}
	
	private function controllerCase($name) {
		$name = str_replace('-', ' ', $name);
		$name = ucwords($name);
		$name = str_replace(' ', '', $name);
		return $name;
	}
	private function actionCase($name) {
		return lcfirst($this->controllerCase($name));
	}
}