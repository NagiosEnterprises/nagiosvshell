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
	else
	{
		echo "Access Denied: Please log into Nagios Core.";
		return false; 
	}	
	
}




?>	
		
