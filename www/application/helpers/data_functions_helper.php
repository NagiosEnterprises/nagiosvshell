<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function hosts_and_services_data($type, $state_filter = NULL, $name_filter = NULL, $host_filter = NULL) {
	$CI = &get_instance();

	$data = $CI->nagios_data->getProperty($type);

	//add filter for user-level filtering
	if(! $CI->nagios_user->is_admin()) {
		$data = user_filtering($data, $type);
	}

	$data_in = $data;

	if ($state_filter) {
		//merge arrays for multiple states
		if($state_filter == 'PROBLEMS' || $state_filter == 'UNHANDLED' || $state_filter == 'ACKNOWLEDGED') {
			$data = array_merge(
				get_by_state('UNKNOWN', $data_in),
				get_by_state('CRITICAL', $data_in),
				get_by_state('WARNING', $data_in),
				get_by_state('UNREACHABLE', $data_in),
				get_by_state('DOWN', $data_in)
			);

			//filter down problem array
			if($state_filter == 'UNHANDLED') {
				//loop and return array
				$unhandled = array();
				foreach($data as $d) {
					if( $d['problem_has_been_acknowledged'] == 0 && $d['scheduled_downtime_depth'] == 0 ){
						$unhandled[] = $d;
					}
				}
				$data = $unhandled;
			}

			if($state_filter == 'ACKNOWLEDGED') {
				//loop and return array
				$acknowledged = array();
				foreach($data as $d) {
					if( $d['problem_has_been_acknowledged'] > 0 || $d['scheduled_downtime_depth'] > 0 ){
						$acknowledged[] = $d;
					}
				}
				$data = $acknowledged;
			}
		} elseif($state_filter == 'PENDING' || $state_filter == 'OK' || $state_filter == 'UP') {
			$filtered = array();

			if($state_filter == 'PENDING') {
				foreach($data as $d) {
					if( $d['current_state'] == 0 && $d['last_check'] == 0 ){
						$filtered[] = $d;
					}
				}
			} else {
				foreach($data as $d) {
					if( $d['current_state'] == 0 && $d['last_check'] != 0 ){
						$filtered[] = $d;
					}
				}
			}
			$data = $filtered;
		} else {
			$s = ($type == 'services') ? true : false;
			$data = get_by_state($state_filter, $data,$s);
		}
	}

	if ($name_filter) {
		$name_data = get_by_name($name_filter, $data);
		$service_data = get_by_name($name_filter, $data, 'service_description');
		$data = $name_data;
		foreach ($service_data as $i => $service) {
			if (!isset($data[$i])) {
				$data[$i] = $service;
			}
		}
		$data = array_values($data);
	}

	if ($host_filter) {
		$name_data = get_by_name($name_filter, $data,'host_name',$host_filter);
		$service_data = get_by_name($name_filter, $data, 'service_description',$host_filter);
		$data = $name_data;
		foreach ($service_data as $i => $service) {
			if (!isset($data[$i])) {
				$data[$i] = $service;
			}
		}
		$data = array_values($data);
	}

	return $data;
}

function host_and_service_detail_data($type, $name) {
	$data_function = 'process_'.preg_replace('/detail/', '_detail', $type);
	$data = $data_function(stripslashes($name)); //added stripslashes because hostnames with periods had them in the variable -MG
	return $data;
}

function hostgroups_and_servicegroups_data($type, $name_filter = NULL) {
	include_once(DIRBASE.'/views/'.$type.'.php');
	$data_function = 'get_'.preg_replace('/s$/', '', $type).'_data';
	$data = $data_function();
	if ($name_filter) {
		// TODO filters against Services and/or hosts within groups, status of services/hosts in groups, etc...
		$name = preg_quote($name_filter, '/');

		//XXX create_function needs to be removed
		$match_keys = array_filter(array_keys($data), create_function('$d', 'return !preg_match("/'.$name.'/i", $d);'));

		// XXX is there a better way?
		foreach ($match_keys as $key) {
			unset($data[$key]);
		}
	}

	return $data;
}

function object_data($objtype_filter, $name_filter) {
	$CI = &get_instance();

	$valid_objtype_filters = array(
		'hosts_objs',
		'services_objs',
		'hostgroups_objs',
		'servicegroups_objs',
		'timeperiods',
		'contacts',
		'contactgroups',
		'commands'
	);

	if (in_array($objtype_filter, $valid_objtype_filters)) {
		$data = $CI->nagios_data->getProperty($objtype_filter);

		if ($name_filter) {
			$name_data = get_by_name($name_filter, $data);
			$service_data = get_by_name($name_filter, $data, 'service_description');

			$data = $name_data;
			foreach ($service_data as $i => $service) {
				if (!isset($data[$i])) {
					$data[$i] = $service;
				}
			}
		}
	}
	return $data;
}

/* End of file data_functions_helper.php */
/* Location: ./application/helpers/data_functions_helper.php */
