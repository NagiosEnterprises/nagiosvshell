<?php //config_viewer.php 

/////////////////////////////////////////////////////////////
//used to view object configurations 
//expects $array from objects file such as $hosts_objs 
//        $arg is the argument taken from the browser.  example object=services_objs 
function build_object_list($data, $arg) //expecting arrays from read_objects.php file 
{	
	$count = 0;
	$object_list = '';
	$name_filter = isset($_GET['name_filter']) ? htmlentities($_GET['name_filter']) : '';
	$objtype_filter = isset($_GET['objtype_filter']) ? htmlentities($_GET['objtype_filter']) : '';
	$type = isset($_GET['type']) ? htmlentities($_GET['type']) : '';

/*   //commented out, needs further revisions to be used on config pages.  Only host filter works right now -MG 
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
*/ 


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
				$name=$a['host_name'];
				$linkkey = 'host'.$a['host_name'];
				#$link = htmlentities(BASEURL.'index.php?cmd=gethostdetail&arg='.$name);
				$link = htmlentities(BASEURL.'index.php?type=hostdetail&name_filter=').urlencode($name);
				$title = gettext('Host').": <a href='$link' title='Host Details'>$name</a>";
			break;
			
			case 'services_objs':
				$count++;
				$name=$a['service_description'];
				$linkkey = 'service'.$count;
				$host = $a['host_name'];
				#$hlink = htmlentities(BASEURL.'index.php?cmd=gethostdetail&arg='.$host);
				$hlink = htmlentities(BASEURL.'index.php?type=hostdetail&name_filter=').urlencode($host);
				#$link = htmlentities(BASEURL.'index.php?cmd=getservicedetail&arg='.$linkkey);
				$link = htmlentities(BASEURL.'index.php?type=servicedetail&name_filter='.$linkkey);
				$title = gettext('Host').": <a href='$hlink' title='Host Details'>$host</a> 
							".gettext('Service').":<a href='$link' title='Service Details'>$name</a>";	
						
			break;
			
			case 'commands':
				$name=$a['command_name'];
				$title = gettext('Command').": $name";
				$linkkey = $name;
			break;
			
			case 'hostgroups_objs':
				$name=$a['hostgroup_name'];
				$title = gettext('Group Name').": $name";
				$linkkey = 'hg'.$name;
			break;
			
			case 'servicegroups_objs':
				$name=$a['servicegroup_name'];
				$title = gettext('Group Name').": $name";
				$linkkey = 'sg'.$name;
			break;
			
			case 'timeperiods':
				$name=$a['timeperiod_name'];
				$title = gettext('Timeperiod').": $name";
				$linkkey = 'tp'.$name;
			break;
			
			case 'contacts':
				$name=$a['contact_name'];
				$title = gettext('Contact').": $name";
				$linkkey = $name;
			break;
			
			case 'contactgroups':
				$name=$a['contactgroup_name'];
				$title = gettext('Contact Group').": $name";
				$linkkey = $name;
			break;
			
			default:
				$title = gettext('Access Denied').'<br />';
				$linkkey = gettext('You do not have permissions to view this information');
			break;
			 
		}	
		
		$id = preg_replace('/[\. ]/', '_', $linkkey); //replacing dots with underscores
		#$id = preg_replace('/\ /', '_', $id);    //replacing spaces with underscores
		$confighead="
				
		<li class='configlist'>{$title} <a class='label' onclick='showHide(\"{$id}\")' href='javascript:void(0)'>
		<img class='label' src='views/images/expand.gif' title='Show Config' alt='Image' height='12' width='12' />
		</a></li> 
				
		<div class='hidden' id='{$id}'>
		
		<table class='objectList'> 
		<tr><th>".gettext('Config')."</th><th>".gettext('Value')."</th></tr>
		
";

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
