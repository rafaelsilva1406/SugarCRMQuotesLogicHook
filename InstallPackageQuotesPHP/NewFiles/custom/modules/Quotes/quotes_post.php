<?php
//deny any access other than sugar
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
//custom hook class
class QuotesPost {
	static $already_ran = false; //prevent from running multiple times
	//custom hook method
	function clonePost(&$bean, $event, $arguments) 
	{	
		if(self::$already_ran == true) return; //if static var set a return 
		self::$already_ran = true; //else set it to true frist time running
		
		$quotes_post = array();//custom array capture one/two dimensional array
		$keyCheck = array(//set up static keys to copy from post
					'module', //example
		);
		foreach($_POST as $key => $value)  //each key and value in post array
		{
			if(in_array($key, $keyCheck)){	//if key in post match array static keys
				if(is_string($value)){
					$quotes_post[$key]  = $value;//set key and value to custom array dynamically
				}elseif(is_array($value)){
					$arr = array();
					foreach($value as $childKey => $childValue){
							$arr[$childKey] = $childValue;
					}
					$quotes_post[$key] = json_encode($arr);
				}
			}
		}
		if($bean->id) {//sugar bean object call method return value is set	
			$quotes_post['quote_id'] = $bean->id;//store manually key val
		}
		$this->mailArray("ITStaff Don't Reply", 'SugarCRM Quotes Module New Order',$quotes_post);//send data to mail 
		$rest_url = 'http://webservice';//set web service rest url
			$curlOpt = array(//set up curl options
				CURLOPT_URL => $rest_url, //set url
				CURLOPT_POST => 1, //set type method to be used
				CURLOPT_POSTFIELDS => $quotes_post, //set key values array
				CURLOPT_RETURNTRANSFER => 1, //set return transfer 
				CURLOPT_HEADER => 0 //set header options
			);
			$ch = curl_init();//set up init method
			curl_setopt_array($ch,$curlOpt); //pass in curl options
			$response = curl_exec($ch); //wait for return value from web service
			curl_close($ch);//set curl option close curl
			$arrRes = json_decode($response, true); //decode response from web service
		if($arrRes['success']){ //if key exists
			$this->mailString("ITStaff Don't Reply", 'SugarCRM Module Success', $arrRes['success']);//send message to mail
		}elseif($arrRes['error']){//else if this key exists
			$this->mailString("ITStaff Don't Reply", 'SugarCRM Module Error', $arrRes['error']);//send message to mail
			}else{
			$this->mailString("ITStaff Don't Reply", 'SugarCRM Module Server', $arrRes);//send message to mail
			}
	}
	//you could use the SugarCRM mail class but I will keep it basic using php built in mail function
	//this takes in subject, headling on body and string message
	function mailString($sub, $line, $str){
		$to = 'toname@email.com'; //set up mail to email
		$subject = $sub; //set up mail subject email
		$txt = $line . ":" . "\r\n"; //body headline
		$txt .= (string)$str; //set up mail inline message
		$headers = "From: fromname@email.com"; //set up mail header from emial & copy email
		mail($to,$subject,$txt,$headers); //php mail method
	}
	//you could use the SugarCRM mail class but I will keep it basic using php built in mail function
	//this takes in subject, headline on body and array message
	function mailArray($sub, $line, array $arr){
		$to = 'toname@email.com'; //set up mail to email
		$subject = $sub; //set up mail subject email
		$txt = $line . ":" . "\r\n\r\n"; //body headline
		foreach($arr as $key => $value){ //
			if(is_string($value)){
				$txt .= $key . " = " . $value . "\r\n\r\n"; //set up mail inline message
			}
			if(is_array($value)){
				foreach($value as $childKey => $childvalue){
					$txt .= $childKey . " = " . $childvalue . "\r\n\r\n"; //set up mail inline message
				}
			}
		}
		$headers = "From: fromname@email.com"; //set up mail header from emial & copy email
		$headers .="cc: ccname@email.com";
		mail($to,$subject,$txt,$headers); //php mail method
	}
	//Search through SugarCRM table return single col that matches col value found
	//method take in strings first column to return second what table to look for third column to find and last value of column to find
	function sqlSingleColumn($col,$table,$findField,$valueField){
		$sql = "SELECT {$col} FROM {$table} WHERE {$findField}='{$valueField}'";//obtain specific field from table
		$result = $GLOBALS["db"]->query($sql);//obtain query result
		while($row = $GLOBALS["db"]->fetchByAssoc($result) )//while result return filter through 
		{
			return $row[$col];//store data to array
 		}
	}
	//call this methis when debugging prevents it from going to next screen same as die method 
	//you can call it like this $this->SugarDebug
	//take in array and dumps it on screen
	function SugarDebug(array $arr){
		var_dump($arr);
		sugar_die(); //stop point sugar logic 
	}
}
?>