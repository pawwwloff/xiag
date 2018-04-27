<?php

namespace vendor\core\helper;

class Cookie
{
	public static $int = 3600;
    /**
     * Add cookies
     * @param $key
     * @param $value
     * @param int $time
     */
    public static function set($key, $value, $time = false)
    {
    	if(!$time){
    		$time = time()+self::$int;
    	}
    	self::setCookie($key, $value, $time);
        //setcookie($key, $value, $time, '/') ;
    }

    /**
     * Get cookies by key
     * @param $key
     * @return null
     */
    public static function get($key)
    {
        if ( isset($_COOKIE[$key]) ) {
            return $_COOKIE[$key];
        }
        return null;
    }

    /**
     * Delete cookies by key
     * @param $key
     */
    public static function delete($key)
    {
        if ( isset($_COOKIE[$key]) ) {
            self::set($key, '', -3600);
            unset($_COOKIE[$key]);
        }
    }
    

    public static function setCookie($name, $value = '', $time){
    	setcookie($name,$value,$time,'/',false);
    	$_COOKIE[$name] = $value;
    }
}