<?php 

namespace  vendor\core\database;

class Connection {
	
	protected $pdo;
	protected static $instance;
	protected $query = 0;
	
	private function __construct(){
		$db = require $_SERVER['DOCUMENT_ROOT'].'/../config/Db.php';
		$dsn = "mysql:host={$db['Host']};dbname={$db['Name']};charset={$db['Charset']}";
		$this->pdo = new \PDO($dsn, $db['Login'], $db['Password'], $db['opt']);
	}
	
	public static function instance(){
		if(self::$instance === null){
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	public function execute($sql, $values=array(), $ret = false) {
		$statement = $this->pdo->prepare($sql);
		$this->query++;
		if($ret){
			$statement->execute($values);
			return $this->pdo->lastInsertId();
		}else{
			return $statement->execute($values);
		}
	}
	
	public function query($sql, $values=array()) {
		$statement = $this->pdo->prepare($sql);
		$this->query++;

		$res = $statement->execute($values);
		if($res !== false){
			return $statement->fetchAll();
		}
		return array();
	}
	
	public function pdoSet($source=array(), &$values = array(), $limiter = ', ') {
		$set = '';
		if(isset($source['LOGIC'])&&in_array($source['LOGIC'], ['OR', 'AND'])){
			$logic = $source['LOGIC'];
			unset($source['LOGIC']);
			$str = [];
			foreach ($source as $d=>$params){
				$str[$d]="(";
				foreach ($params as $key=>$field) {
					$str[$d].="`".str_replace("`","``",$key)."`". "=:$key{$limiter}";
					$values[$key] = $field;
				}
				$str[$d] = substr($str[$d], 0, -strlen($limiter));
				$str[$d].=")";
			}
			$set = implode(" $logic ", $str);
			return ($set);
		}else{
			foreach ($source as $key=>$field) {
				$set.="`".str_replace("`","``",$key)."`". "=:$key{$limiter}";
				$values[$key] = $field;
			}
			return substr($set, 0, -strlen($limiter));
		}
		
	}
	
	public function getQuery() {
		return $this->query;
	}
	
}
