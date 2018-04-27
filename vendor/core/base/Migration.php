<?php 

namespace vendor\core\base;

use vendor\core\database\Connection as Db;

abstract class Migration{
	
	protected $pdo;
	protected $data = array();
	
	public function __construct(){
		$this->pdo = Db::instance();
	}
	
	protected function increment($name){
		if(isset($this->data[$name]))
			$this->data[$name] .= " AUTO_INCREMENT";
		else
			$this->data[$name] = " AUTO_INCREMENT";
		return $this;
	}
	
	protected function primary($name){
		if(isset($this->data["PRIMARY KEY"]))
			return $this;
		else
			$this->data["PRIMARY KEY"] = "$name";
		return $this;
	}
	protected function foreignKey($name, $table, $field = 'id'){
		$this->data["FOREIGN KEY"][] = "($name) REFERENCES `$table`($field)";
		return $this;
	}
	
	protected function notnull($name){
		if(isset($this->data[$name]))
			$this->data[$name] .= " NOT NULL";
		else
			$this->data[$name] = " NOT NULL";
		return $this;
	}
	
	protected function varchar($name, $strln='255'){
		if(isset($this->data[$name]))
			$this->data[$name] .= " varchar($strln)";
		else
			$this->data[$name] = " varchar($strln)";
		return $this;
	}
	
	protected function enum($name, $values = NULL){
		if(is_array($values) && !empty($values)){
			$values = implode(', ', $values);
			if(isset($this->data[$name]))
				$this->data[$name] .= ", size ENUM($values)";
				else
					$this->data[$name] = " NOT NULL";
					return $this;
		}
		return $this;
	}
	
	protected function default_value($name, $default){
		if(isset($this->data[$name]))
			$this->data[$name] .= " default '$default'";
		else
			$this->data[$name] = " default '$default'";
		return $this;
	}
	
	protected function text($name){
		if(isset($this->data[$name]))
			$this->data[$name] .= " TEXT";
			else
				$this->data[$name] = " TEXT";
				return $this;
	}
	
	protected function int($name){
		if(isset($this->data[$name]))
			$this->data[$name] .= " int";
		else
			$this->data[$name] = " int";
		return $this;
	}
	

	protected function createTable($table, $data){
		$str = $this->prepareData($data);
		$sql = "CREATE TABLE `$table` (".$str.");";
		if($this->pdo->execute($sql)){
			echo "create table $table</br>";
		}
	}
	
	protected function dropTable($table){
		$sql = "DROP TABLE IF EXISTS `$table`";
		if($this->pdo->execute($sql)){
			echo "drop table $table</br>";
		}
	}
	
	private function prepareData($data){
		$str = '';
		foreach ($data as $key=>$val)
			if($key!="PRIMARY KEY" && $key!="FOREIGN KEY")
				$str .= $key.$val.', ';
		if(isset($data["PRIMARY KEY"])){
			$str .= " PRIMARY KEY ({$data['PRIMARY KEY']}), ";
			$substr = false;
		}
		if(isset($data["FOREIGN KEY"])){
			foreach ($data["FOREIGN KEY"] as $foreign){
				$str .= " FOREIGN KEY {$foreign}, ";
			}
			$substr = false;
		}

		$str = substr($str, 0, -2);

		return $str;
	}
}