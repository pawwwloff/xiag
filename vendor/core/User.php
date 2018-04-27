<?php

namespace vendor\core;

use vendor\core\base\Model;
use vendor\core\auth\Auth;

class User extends Model{
	 
	public $table = 'user';
	public $auth = null;
	private $user = [];
	
	public function __construct($di){
		parent::__construct($di);
		$this->auth = new Auth();
		if($this->auth->authorized()){
			$this->user = $this->findOne($this->auth->hashUser(), 'hash');
			if(empty($this->user)){
				$this->auth->unAuthorize();
			}
		}
	}
	
	public function userLogin($param){
		$param['password'] = $this->auth->encryptPassword($param['password']);
		$this->user = $this->findOne($param['name'], 'name');
		if(empty($this->user)){
			unset($param['action']);
			$param['hash'] = $this->auth->encryptPassword($param['name'].$param['password']);
			return $this->userRegister($param);
		}elseif($this->user['password']!=$param['password']){
			$this->error->setError('Пароль не верный');
		}else{
			$hash = $this->auth->encryptPassword($this->user['name'].$this->user['password'].time());
			$this->user = $this->changeBy(['hash'=>$hash], $this->user['id']);
			$this->auth->authorize($hash);
		}
		return true;
	}
	
	public function userRegister($param){
		if(strlen($param['password'])<6){
			$this->error->setError('Пароль должен быть не меньше 6 символов');
		}
		$userId = $this->into($param, true);
		if($userId>0){
			$this->user = $this->findOne($userId, 'id');
			$this->auth->authorize($param['hash']);
		}
		return true;
	}
	
	public function userUnLogin(){
		if($this->auth->authorized()){
			$this->user = $this->findOne($this->auth->hashUser(), 'hash');
			if(!empty($this->user)){
				$this->auth->unAuthorize();	
				header('Location: /');
			}
		}
		return true;
	}
	
	public function getUserId() {
		return $this->user['id'];
	}
	
}