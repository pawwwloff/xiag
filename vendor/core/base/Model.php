<?php 

namespace vendor\core\base;

//use vendor\core\database\Connection as Db;

abstract class Model{
	
	protected $pdo;
	protected $error;
	protected $table;
	protected $di;
	
	public function __construct($di){
		$this->pdo = $di->get('db');
		$this->error = $di->get('view');
		$this->di = $di;
	}
	
	public function query($sql){
		return $this->pdo->execute($sql);
	}
	
	public function findAll($order = null){
		if(isset($_GET['name'])){
			return $this->findLike($_GET['name'], 'name');
		}else{
			$sql = "SELECT * FROM {$this->table}";
			if($order){
				$sql .= " ORDER BY {$order['by']} {$order['sort']}";
			}
			return $this->pdo->query($sql);
		}
	}
	
	public function findOne($str, $field, $per = false, $table = ''){
		$table = $table ?: $this->table;
		$sql = "SELECT * FROM $table WHERE $field LIKE ? LIMIT 1";
		if($per){
			$data = $this->pdo->query($sql, ["%$str%"]);	
		}else{
			$data = $this->pdo->query($sql, [$str]);
		}
		return !empty($data)? $data[0] : null;
	}
	
	public function findLike($str, $field, $per = true, $table = ''){
		$table = $table ?: $this->table;
		$sql = "SELECT * FROM $table WHERE $field LIKE ?";
		if($per)
			return $this->pdo->query($sql, ["%$str%"]);
		else
			return $this->pdo->query($sql, [$str]);
	}
	
	public function findAllByFilter($filter = []){
		$str = $this->pdo->pdoSet($filter, $values, ' && ');
		$sql = "SELECT * FROM {$this->table}";
		if($str){
			$sql.=" WHERE $str";
		}
		return $this->pdo->query($sql, $values);
	}
	
	public function findOneByFilter($filter = [], $delimiter=" && ", $per = false, $table = ''){
		$str = $this->pdo->pdoSet($filter, $values, $delimiter);
		$sql = "SELECT * FROM {$this->table}";
		if($str){
			$sql.=" WHERE $str";
		}
		$sql.=" LIMIT 1";
		$data = $this->pdo->query($sql, $values);
		return !empty($data)? $data[0] : null;
	}
	
	public function findIn($values, $field, $table = ''){
		$table = $table ?: $this->table;
		$sql = "SELECT * FROM $table WHERE $field IN (";
		for ($i=0;$i<count($values);$i++){
			$sql .= "?,";
		}
		$sql = substr($sql, 0, -1);
		$sql .= ")";
		return $this->pdo->query($sql, $values);
	}
	
	public function rndm($field = 'id'){
		$sql = "SELECT $field FROM {$this->table} ORDER BY RAND() LIMIT 1";
		$data = $this->pdo->query($sql);
		if($data)
			return $data[0][$field];
		else
			return null;
	}
	
	public function into($data, $ret = false){
		$str = $this->pdo->pdoSet($data, $values);
		$sql = "INSERT INTO {$this->table} SET $str";
		return $this->pdo->execute($sql, $values, $ret);
	}
	
	public function changeBy($data, $val, $row='id', $ret = false){
		$str = $this->pdo->pdoSet($data, $values);
		$sql = "UPDATE {$this->table} SET $str WHERE $row=$val";
		return $this->pdo->execute($sql, $values, $ret);
	}
	
	public function delete($val, $row='id'){
		$sql = "DELETE FROM {$this->table} WHERE $row=$val";
		return $this->pdo->execute($sql);
	}
	
    public function returnToJson($arr){
    	//debug($arr);
    	echo json_encode($arr);
    }
    

}