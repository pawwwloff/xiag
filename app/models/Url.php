<?php 

namespace app\models;

use vendor\core\base\Model;

class Url extends Model{

	public $table = 'url';
	private static $chars = "bcdfghjkmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ";
	private $cur;
	private $uri;
	
	public function __construct($di){
		parent::__construct($di);
		if($di->get('request')->server['REQUEST_METHOD']=='POST'){
			$url = $di->get('request')->post['url'];
			if ($this->validateUrlFormat($url) == false) {
				throw new \Exception(
						"URL is not in the correct format.");
			}
			$server = $di->get('request')->server;
			$this->cur = $this->findOne($url, 'longUrl');
			if($this->cur){
				$this->uri = $server['HTTP_REFERER'].$this->cur['shortUrl'];
			}else{
				$this->createShortCode($url);
				$this->uri = $server['HTTP_REFERER'].$this->cur['shortUrl'];
			}
		}
	}
	
	public function getUrlByShortcode(){
		$requestUri = ltrim(rtrim($this->di->get('request')->server['REQUEST_URI'],'/'),"/");
		$this->cur = $this->findOne($requestUri, 'shortUrl');
		return $this->cur?$this->cur['longUrl']:false;
	}
	
	public function redirect(){
		$url = $this->getUrlByShortcode();
		if($url){
			header('Location: '.$url);
			exit();
		}
		return false;
	}
	
	public function getCur(){
		return $this->cur;
	}
	public function getUri(){
		return $this->uri;
	}
	
	private function validateUrlFormat($url) {
        return filter_var($url, FILTER_VALIDATE_URL,
            FILTER_FLAG_HOST_REQUIRED);
    }
    
    private function createShortCode($url) {
    	$this->cur['id'] = $this->into(['longUrl'=>$url], true);
    	$this->cur['longUrl'] = $url;
    	$this->cur['shortUrl'] = $this->convertIntToShortCode($this->cur['id']);
    	$this->changeBy(['shortUrl'=>$this->cur['shortUrl']], $this->cur['id']);
    }
    
    private function convertIntToShortCode($id) {
    	$id = intval($id);
    	if ($id < 1) {
    		throw new \Exception(
    				"ID не является некорректным целым числом.");
    	}
    	$length = strlen(self::$chars);
    	if ($length < 10) {
    		throw new \Exception("Длина строки мала");
    	}
    	$code = "";
    	while ($id > $length - 1) {
    		$code = self::$chars[fmod($id, $length)] .
    		$code;
    		$id = floor($id / $length);
    	}
	   	$code = self::$chars[$id] . $code;
    	if(strlen($code)<10){
    		$length = 10 - strlen($code);
    		for ($i = 0; $i < $length; $i++) {
    			$code .= substr(self::$chars, rand(1, strlen(self::$chars)) - 1, 1);
    		}
    	}
    	return $code;
    }
}