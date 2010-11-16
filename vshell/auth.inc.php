<?php //user authentication 


function check_auth() //return $username if logged into nagios 
{

	// HTTP BASIC AUTHENTICATION through Nagios Core or XI 
	//$remote_user="";
	if(isset($_SERVER["REMOTE_USER"]))
	{	
		$remote_user=$_SERVER["REMOTE_USER"];
		//echo "REMOTE USER is set: $remote_user<br />";
		return $remote_user;
	}
	//digest authentication 
	elseif(isset($_SERVER['PHP_AUTH_USER']))
	{
		//echo "Auth Digest detected".$_SERVER['PHP_AUTH_USER'];
		return $_SERVER['PHP_AUTH_USER'];
	}
	else
	{
		echo "Access Denied: No authentication detected.";
		return false; 
	}	
 
}




?>	
		
