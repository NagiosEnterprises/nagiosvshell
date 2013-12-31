<?php //user authentication 


//initialize main classes 
$NagiosData = new NagiosData();
$NagiosUser = new NagiosUser(); 




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







?>