<?php

   /*************************************************************
	*		Alfasms API 1.0 for way2sms							*
	*		Author		  : Alfred francis						*
	*		Email         : alfredputhurh@gmail.com				*
	*		API homepage  : www.alfredfrancis.in/alfasms-api	*	
	*		Feel free to edit,share and publish					*
	*************************************************************/


include_once "alfa.curl.class.php";

class AlfaSMS
{
	var $username;
	var $password;
	var $number;
	var $msg;
	var $curl;
	var $login;
	var $post_data;
	var $content;
	var $url;
	var $ref;
	
	public function __construct()
	{
		$this->loginok=false;
		$this->curl=new AlfacURL();
	}

	public function login($username,$password)
	{
	$post_data = "mobileNo=&message=&username=$username&password=$password";
	$url = "http://site4.way2sms.com/Login1.action";
	$ref="http://site4.way2sms.com/entry.jsp";
	$content=($this->curl->post($url,$post_data,$ref));
	
			if(!stristr($content,"Logout"))
			{
				$this->login=false;
				return false;							
			}
			else
			{
				$this->login=true;
				return true;
			}
	
	}
	
	public function send($number,$msg)
	{
		if($this->login)
		{

			$msg=urlencode($msg);
			$post_data ="HiddenAction=instantsms&catnamedis=Birthday&Action=sa65sdf656fdfd&chkall=on&MobNo=$number&textArea=$msg";
			$url = "http://site4.way2sms.com/quicksms.action";
			$ref="http://site4.way2sms.com/jsp/InstantSMS.jsp";
			$content=($this->curl->post($url,$post_data,$ref));
				
				if(stristr($content,"successfully"))
				{
					return true;
				}
				else
				{
					return false;
				}
		}
		else
		{
			echo "<h2>Please login first before sending SMS</h2>";
		}
	}
	
	public function logout()
	{
		$post_data ="1=1";
		$url = "http://site4.way2sms.com/LogOut";
		$content=($this->curl->post($url,$post_data));
		
		
			if(stristr($content,"successfully"))
			{
				return true;
			}
			else
			{
				return false;
			}
	}
	
}

?>