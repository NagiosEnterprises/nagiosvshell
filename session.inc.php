<?php //user authentication 

//initializes all session variables as neccessary 
function init_vshell()
{
	
	//gettext support 
	$loc = setlocale(LC_ALL, LANG, LANG.'utf-8', LANG.'utf8', "en_GB.utf8");
	if (!isset($loc)) {
	 echo gettext("Error in setting the correct locale, please report this error with the associated output of  'locale -a' to mguthrie@nagios.com")."<br>";
	}
	putenv("LC_ALL=".LANG);
	putenv("LANG=".LANG);
	bindtextdomain(LANG, 'locale/');
	bind_textdomain_codeset(LANG, 'UTF-8');
	textdomain(LANG); 


}


function get_user() //return $username if logged into nagios 
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