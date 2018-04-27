<?php 

namespace vendor\core\template;

class View{
	private $params = array(
			//'cache' => $_SERVER['DOCUMENT_ROOT'].'/../cache',
	);
	private $twig;
	private $errors = [];
	public function __construct(){
		$loader = new \Twig_Loader_Filesystem($_SERVER['DOCUMENT_ROOT'].'/../app/views');
		$this->twig = new \Twig_Environment($loader, $this->params);
	}
	
	/**
	 * 
	 * @param $template
	 * @param array $vars
	 * @throws \InvalidArgumentException
	 * @throws Exception
	 */
	public function render($template, $vars = []){
		$vars['errors'] = $this->getErrorBlock();
		echo $this->twig->render($template, $vars);
	}
	
	public function setError($value){
		$this->errors[] = $value;
		return $this;
	}
	
	public function getErrors(){
		return $this->errors;
	}
	
	public function getErrorBlock(){
		return implode('</br>', $this->errors);
	}
}