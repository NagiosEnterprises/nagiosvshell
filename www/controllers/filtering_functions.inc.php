<?php //filtering_functions.inc.php




function user_filtering($data,$type)
{
	global $NagiosUser; 
	$new_data = array(); 
	//rebuild array for auth hosts 
	if($type=='hosts')	{
		foreach($data as $d) {
			//echo $d['host_name'];  
			if($NagiosUser->is_authorized_for_host($d['host_name']) ) $new_data[] = $d; 
			
		}
	}
	//rebuild array for auth services 
	if($type=='services') {
		foreach($data as $d) {
			//print "<pre>".print_r($d,true)."</pre>"; 
			if($NagiosUser->is_authorized_for_service($d['host_name'],$d['service_description'])) $new_data[] = $d; 
			//die(); 
		}
	}
	return $new_data; 

}


function process_state_filter($filter_str)
{
	$ret_filter = NULL;
	$filter_str = strtoupper($filter_str);
	$valid_states = array('UP', 'DOWN', 'UNREACHABLE', 'OK', 'CRITICAL', 
								'WARNING', 'UNKNOWN', 'PENDING', 'PROBLEMS','UNHANDLED', 'ACKNOWLEDGED');


	if (in_array($filter_str, $valid_states))
	{
		$ret_filter = $filter_str;
	}
	return $ret_filter;
}

function process_name_filter($filter_str) {
	//$filter_str = preg_quote($filter_str, '/'); //removed strtolower -MG 
	$filter_str = strtolower(rawurldecode($filter_str)); 	
	return $filter_str;
}

function process_objtype_filter($filter_str)
{
	$ret_filter = NULL;
	$filter_str = strtolower($filter_str);
	$valid_objtypes = array('hosts_objs', 'services_objs', 'hostgroups_objs', 'servicegroups_objs',
		'timeperiods', 'contacts', 'contactgroups', 'commands');
	if (in_array($filter_str, $valid_objtypes))
	{
		$ret_filter = $filter_str;
	}
	return $ret_filter;
}



?>