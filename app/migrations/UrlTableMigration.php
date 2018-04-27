<?php 

namespace app\migrations;

use vendor\core\base\Migration;

class UrlTableMigration extends Migration{

	public function up()
	{
		$this->int('id')->notnull('id')->increment('id')->primary('id');
		$this->varchar('longUrl', 50);
		$this->varchar('shortUrl', 10);
		$this->createTable('url', $this->data);
	}
	
	public function down()
	{
		$this->dropTable('url');
	}
	
}