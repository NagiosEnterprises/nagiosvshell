<?php //config_viewer.php 

//expecting 

/////////////////////////////////////////////////////////////
//used to view object configurations 
//expects $array from objects file such as $hosts_objs 
//        $arg is the argument taken from the browser.  example object=services_objs 
function build_object_list($array, $arg) //expecting arrays from read_objects.php file 
{
	global $authorizations;
	$count = 0;

	print "<ul class='configlist'>";
	foreach($array as $a)
	{
		//default for no permissions 
		$title = '';
		$linkkey = '';
			//change variables based on type of object being viewed 
		switch($arg)
		{
			case 'hosts_objs':
			if($authorizations['configuration_information']==1)
			{
				$name=$a['host_name'];
				$linkkey = 'host'.$a['host_name'];
				#$link = htmlentities(BASEURL.'index.php?cmd=gethostdetail&arg='.$name);
				$link = htmlentities(BASEURL.'index.php?mode=filter&type=hostdetail&arg='.$name);
				$title = "Host: <a href='$link' title='Host Details'>$name</a>";
			}
			//else{ continue; }
			break;
			
			case 'services_objs':
			if($authorizations['configuration_information']==1)
			{
				$count++;
				$name=$a['service_description'];
				$linkkey = 'service'.$count;
				$host = $a['host_name'];
				#$hlink = htmlentities(BASEURL.'index.php?cmd=gethostdetail&arg='.$host);
				$hlink = htmlentities(BASEURL.'index.php?mode=filter&type=hostdetail&arg='.$host);
				#$link = htmlentities(BASEURL.'index.php?cmd=getservicedetail&arg='.$linkkey);
				$link = htmlentities(BASEURL.'index.php?mode=filter&type=servicedetail&arg='.$linkkey);
				$title = "Host: <a href='$hlink' title='Host Details'>$host</a> 
							Service:<a href='$link' title='Service Details'>$name</a>";	
			}							
			break;
			
			case 'commands':
			if(($authorizations['host_commands']==1 && $authorizations['service_commands'])
			||$authorizations['system_commands']==1 )
			{
				$name=$a['command_name'];
				$title = "Command: $name";
				$linkkey = $name;
			}
			break;
			
			case 'hostgroups_objs':
			if($authorizations['configuration_information']==1)
			{
				$name=$a['hostgroup_name'];
				$title = "Group Name: $name";
				$linkkey = 'hg'.$name;
			}
			break;
			
			case 'servicegroups_objs':
			if($authorizations['configuration_information']==1)
			{
				$name=$a['servicegroup_name'];
				$title = "Group Name: $name";
				$linkkey = 'sg'.$name;
			}
			break;
			
			case 'timeperiods':
			if($authorizations['configuration_information']==1)
			{
				$name=$a['timeperiod_name'];
				$title = "Timeperiod: $name";
				$linkkey = 'tp'.$name;
			}
			break;
			
			case 'contacts':
			if($authorizations['configuration_information']==1)
			{
				$name=$a['contact_name'];
				$title = "Contact: $name";
				$linkkey = $name;
			}
			break;
			
			case 'contactgroups':
			if($authorizations['configuration_information']==1)
			{
				$name=$a['contactgroup_name'];
				$title = "Contact Group: $name";
				$linkkey = $name;
			}
			break;
			
			default:
			$title = 'Access Denied<br />';
			$linkkey = 'You do not have permissions to view this information';
			break;
			 
		}	
		
		$id = preg_replace('/[\. ]/', '_', $linkkey); //replacing dots with underscores
		#$id = preg_replace('/\ /', '_', $id);    //replacing spaces with underscores
		//using HEREDOC string syntax 
		$confighead=<<<CONFIG
				
		<li class="configlist">{$title} <a class='label' onclick='showHide("{$id}")' href='javascript:void(0)'>
		<img class='label' src="views/images/expand.gif" title="Show Config" alt="Image" height="12" width="12" />
		</a></li> 
				
		<div class='hidden' id='{$id}'>
		
		<table class="objectList"> 
		<tr><th>Config</th><th>Value</th></tr>
		
CONFIG;
//end HEREDOC 
		if($title!='') //only display if authorized 
		{
			print $confighead;
			//print raw config data into a table 
			foreach($a as $key => $value)
			{	
				
				print "<tr class='objectList'>
							<td>$key</td><td>$value</td>
						</tr>\n";			
			}
			print "</table></div>";
		}//end IF 
	}//end FOREACH loop
	print "<ul>";	
}//end function 



?>