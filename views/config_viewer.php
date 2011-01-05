<?php //config_viewer.php 

/////////////////////////////////////////////////////////////
//used to view object configurations 
//expects $array from objects file such as $hosts_objs 
//        $arg is the argument taken from the browser.  example object=services_objs 
function build_object_list($data, $arg) //expecting arrays from read_objects.php file 
{
	global $authorizations;
	$count = 0;

	$object_list = '';


	$name_filter = isset($_GET['name_filter']) ? $_GET['name_filter'] : '';
	$objtype_filter = isset($_GET['objtype_filter']) ? $_GET['objtype_filter'] : '';
	$type = isset($_GET['type']) ? $_GET['type'] : '';

	$object_list .= <<<FILTERDIV
<div class='resultFilter'>
	<form id='resultfilterform' action='{$_SERVER['PHP_SELF']}' method='get'>
		<input type="hidden" name="type" value="$type">
		<input type="hidden" name="objtype_filter" value="$objtype_filter">
		<label class='label' for='name_filter'>Search Configuration Name:</label>
		<input type="text" name='name_filter' value="$name_filter"></input>
		<input type='submit' name='submitbutton' value='Filter' />
	</form>
</div>
FILTERDIV;

	$object_list .= "<ul class='configlist'>";
	foreach($data as $a)
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
				$link = htmlentities(BASEURL.'index.php?type=hostdetail&name_filter='.$name);
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
				$hlink = htmlentities(BASEURL.'index.php?type=hostdetail&name_filter='.$host);
				#$link = htmlentities(BASEURL.'index.php?cmd=getservicedetail&arg='.$linkkey);
				$link = htmlentities(BASEURL.'index.php?type=servicedetail&name_filter='.$linkkey);
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

		if($title!='') //only display if authorized 
		{
			$object_list .= $confighead;
			//print raw config data into a table 
			foreach($a as $key => $value)
			{	
				
				$object_list .= <<<TABLEROW
<tr class='objectList'>
	<td>$key</td><td>$value</td>
</tr>
TABLEROW;
			}
			$object_list .= "</table></div>";
		}//end IF 
	}//end FOREACH loop
	$object_list .= "<ul>";

	return $object_list;
}//end function 



?>
