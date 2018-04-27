<?php 

namespace app\controllers;

use app\models\Migration;
use vendor\core\base\Controller;

class MigrationController extends Controller{
	
	public function indexAction(){
		
		$migrations = new Migration($this->di);
		$migrations->startMigration();
	}
	
	public function dropAction(){
		$migrations = new Migration($this->di);
		$migrations->stopMigration();
	}
	
}