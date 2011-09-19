<?php  //output_functions.inc.php

function object_output($objtype_filter, $data, $mode)
{
	$retval = '';
	switch($mode)
	{
		case 'html':
			include(DIRBASE.'/views/config_viewer.php');
			$retval = build_object_list($data, $objtype_filter);
		break;
	}
	return $retval;
}



function host_and_service_detail_output($type, $data, $mode)
{
	$retval = '';
	switch($mode)
	{
		case 'html':
			require_once(DIRBASE.'/views/'.$type.'s.php');
			$display_function = 'get_'.preg_replace('/detail/', '_detail', $type).'s'; 
			$retval = $display_function($data);
		break;
	}
	return $retval;
}


function hostgroups_and_servicegroups_output($type, $data, $mode)
{
	$retval = '';
	switch($mode)
	{
		case 'html':
			$title = ucwords(preg_replace('/objs/', 'Objects', preg_replace('/_/', ' ', $type)));
			$display_function = 'display_'.$type;
			$retval = $display_function($data);
		break;
	}
	return $retval;
}


function hosts_and_services_output($type, $data, $mode)
{
	$retval = '';
	switch($mode)
	{
		case 'html':
			list($start, $limit) = get_pagination_values();
			$title = ucwords(preg_replace('/objs/', 'Objects', preg_replace('/_/', ' ', $type)));
			include_once(DIRBASE.'/views/'.$type.'.php');
			$display_function = 'display_'.$type;
			$retval = $display_function($data, $start, $limit);
		break;
	}
	return $retval;
}

?>