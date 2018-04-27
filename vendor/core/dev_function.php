<?php 
function dd($data, $die=false){
	$backtrace = debug_backtrace();
	echo "<pre>";
	print_r($data);
	echo "</pre>";
	echo "<pre style='color: green'>";
	echo $backtrace[0]['file']." line ".$backtrace[0]['line'];
	echo "</pre>";
	if($die){
		die();
	}
}
?>