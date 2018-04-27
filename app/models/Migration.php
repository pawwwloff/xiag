<?php 

namespace app\models;

use vendor\core\base\Model;
//use database\migrations;

class Migration extends Model{
	private static $dir = '/../app/migrations/';
	public function startMigration() {
		if ($files = scandir($_SERVER['DOCUMENT_ROOT'].self::$dir)) {
		    foreach ($files as $file){
		    	if(is_file($_SERVER['DOCUMENT_ROOT'].self::$dir.$file)){
		    		$migrationName = "app\migrations\\".(basename($_SERVER['DOCUMENT_ROOT'].self::$dir.$file, '.php'));
		    		if(class_exists($migrationName)){
		    			$migration = new $migrationName;
			    		if(method_exists($migration, 'up')){
							$migration->up();
						}
		    		}
		    	}
		    }
		}
	}
	
	public function stopMigration() {
		if ($files = scandir($_SERVER['DOCUMENT_ROOT'].self::$dir)) {
			foreach ($files as $file){
				if(is_file($_SERVER['DOCUMENT_ROOT'].self::$dir.$file)){
					$migrationName = "app\migrations\\".(basename($_SERVER['DOCUMENT_ROOT'].self::$dir.$file, '.php'));
					if(class_exists($migrationName)){
						$migration = new $migrationName;
						if(method_exists($migration, 'down')){
							$migration->down();
						}
					}
				}
			}
		}
	}
	
}