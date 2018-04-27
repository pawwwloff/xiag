<?php 

namespace app\controllers;

use vendor\core\base\Controller;

class MainController extends Controller{

	public function indexAction(){
			$this->view->render('index.html', array('title'=>'Главная страница', 'data'=>$this->data, 'query'=>$this->di->get('db')->getQuery()));
	}
}