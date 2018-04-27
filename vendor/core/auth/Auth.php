<?php 

namespace vendor\core\auth;

use vendor\core\helper\Cookie;

class Auth implements AuthInterface{
	
	/**
	 * 
	 * @var bool
	 */
	protected $authorized = false;
	protected $hash_user = null;
	
	public function __construct(){
		$this->authorized = Cookie::get('auth_authorized');
		$this->hash_user = Cookie::get('auth_user');
	}
	
	/**
	 * @return bool
	 */
	public function authorized(){
		return $this->authorized;
	}
	
	/**
	 * @return mixed
	 */
	public function hashUser(){
		return $this->hash_user;
	}
	
	/**
	 * User authorization
	 * @param $user
	 */
	public function authorize($user){
		
		Cookie::set('auth_authorized', true);
		Cookie::set('auth_user', $user);
		
		$this->authorized = true;
		$this->hash_user = $user;
	}
	
	/**
	 * User unauthorization
	 * @return void
	 */
	public function unAuthorize(){
		Cookie::delete('auth_authorized');
		Cookie::delete('auth_user');
	
		$this->authorized = false;
		$this->hash_user = null;
	}
	
	/**
	 * Generates a hash
	 * @param $password
	 * @param $salt
	 * @return string
	 */
	public static function encryptPassword($password){
		return hash('sha256', $password);
	}

	
}