<?php 

namespace app\controllers;

use vendor\core\base\Controller;
use app\models\Url;

class UrlController extends Controller{

	public function indexAction(){
		$url = new Url($this->di);
		echo $url->getUri();	
	}
}